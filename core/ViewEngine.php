<?php

namespace Core;

use Core\Error\Error;
use eftec\bladeone\BladeOne;

class ViewEngine {
    protected BladeOne $blade;

    public function __construct()
    {
        $templates = Application::$ROOT_DIR . '/templates';
        $cache = Application::$ROOT_DIR . '/cache/templates';

        $this->blade = new BladeOne($templates, $cache, BladeOne::MODE_DEBUG);

        if(Auth::check()) {
            $this->blade->setAuth(Auth::user()->id);
        }
    }

    public function process(string $template, array $data = [])
    {
        echo $this->blade->setView($template)
                         ->share(['errors'=>new Error()])
                         ->share($data)
                         ->run();
    }

    public function getErrors()
    {
    }
}