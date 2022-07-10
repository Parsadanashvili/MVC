<?php

namespace Core\Response;

use Core\Response\Manager\ResponseManagerInterface;
use Core\Response\Manager\ResponseManager;

abstract class AbstractResponse
{
    protected static $function;

    protected static $args;

    protected static mixed $chains = false;

    protected static ResponseManagerInterface $manager;

    public function __construct()
    {
        static::$manager = new ResponseManager();

        if(static::$function && static::$args) {
            $function = static::$function;
            $args = static::$args;
            static::$function = false;
            static::$args = false;
            return static::$manager->$function(...$args);
        }
    }
}