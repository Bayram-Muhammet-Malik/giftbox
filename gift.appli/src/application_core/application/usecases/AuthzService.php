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
            try {
                  $box_data = Box::where('$id', $box_id);
            } catch (QueryException $e) {
                  throw new DataErrorException('pas de box avec cette id');
            }

            switch ($operation) {
                  case self::CREATE_BOX:
                        return $user['role'] >= 1;
                  case self::VIEW_BOX:
                        return self::nonUserVerification($user['user_id'], $user['role'], $box_data['createur_id']);
                  case self::VALIDATE_BOX:
                        return self::nonUserVerification($user['user_id'], $user['role'], $box_data['createur_id']);
                  case self::ADD_PRESTATION:
                        return self::nonUserVerification($user['user_id'], $user['role'], $box_data['createur_id']);
                  case self::GENERATE_URL:
                        return self::nonUserVerification($user['user_id'], $user['role'], $box_data['createur_id']);
            }
            return false;
      }

      protected function nonUserVerification(string $user_id, string $role, string $createur_id): bool
      {
            return $role >= 1 && $user_id === $createur_id;
      }
}
