<?php

namespace Core\Response\Manager;

use Core\Error\Error;
use Core\Response\Manager\ResponseManagerInterface;
use Core\View;

class ResponseManager
    implements ResponseManagerInterface
    {
        public function redirect(string $url, int $code = 302, array $errors = [])
        {
            Error::store($errors);
            header('Location: ' . $url, true, $code);
            exit;
        }
        
        public function json(array $data, int $statusCode = 404)
        {
            ob_get_clean();

            header('Content-Type: application/json');
            http_response_code($statusCode);

            echo json_encode($data);

            return;
        }
        
        public function abort(string $message, int $statusCode = 404)
        {
            ob_get_clean();

            header('Content-Type: text/html');
            http_response_code($statusCode);

            if(View::exists('errors.'.$statusCode)){
                return new View('errors.'.$statusCode, ['message' => $message]);
            } else if(View::exists('errors.404')) {
                return new View('errors.404', ['message' => $message]);
            }
            
            echo $message;

            return;
        }
    }