<?php
declare(strict_types=1);

namespace gift\core\application\usecases;

use gift\core\application\exceptions\AuthnException;
use gift\core\application\exceptions\NotFoundException;
use gift\core\domain\entities\User;
use Exception;

class AuthnService implements AuthnInterface
{
    public function register(string $user_id, string $password): array
    {
        $existingUser = User::where('user_id', $user_id)->first();

        if ($existingUser) throw new AuthnException("Identifiant déjà utilisé");

        if (strlen($password) < 8) {
            throw new AuthnException("Le mot de passe doit faire au moins 8 charactères");
        }

        if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
            throw new AuthnException("Le mot de passe doit contenir une majuscule et une minuscule");
        }

        $user = new User();
        $user->id = bin2hex(random_bytes(16));
        $user->user_id = $user_id;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role = 1;
        $user->save();

        return $user->toArray();
    }

    public function signin(string $user_id, string $password): array
    {
        $user = User::where('user_id', $user_id)->first();

        if (!$user) throw new NotFoundException("Utilisateur non trouvé");
        if (!password_verify($password, $user->password)) throw new AuthnException("Mot de passe incorrect");

        return $user->toArray();
    }
}