<?php
declare(strict_types=1);
namespace gift\webui\providers;

interface AuthnProviderInterface {
    public function signin(string $user_id, string $password): array;

    public function getSignedInUser(): ?array;
}
