<?php

namespace Core\Request;

use Core\Request\ParameterBag;
use Core\Session\Session;

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

    public static function storeOldInputs(array $inputs): void
    {
        Session::set('_old_inputs', $inputs);
        Session::set('_remove_old_inputs', 0);
    }

    public static function clearOldInputs()
    {
        if(Session::has('_remove_old_inputs') && Session::get('_remove_old_inputs') == 1) {
            Session::remove('_old_inputs');
            Session::remove('_remove_old_inputs');
        }

        Session::set('_remove_old_inputs', 1);
    }
}