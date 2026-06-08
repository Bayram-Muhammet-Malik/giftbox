<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use gift\core\domain\entities\Box;
use Exception;

class BoxManaService implements BoxManaInterface
{
    public function deliverBox(string $id): array {
        try {
            $box = Box::findOrFail($id);

            $token = bin2hex(random_bytes(16));
            $box->deliver($token);
            $box->save();

            $res = $box->toArray();

            return $res;
        } catch (ModelNotFoundException $e) {
            throw new NotFoundException("Box non trouvée dans la base de donnée.");
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de la livraison de la box");
        }
    }

    public function boxUsed(string $token): array {
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
