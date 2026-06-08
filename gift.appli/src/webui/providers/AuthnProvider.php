<?php
declare(strict_types=1);

namespace gift\webui\providers;
use gift\core\application\usecases\AuthnService;
use gift\core\application\exceptions\AuthnException;

class AuthnProvider {
    public static function signin(string $user_id, string $password): array {
        $user = (new AuthnService)->signin($user_id, $password);

        $_SESSION['user'] = $user['id'];

        return $user;
    }

    public static function register(string $user_id, string $password): void {
        $user = (new AuthnService)->register($user_id, $password);

        $_SESSION['user'] = $user['id'];
    }

    public static function getSignedInUser(): ?array
    {
        if (!isset($_SESSION['user'])) throw new AuthnException("Non authentifié");
        return $_SESSION['user'];
    }
}
