<?php
declare(strict_types=1);

namespace gift\core\application\providers;

use \gift\core\application\exceptions\CsrfException;

class CsrfTokenProvider{
    public static function generate() : string {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $token = bin2hex(random_bytes(16));
        $_SESSION['csrf_token'] = $token;

        return $token;
    }

    public static function check($token): void {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();

        if (!isset($_SESSION['csrf_token']) || !is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
            unset($_SESSION['csrf_token']);
            throw new CsrfException('Token CSRF invalide');
        }

        unset($_SESSION['csrf_token']);
    }
}
