<?php

namespace Core\Response;

interface ResponseInterface
{
    public static function redirect(string $url, int $code = 302, array $errors = []);
    public static function json(array $data, int $code = 200);
    public static function abort(string $message, int $code = 404);
}