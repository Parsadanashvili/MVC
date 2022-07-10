<?php

namespace Core\Session;

interface SessionInterface
{
    public static function set(string $key, $value): void;

    public static function get(string $key, $default = null): mixed;

    public static function all(): array;

    public static function has(string $key): bool;

    public static function remove(string $key): bool;

    public static function clear(): void;
}