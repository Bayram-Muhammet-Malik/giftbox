<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

interface BoxManaInterface {

    public function createBox(string $libelle, string $description, bool $kdo, ?string $message_kdo, string $createur_id): array;

    public function addPrestations(int $id, int $presta_id, int $quantity): array;

    public function getBox(int $id): array;

    public function validateBox(int $id): array;
}
