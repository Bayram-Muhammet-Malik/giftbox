<?php
declare(strict_types=1);
namespace gift\core\application\providers;

interface AuthnProviderInterface {
    public function signin(string $user_id, string $password): array;

    public function getSignedInUser(): ?array;
}
