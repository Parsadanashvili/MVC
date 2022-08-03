<?php

namespace Core\Session;

use Core\Session\Storage\SessionStorage;

class SessionManager
{
    public static function initialize()
    {
        $factory = new SessionFactory();
        return $factory->create(self::getSessionName(), SessionStorage::class, [
            'session_name' => self::getSessionName(),
            'lifetime' => $_ENV['SESSION_LIFETIME'],
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'use_cookies' => true,
            'cookie_lifetime' => $_ENV['SESSION_LIFETIME'],
            'cookie_path' => '/',
            'cookie_secure' => false,
            'cookie_httponly' => false,
            'gc_maxlifetime' => $_ENV['SESSION_LIFETIME'],
            'gc_divisor' => 1,
            'gc_probability' => 1,
        ]);
    }

    protected static function getSessionName() {
        return str_replace(' ', '_',strtolower($_ENV['APP_NAME']));
    }
}
