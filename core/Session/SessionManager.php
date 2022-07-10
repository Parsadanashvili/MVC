<?php

namespace Core\Session;

use Core\Session\Storage\SessionStorage;

class SessionManager
{
    public static function initialize()
    {
        $factory = new SessionFactory();
        return $factory->create('mvccore', SessionStorage::class, [
            'session_name' => 'mvccore',
            'lifetime' => 3600,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => false,
            'use_cookies' => true,
            'cookie_lifetime' => 3600,
            'cookie_path' => '/',
            'cookie_secure' => false,
            'cookie_httponly' => false,
            'gc_maxlifetime' => 3600,
            'gc_divisor' => 1,
            'gc_probability' => 1,
        ]);
    }
}
