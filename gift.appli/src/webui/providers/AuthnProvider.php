<?php
declare(strict_types=1);

namespace gift\webui\providers;
use gift\core\application\usecases\AuthnService;
use gift\core\application\exceptions\NotLoggedException;

class AuthnProvider {
    public static function signin(string $user_id, string $password): void {
        $user = (new AuthnService)->signin($user_id, $password);

        $_SESSION['user'] = $user['id'];
    }

    public static function getSignedInUser(): ?string
    {
        if (!isset($_SESSION['user'])) throw new NotLoggedException("Non authentifié");
        return $_SESSION['user'];
    }
}
