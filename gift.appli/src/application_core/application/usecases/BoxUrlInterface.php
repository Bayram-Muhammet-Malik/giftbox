<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

interface BoxUrlInterface {
    public function deliverBox(string $id): array;
    
    public function boxUsed(string $token): array;
}
