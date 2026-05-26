<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use gift\core\application\exceptions\UnauthorizedException;
use gift\core\domain\entities\Box;
use gift\core\domain\entities\Prestation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class BoxManaService implements BoxManaInterface
{
    public function createBox(
        string $libelle,
        string $description,
        bool $kdo,
        ?string $message_kdo,
        int $createur_id,
    ): array {
        try {
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
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de la création de la box");
        }
    }

    public function addPrestations(
        int $id,
        int $presta_id,
        int $quantite,
        int $createur_id,
    ): array {
        try {
            $box = Box::with('prestation')->findOrFail($id);

            if ($box->createur_id !== $createur_id) {
                throw new UnauthorizedException("Accès interdit");
            }

            Prestation::findOrFail($presta_id);

            $prestations = $box->preparePrestationsForSync($presta_id, $quantite);

            $box->prestation()->sync($prestations);
            $box->load('prestation');
            $box->recalculateMontant();
            $box->save();

            return $box->toArray();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Box ou prestation non trouvée dans la base de donnée.");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de l'ajout de la prestation dans la box");
        }
    }

    public function getBox(int $id, int $createur_id): array
    {
        try {
            $box = Box::with('prestation')->findOrFail($id);

            if ($box->createur_id !== $createur_id && $box->statut !== 4) {
                throw new UnauthorizedException("Accès interdit");
            }

            return $box->toArray();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de la récupération de la box");
        }
    }

    public function validateBox(int $id): array
    {
        try {
            $box = Box::with('prestation')->findOrFail($id);

            $box->validate();
            $box->save();

            return $box->toArray();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de la validation de la box");
        }
    }

    public function deliverBox(int $id, int $createur_id): array
    {
        try {
            $box = Box::findOrFail($id);

            if ($box->createur_id !== $createur_id) {
                throw new UnauthorizedException("Accès interdit");
            }

            $token = bin2hex(random_bytes(16));
            $box->deliver($token);
            $box->save();

            $res = $box->toArray();
            $res['url'] = "/box/$token";

            return $res;
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de la livraison de la box");
        }
    }

    public function boxUsed(string $token): array
    {
        try {
            $box = Box::where('token', $token)->firstOrFail();

            $box->markAsUsed();
            $box->save();

            return $box->load('prestation')->toArray();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Token invalide");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de l'utilisation de la box");
        }
    }
}
