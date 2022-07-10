<?php

namespace Core\Session\Storage;

abstract class AbstractSessionStorage implements SessionStorageInterface 
{
    protected array $options = [];

    public function __construct(array $options = [])
    {
        $this->options = $options;

        $this->iniSet();
        if($this->isSessionStarted()) {
            session_unset();
            session_destroy();
        }
        $this->start();
    }

    public function setSessionName(string $name): void
    {
        session_name($name);
    }
    
    public function getSessionName(): string
    {
        return session_name();
    }

    public function setSessionId(string $id): void
    {
        session_id($id);
    }

    public function getSessionId(): string
    {
        return session_id();
    }

    public function iniSet()
    {
        ini_set('session.gc_maxlifetime', $this->options['gc_maxlifetime']);
        ini_set('session.gc_divisor', $this->options['gc_divisor']);
        ini_set('session.gc_probability', $this->options['gc_probability']);
        ini_set('session.cookie_lifetime', $this->options['cookie_lifetime']);
        ini_set('session.use_cookies', $this->options['use_cookies']);
    }

    public function isSessionStarted(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    public function startSession(): void
    {
        if(!$this->isSessionStarted()) {
            session_start();
        }
    }

    public function start()
    {
        $this->setSessionName($this->options['session_name']);

        if(isset($this->options['domain'])) {
            $domain = $this->options['domain'];
        } else if(isset($_SERVER['HTTP_HOST'])) {
            $domain = $_SERVER['HTTP_HOST'];
        } else if(isset($this->options['secure'])) {
            $domain = $this->options['secure'];
        } else {
            $domain = isset($_SERVER['HTTP']);
        }

        session_set_cookie_params(
            $this->options['cookie_lifetime'],
            $this->options['cookie_path'],
            $domain,
            $this->options['cookie_secure'],
            $this->options['cookie_httponly']
        );

        $this->startSession();
    }
}