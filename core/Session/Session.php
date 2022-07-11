<?php

namespace Core\Session;

use Core\Session\Exception\SessionException;
use Core\Session\Storage\SessionStorageInterface;
use Throwable;

class Session implements SessionInterface
{
    public static SessionStorageInterface $storage;

    public string $name;

    protected const SESSION_PATTERN = '/^[a-zA-Z0-9_\.]{1,64}+$/';
    
    public function __construct(string $sessionName, SessionStorageInterface $storage)
    {
        if(self::isSessionKeyValid($sessionName) === false) {
            throw new SessionException('Session name is invalid');
        }

        $this->setSessionName($sessionName);
        $this->setStorage($storage);
    }

    public static function set(string $key, $value): void
    {
        self::ensureSessionKeyIsValid($key);

        try {
            self::$storage->setSession($key, $value);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown while trying to set session.');
        }
    }

    public static function get(string $key, $default = null): mixed
    {
        self::ensureSessionKeyIsValid($key);
        
        try {
            return self::$storage->getSession($key, $default);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown while trying to get session.');
        }
    }

    public static function all(): array
    {
        try {
            return self::$storage->getAllSession();
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown while trying to get all session.');
        }
    }

    public static function has(string $key): bool
    {
        self::ensureSessionKeyIsValid($key);

        return (bool) self::$storage->hasSession($key);
    }

    public static function remove(string $key): bool
    {
        self::ensureSessionKeyIsValid($key);
        
        try {
            return self::$storage->removeSession($key);
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown while trying to remove session.');
        }
    }

    public static function clear(): void
    {
        try {
            self::$storage->clearSession();
        } catch (Throwable $throwable) {
            throw new SessionException('An exception was thrown while trying to clear session.');
        }
    }

    protected static function isSessionKeyValid(string $key): bool
    {
        return (bool) preg_match(self::SESSION_PATTERN, $key);
    }

    protected static function ensureSessionKeyIsValid(string $key): void
    {
        if (!self::isSessionKeyValid($key)) {
            throw new SessionException('Session key is invalid.');
        }
    }

    protected function checkIfStorageIsAlreadyExists(): bool
    {
        return (bool) isset($GLOBALS['session']);
    }

    protected function setStorage(SessionStorageInterface $storage): void
    {
        self::$storage = $storage;
    }

    protected function setSessionName(string $sessionName): void
    {
        $this->name = $sessionName;
    }
}