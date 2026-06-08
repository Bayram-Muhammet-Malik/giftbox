<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

use Illuminate\Database\QueryException;
use gift\core\application\exceptions\DataErrorException;
use gift\core\domain\entities\User;
use gift\core\domain\entities\Box;

class AuthzService implements AuthzInterface
{
      public function isGranted(string $user_id, string $operation, ?string $box_id = null): bool
      {
            try {
                  $user = User::where('id', $user_id)->first();
                  switch ($operation) {
                        case self::CREATE_BOX:
                              return $user['role'] >= 1;
                        case self::VIEW_BOX:
                        case self::VALIDATE_BOX:
                        case self::ADD_PRESTATION:
                        case self::GENERATE_URL:
                              $box = Box::findOrFail($box_id);
                              return $user['role'] >= 1 && $user['id'] === $box['createur_id'];
                  }

            } catch (QueryException $e) {
                  throw new DataErrorException('pas de box avec cette id');
            }
            return false;
      }
}
