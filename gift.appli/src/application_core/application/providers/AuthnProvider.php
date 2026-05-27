<?php
declare(strict_types=1);

namespace gift\core\application\providers;
use gift\core\application\usecases\AuthnInterface;

class AuthnProvider implements AuthnProviderInterface {
    private AuthnInterface $authnService;

    public function __construct(AuthnInterface $authnService) {
        $this->authnService = $authnService;
    }

    public function signin(string $user_id, string $password): array {
        $user = $this->authnService->signin($user_id, $password);

        $_SESSION['user'] = $user;

        return $user;
    }

    public function getSignedInUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }
}
