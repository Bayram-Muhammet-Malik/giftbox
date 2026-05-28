<?php
declare(strict_types=1);

namespace gift\webui\providers;
use gift\core\application\usecases\AuthnService;

class AuthnProvider implements AuthnProviderInterface {
    public static function signin(string $user_id, string $password): array {
        $user = (new AuthnService)->signin($user_id, $password);

        $_SESSION['user'] = $user;

        return $user;
    }

    public static function getSignedInUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}
