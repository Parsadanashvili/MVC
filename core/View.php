<?php

namespace Core;

use Core\Response\Response;

class View
{
    protected ViewEngine $engine;

    public function __construct(string $template, $data = [])
    {
        if(!$this->exists($template)) {
            return Response::abort('Template not found');
        }

        $this->engine = new ViewEngine();

        $this->engine->process($template, $data);
    }

    public static function templatePath($template)
    {
        if(!self::exists($template)) return false;

        $template = str_replace('.', '/', $template);

        return Application::$ROOT_DIR . '/templates/' . $template . '.blade.php';        
    }

    public static function exists($template)
    {
        $template = str_replace('.', '/', $template);
        return file_exists(Application::$ROOT_DIR . '/templates/' . $template . '.blade.php');
    }
}