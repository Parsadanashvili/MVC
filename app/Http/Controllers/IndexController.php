<?php

namespace App\Http\Controllers;

use Core\View;

class IndexController
{
    public function index()
    {
        return new View('index');
    }
}