<?php

use Core\Request\Request;

if(!function_exists('old')) {
    function old($name, $default = null)
    {
        return (new Request)->old($name, $default);
    }
}