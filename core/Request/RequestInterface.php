<?php

namespace Core\Request;

interface RequestInterface
{
    public function all(): array;
    public function get(string $key, mixed $default = null): mixed;
    public function server(string $key, mixed $default = null): mixed;
    public function header(string $key, mixed $default = null): mixed;
    public function hasHeader(string $key): bool;
    public function method(): string;
    public function isMethod(string $method): bool;
    public function path(): string;
    public function ip(): string;
}