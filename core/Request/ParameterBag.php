<?php

namespace Core\Request;

class ParameterBag
{
    protected $parameters;
    
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }
    
    public function get(string $key, $default = null)
    {
        if(isset($this->parameters[$key])) {
            return $this->parameters[$key];
        }
        
        return $default;
    }

    public function set(string $key, $value)
    {
        $this->parameters[$key] = $value;
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    public function remove(string $key)
    {
        unset($this->parameters[$key]);
    }

    public function clear()
    {
        $this->parameters = [];
    }
}