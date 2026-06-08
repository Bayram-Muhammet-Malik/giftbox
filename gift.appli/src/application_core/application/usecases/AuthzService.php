<?php
declare(strict_types=1);
namespace gift\core\application\usecases;

use Illuminate\Database\QueryException;
use gift\core\application\exceptions\DataErrorException;
use gift\core\domain\entities\Box;

class AuthzService implements AuthzInterface
{
      public function isGranted(array $user, string $operation, ?string $box_id = null): bool
      {
            switch ($operation) {
                  case self::CREATE_BOX:
                        return $user['role'] >= 1;
                  case self::VIEW_BOX:
                  case self::VALIDATE_BOX:
                  case self::ADD_PRESTATION:
                  case self::GENERATE_URL:
                        try {
                              $box_data = Box::where('$id', $box_id);
                        } catch (QueryException $e) {
                              throw new DataErrorException('pas de box avec cette id');
                        }
                        return $user['role'] >= 1 && $user['user_id'] === $box_data['createur_id'];
            }
            return false;
      }
}
