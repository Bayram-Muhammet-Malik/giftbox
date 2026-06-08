<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use gift\core\domain\entities\Box;
use gift\core\domain\entities\Prestation;
use Exception;

class BoxManaService implements BoxManaInterface
{
    public function createBox(string $libelle, string $description, bool $kdo, ?string $message_kdo, string $createur_id): array
    {
        try {
            if ($kdo && empty($message_kdo)) {
                throw new DataErrorException("Message cadeau obligatoire pour une box cadeau");
            }

            $box = new Box();
            $box->id = bin2hex(random_bytes(16));
            $box->token = "";
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
            throw new DataErrorException("Erreur lors de la création de la box " . $e->getMessage());
        }
    }

    public function addPrestations(string $id, int $presta_id, int $quantite): array
    {
        try {
            $box = Box::with('prestation')->findOrFail($id);

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

    public function getBox(string $id): array
    {
        try {
            $box = Box::with('prestation')->findOrFail($id);

            return $box->toArray();
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de la récupération de la box");
        }
    }

    public function validateBox(string $id): array
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
}
