<?php
declare(strict_types=1);

namespace gift\webui\providers;
use gift\core\application\usecases\AuthnService;

class AuthnProvider implements AuthnProviderInterface {
    private AuthnService $authnService;

    public function __construct(AuthnService $authnService) {
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
