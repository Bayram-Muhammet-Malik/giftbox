<?php
declare(strict_types=1);

namespace gift\webui\providers;
use gift\core\application\usecases\AuthnService;
use gift\core\application\exceptions\NotLoggedException;

class AuthnProvider implements AuthnProviderInterface {
    public static function signin(string $user_id, string $password): array {
        $user = (new AuthnService)->signin($user_id, $password);

        $_SESSION['user'] = $user;

        return $user;
    }

    public static function getSignedInUser(): ?array
    {
        if (!isset($_SESSION['user'])) throw new NotLoggedException("Non authentifié");
        return $_SESSION['user'] ?? null;
    }
}
