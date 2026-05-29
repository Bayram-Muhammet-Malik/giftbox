<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\exceptions\DataErrorException;
use gift\core\application\exceptions\NotFoundException;
use gift\core\domain\entities\User;
use Exception;

class AuthnService implements AuthnInterface
{
    public function register(string $user_id, string $password): array
    {
        try {
            $existingUser = User::where('user_id', $user_id)->first();

            if ($existingUser) throw new DataErrorException("Identifiant déjà utilisé");

            $user = new User();
            $user->id = bin2hex(random_bytes(16));
            $user->user_id = $user_id;
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();

            return $user_id;
        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de l'inscription");
        }
    }

    public function signin(string $user_id, string $password): array
    {
        try {
            $user = User::where('user_id', $user_id)->first();

            if (!$user) throw new NotFoundException("Utilisateur non trouvé");

            if (!password_verify($password, $user->password)) throw new DataErrorException("Mot de passe incorrect");

            return $user->toArray();

        } catch (Exception $e) {
            throw new DataErrorException("Erreur lors de l'authentification");
        }
    }
}
