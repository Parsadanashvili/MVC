<?php

namespace Core\Database\Manager;

interface DatabaseManagerInterface
{
    public function __construct($FETCH_CLASS);
    
    public function table(string $table): void;
    
    public function get(): array;
    
    public function first(): object|null;

    public function where(string $column, $operator = '=', $value = ''): void;
    
    public function create(array $data);
    
    public function update(array $data);
    
    public function delete();
}