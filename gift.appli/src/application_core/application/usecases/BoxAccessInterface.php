<?php

declare(strict_types=1);
namespace gift\core\application\usecases;

interface BoxAccessServiceInterface {
    public function generateToken(int $boxId, int $userId): string;
    public function getBoxByToken(string $token): array;
}
