<?php

namespace Core\Database;

interface DatabaseInterface
{
    public function __construct();

    public static function table(string $table);

    public function get();

    public function where(string $column, $operator = '=', $value = '');

    public function create(array $data);

    public function update(array $data);

    public function delete();
}