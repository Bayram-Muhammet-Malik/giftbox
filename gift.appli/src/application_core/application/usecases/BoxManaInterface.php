<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

interface BoxManaInterface {

    public function createBox(string $libelle, string $description, bool $kdo, ?string $message_kdo, int $createur_id): array;

    public function addPrestations(int $id, int $presta_id, int $quantity, int $createur_id): array;

    public function getBox(int $id, int $createur_id): array;

    public function validateBox(int $id, int $createur_id): array;

    public function deliverBox(int $id, int $createur_id): array;

    public function boxUsed(string $token): array;
}
