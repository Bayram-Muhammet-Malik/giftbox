<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

interface BoxManaInterface {
    public function deliverBox(int $id): array;
    
    public function boxUsed(string $token): array;
}
