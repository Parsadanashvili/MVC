<?php

namespace Core\Session;

use Core\Globals;
use Core\Request\Request;
use Core\Session\Storage\SessionStorageInterface;
use Core\Session\SessionInterface;

class SessionFactory
{
    public function __construct()
    {

    }

    public function create(string $name, string $storageString, array $options = []): SessionInterface
    {
        $storageObject = new $storageString(new Request(), $options);
        if(!$storageObject instanceof SessionStorageInterface) {
            throw new \InvalidArgumentException($storageString . ' is not a valid session storage object.');
        }

        return new Session($name, $storageObject);
    }
}