<?php

namespace Core\Error;

class Error
    extends AbstractError
    implements ErrorInterface
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
        parent::__construct();
    }

    /**
     * Get error list
     * 
     * @return array
     */
    public function all(): array
    {
        return $this->errors;
    }

    /**
     * Check if field has errors
     * 
     * @param string $field
     * @return bool
     */
    public function has(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /**
     * Get error list for field
     * 
     * @param string $field
     * @return array
     */
    public function get(string $field): array
    {
        if($this->has($field)) {
            if(is_array($this->errors[$field])) {
                return $this->errors[$field];
            } else {
                return [$this->errors[$field]];
            }
        }
        return [];
    }

    /**
     * Get first error for field
     * 
     * @param string $field
     * @return string
     */
    public function first(string $field): string
    {
        if($this->has($field)){
            if(is_array($this->errors[$field])){
                return $this->errors[$field][0] ?? '';
            } else {
                return $this->errors[$field] ?? '';
            }
        }
        return '';
    }

    /**
     * Get last error for field
     * 
     * @param string $field
     * @return string
     */
    public function last(string $field): string
    {
        if($this->has($field)){
            if(is_array($this->errors[$field])){
                return end($this->errors[$field]);
            } else {
                return $this->errors[$field] ?? '';
            }
        }
        return '';
    }
}