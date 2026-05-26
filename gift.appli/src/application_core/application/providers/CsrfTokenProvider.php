<?php
declare(strict_types=1);
namespace gift\core\application\providers;
use Exception;
class CsrfTokenProvider{

    public static function generate() : string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    $token = bin2hex(random_bytes(16));
            $_SESSION['csrf_token'] = $token;

            return $token;
    }

    public static function check($token): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (
            !isset($_SESSION['csrf_token']) ||
            !is_string($token) ||
            !hash_equals($_SESSION['csrf_token'], $token)
        ) {
            unset($_SESSION['csrf_token']);
            throw new Exception('Token CSRF invalide');
        }

        unset($_SESSION['csrf_token']);
    }
    }
}
