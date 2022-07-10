<?php

namespace Core\Error;

interface ErrorInterface
{
    /**
     * Get error list
     * 
     * @return array
     */
    public function all(): array;

    /**
     * Check if field has errors
     * 
     * @param string $field
     * @return bool
     */
    public function has(string $field): bool;

    /**
     * Get error list for field
     * 
     * @param string $field
     * @return array
     */
    public function get(string $field): array;

    /**
     * Get first error for field
     * 
     * @param string $field
     * @return string
     */
    public function first(string $field): string;

    /**
     * Get last error for field
     * 
     * @param string $field
     * @return string
     */
    public function last(string $field): string;
}