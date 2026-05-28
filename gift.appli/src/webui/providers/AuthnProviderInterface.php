<?php
declare(strict_types=1);
namespace gift\webui\providers;

interface AuthnProviderInterface {
    public static function signin(string $user_id, string $password): array;

    public static function getSignedInUser(): ?array;
}
