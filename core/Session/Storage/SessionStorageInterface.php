<?php

namespace Core\Session\Storage;

interface SessionStorageInterface
{
    public function setSession(string $key, $value): void;

    public function getSession(string $key, $default = null): mixed;

    public function getAllSession(): array;

    public function hasSession(string $key): bool;

    public function removeSession(string $key): bool;

    public function clearSession(): void;
}