<?php

namespace Core\Request;

use Core\Request\ParameterBag;

abstract class AbstractRequest
{
    public ParameterBag $query;

    public ParameterBag $request;

    public ParameterBag $server;

    public ParameterBag $headers;

    protected string $path = '/';

    protected string $method;

    public function __construct()
    {
        $this->getPath();
        $this->getQuery();
        $this->getRequest();
        $this->getServer();
        $this->getHeaders();
        $this->getMethod();

        return $this;
    }

    protected function getPath(): void
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $positon = strpos($path, '?');

        if ($positon === false) {
            $this->path = $path;
        }
    }

    protected function getMethod(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    protected function getQuery(): void
    {
        $this->query = new ParameterBag($_GET ?? []);
    }

    protected function getRequest(): void
    {
        $this->request = new ParameterBag($_REQUEST ?? []);
    }

    protected function getServer(): void
    {
        $this->server = new ParameterBag($_SERVER ?? []);
    }

    protected function getHeaders(): void
    {
        $this->headers = new ParameterBag(getallheaders() ?? []);
    }
}