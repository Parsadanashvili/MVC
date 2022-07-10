<?php

namespace Core\Database;

use Core\Database\Manager\DatabaseManager;
use Core\Database\Manager\DatabaseManagerInterface;

class Database
    implements DatabaseInterface
{
    private static DatabaseManagerInterface $manager;

    private static $_instance = null;

    public function __construct($FETCH_CLASS = null)
    {
        self::$manager = new DatabaseManager($FETCH_CLASS ?? static::class);
    }

    public static function table(string $table)
    {
        try {
            if (self::$_instance === null) {
                self::$_instance = new self;
            }

            self::$manager->table($table);
            return self::$_instance;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function get()
    {
        try {
            return self::$manager->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function where(string $column, $operator = '=', $value = '')
    {
        if($value === '') {
            $value = $operator;
            $operator = '=';
        }

        try {
            self::$manager->where($column, $operator, $value);
            return $this;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function create(array $data)
    {
        try {
            self::$manager->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            self::$manager->update($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function delete()
    {
        try {
            self::$manager->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}