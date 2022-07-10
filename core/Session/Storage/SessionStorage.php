<?php

namespace Core\Session\Storage;

use Core\Request\Request;

class SessionStorage
    extends AbstractSessionStorage
    implements SessionStorageInterface
{
    public function __construct(Request $request, array $options = [])
    {
        parent::__construct($options);

        $this->setPreviousUrl($request->server('HTTP_REFERER', $request->server('REQUEST_URI')));
    }

    public function setSession(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function getSession(string $key, $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function getAllSession(): array
    {
        return $_SESSION;
    }

    public function hasSession(string $key): bool
    {
        return (bool) isset($_SESSION[$key]);
    }

    public function removeSession(string $key): bool
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }

        return false;
    } 

    public function clearSession(): void
    {
        $_SESSION = [];
    }

    public function setPreviousUrl(string $url): void
    {
        $this->setSession('_previous_url', $url);
    }
}