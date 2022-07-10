<?php

namespace Core;

use Exception;
use Throwable;

class Globals
{
    public static function set(string $key, $value): void
    {
        $GLOBALS[$key] = $value;
    }

    public static function get(string $key, $default = null): mixed
    {
        try {
            return $GLOBALS[$key] ?? $default;
        } catch (Throwable $throwable) {
            throw new Exception('An exception was thrown while trying to get session.');
        }
    }

    public static function has(string $key): bool
    {
        return (bool) isset($GLOBALS[$key]);
    }
}