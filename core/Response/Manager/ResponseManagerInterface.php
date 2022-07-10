<?php

namespace Core\Response\Manager;

interface ResponseManagerInterface
{
    public function redirect(string $url, int $code = 302, array $errors = []);
    public function json(array $data, int $code = 200);
    public function abort(string $message, int $code = 404);
}