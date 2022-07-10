<?php

namespace Core\Request;

use Core\Request\ParameterBag;
use Core\Session\Session;

class Request
    extends AbstractRequest
    implements RequestInterface
{   
    public ParameterBag $query;

    public ParameterBag $request;

    public ParameterBag $server;

    public ParameterBag $headers;

    protected string $path = '/';

    protected string $method;

    public function __construct()
    {
        parent::__construct();
    }

    public function all(): array
    {
        return array_merge($this->query->all(), $this->request->all());
    }

    public function get(string $key, $default = null): mixed
    {
        if($this->query->has($key)) {
            return $this->query->get($key);
        }
        
        if($this->request->has($key)) {
            return $this->request->get($key);
        }

        return $default;
    }

    public function server(string $key, $default = null): mixed
    {
        if($this->server->has($key)) {
            return $this->server->get($key);
        }
        
        return $default;
    }
    
    public function header(string $key, $default = null): mixed
    {
        if($this->headers->has($key)) {
            return $this->headers->get($key);
        }
        
        return $default;
    }

    public function hasHeader(string $key): bool
    {
        return $this->headers->has($key);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function isMethod(string $method): bool
    {
        return $this->method === $method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function ip(): string
    {
        return $this->server('REMOTE_ADDR');
    }
}