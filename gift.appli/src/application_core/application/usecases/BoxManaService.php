<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use gift\core\application\exceptions\UnauthorizedException;
use gift\core\domain\entities\Box;
use gift\core\domain\entities\Prestation;

class BoxManaService implements BoxManaInterface
{
    public function createBox(
        string $libelle,
        string $description,
        bool $kdo,
        ?string $message_kdo,
        int $createur_id,
    ): array {
        if ($kdo && empty($message_kdo)) {
            throw new DataErrorException("Message cadeau obligatoire pour une box cadeau");
        }

        $box = new Box();
        $box->id = bin2hex(random_bytes(16));
        $box->token = null;
        $box->libelle = $libelle;
        $box->description = $description;
        $box->montant = 0;
        $box->kdo = $kdo ? 1 : 0;
        $box->message_kdo = $message_kdo;
        $box->statut = 1;
        $box->createur_id = $createur_id;
        $box->save();

        return $box->toArray();
    }

    public function addPrestations(
        int $id,
        int $presta_id,
        int $quantite,
        int $createur_id,
    ): array {
        $box = Box::with('prestation')->find($id);
        if (!$box) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        }

        if ($box->createur_id !== $createur_id) {
            throw new UnauthorizedException("Accès interdit");
        }

        $presta = Prestation::find($presta_id);
        if (!$presta) {
            throw new NotFoundException("Prestation non trouvée dans la base de donnée.");
        }

        $prestations = $box->preparePrestationsForSync($presta_id, $quantite);

        $box->prestation()->sync($prestations);
        $box->load('prestation');

        $box->recalculateMontant();
        $box->save();

        return $box->toArray();
    }

    public function getBox(int $id, int $createur_id): array
    {
        $box = Box::with('prestation')->find($id);
        if (!$box) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        }

        if ($box->createur_id !== $createur_id && $box->statut !== 4) {
            throw new UnauthorizedException("Accès interdit");
        }

        return $box->toArray();
    }

    public function validateBox(int $id): array
    {
        $box = Box::with('prestation')->find($id);
        if (!$box) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        }

        // if ($box->createur_id !== $createur_id) {
        //     throw new UnauthorizedException("Accès interdit");
        // }

        $box->validate();
        $box->save();

        return $box->toArray();
    }

    public function deliverBox(int $id, int $createur_id): array
    {
        $box = Box::find($id);
        if (!$box) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        }

        if ($box->createur_id !== $createur_id) {
            throw new UnauthorizedException("Accès interdit");
        }

        $token = bin2hex(random_bytes(16));

        $box->deliver($token);
        $box->save();

        $res = $box->toArray();
        $res['url'] = "/box/$token";

        return $res;
    }

    public function boxUsed(string $token): array
    {
        $box = Box::where('token', $token)->first();
        if (!$box) {
            throw new NotFoundException("Token invalide");
        }

        $box->markAsUsed();
        $box->save();

        return $box->load('prestation')->toArray();
    }
}
