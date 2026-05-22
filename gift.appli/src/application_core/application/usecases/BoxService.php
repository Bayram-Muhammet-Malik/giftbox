<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

use \gift\core\application\exceptions\DataErrorException;
use \gift\core\application\exceptions\NotFoundException;
use \gift\core\application\exceptions\UnauthorizedException;
use gift\core\application\domain\entities\Box;

class BoxService implements BoxInterface {

    public function createBox(string $libelle, string $description, bool $kdo, ?string $message_kdo, int $createur_id): array{
        if ($kdo && empty($messageKdo)) throw new DataErrorException("Message cadeau obligatoire pour une box cadeau");

        $box = new Box();
        $box->id = bin2hex(random_bytes(16));
        $box->token = null;
        $box->libelle = $libelle;
        $box->description = $description;
        $box->montant = 0;
        $box->kdo = $kdo ? 1 : 0;
        $box->message_kdo = $messageKdo;
        $box->statut = 1;
        $box->createur_id = $createurId;
        $box->save();

        return $box->toArray();
    }

    public function addPrestations(int $id, int $presta_id, int $quantity, int $createur_id): array{
        $box = Box::find($boxId);
        if (!$box) throw new NotFoundException("Box non trouvée dans la base de donnée.");
        if ($box->createur_id !== $userId) throw new UnauthorizedException("Accès interdit");
        if ($box->statut !== 1) throw new DataErrorException("Impossible de modifier une box non créée");
        if ($quantite < 1) throw new DataErrorException("Quantité invalide");

        $presta = Prestation::find($prestaId);
        if (!$presta) throw new NotFoundException("Prestation non trouvée dans la base de donnée.");

        $prestations = [];
        foreach ($box->prestation as $p) $prestations[$p->id] = ['quantite' => $p->pivot->quantite];

        if (isset($prestations[$prestaId])) {
            $prestations[$prestaId]['quantite'] += $quantite;
        } else {
            $prestations[$prestaId] = ['quantite' => $quantite];
        }
        $box->prestation()->sync($prestations);
        
        $total = 0;
        foreach ($box->prestation as $p) $total += $p->tarif * $p->pivot->quantite;
        $box->montant = $total;
        $box->save();

        return $box->load('prestation')->toArray();
    }

    public function getBox(int $id, int $createur_id): array{
        $box = Box::with('prestation')->find($boxId);
        if (!$box) throw new NotFoundException("Box non trouvée dans la base de donnée.");
        if ($box->createur_id !== $userId && $box->statut !== 4) throw new UnauthorizedException("Accès interdit");

        return $box->toArray();
    }

    public function validateBox(int $id, int $createur_id): array{
        $box = Box::with('prestation')->find($boxId);
        if (!$box) throw new NotFoundException("Box non trouvée dans la base de donnée.");
        if ($box->createur_id !== $userId) throw new UnauthorizedException("Accès interdit");
        if ($box->statut !== 1) throw new DataErrorException("La box doit être en état créée");
        if ($box->prestation->count() < 2) throw new DataErrorException("La box doit contenir au moins 2 prestations");

        $box->statut = 2;
        $box->save();

        return $box->toArray();
    }

    public function deliverBox(int $id, int $createur_id): array{
        $box = Box::find($boxId);
        if (!$box) throw new NotFoundException("Box non trouvée dans la base de donnée.");
        if ($box->createur_id !== $userId) throw new UnauthorizedException("Accès interdit");
        if ($box->statut !== 2) throw new DataErrorException("La box doit être validée avant livraison");

        $token = bin2hex(random_bytes(16));

        $box->token = $token;
        $box->statut = 3;
        $box->save();

        $res = $box->toArray();
        $res['url'] = "/box/$token";

        return $res;
    }

    public function boxUsed(string $token): array{
        $box = Box::where('token', $token)->first();
        if (!$box) throw new NotFoundException("Token invalide");
        if ($box->statut !== 3) throw new DataErrorException("Box déjà utilisée ou non livrée");

        $box->statut = 4;
        $box->save();

        return $box->load('prestation')->toArray();
    }
}
