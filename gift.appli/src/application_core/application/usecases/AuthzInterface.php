<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

interface AuthzInterface
{
    public const CREATE_BOX = 'create_box';
    public const VIEW_BOX = 'view_box';
    public const VALIDATE_BOX = 'validate_box';
    public const ADD_PRESTATION = 'add_prestation';
    public const GENERATE_URL = 'generate_url';
    public function isGranted(string $user_id, string $operation, string $box_id): bool;
}
