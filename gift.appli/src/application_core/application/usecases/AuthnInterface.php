<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

interface AuthnInterface {
    public function register(string $user_id, string $password): array;

    public function signin(string $user_id, string $password): array;
}
