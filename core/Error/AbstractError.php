<?php

namespace Core\Error;

use Core\Session\Session;

abstract class AbstractError
{
    /**
     * List of errors
     * 
     * @var array
     */
    protected array $errors = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->clear();
        $this->getErrors();
    }

    /**
     * Save error list to session
     * 
     * @param array $errors
     * @return void
     */
    public static function store(array $errors): void
    {
        Session::set('_errors', $errors);
        Session::set('_remove_errors', 0);
    }

    /**
     * Clear error list in session
     */
    public function clear()
    {
        if(Session::has('_remove_errors') && Session::get('_remove_errors') == 1) {
            Session::set('_errors', []);
            Session::remove('_remove_errors');
        } else {
            Session::set('_remove_errors', 1);
        }
    }

    /**
     * Get error list from session
     * 
     * @return array
     */
    protected function getErrors()
    {
        $this->errors = Session::get('_errors', []);
    }
}