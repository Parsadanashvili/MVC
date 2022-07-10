<?php

namespace Core\Response;

use Exception;

class Response
    extends AbstractResponse
    implements ResponseInterface
{
    protected static $function;

    protected static $args;

    public static function redirect(string $url, int $code = 302, array $errors = [])
    {
        try {
            static::$function = 'redirect';
            static::$args = [$url, $code, $errors];
            return new static();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function json(array $data, int $code = 200)
    {
        try {
            static::$function = 'json';
            static::$args = [$data, $code];
            return new static();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public static function abort(string $message, int $code = 404)
    {
        try {
            static::$function = 'abort';
            static::$args = [$message, $code];
            return new static();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}