<?php

namespace Core\Concerns;

use Core\Database\Manager\DatabaseManagerInterface;
use Core\Database\Manager\DatabaseManager;

abstract class Model
{
    protected static DatabaseManagerInterface $manager;

    protected static $_instance = null;

    public function __construct()
    {
        static::$manager = new DatabaseManager(static::class);

        static::$manager->table($this->getTableName());
    }

    public static function get()
    {
        try {
            if (static::$_instance === null) {
                static::$_instance = new static;
            }

            return static::$manager->get();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function first()
    {
        try {
            if (static::$_instance === null) {
                static::$_instance = new static;
            }

            return static::$manager->first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function where(string $column, $operator = '=', $value = '')
    {
        if($value === '') {
            $value = $operator;
            $operator = '=';
        }

        try {
            if (static::$_instance === null) {
                static::$_instance = new static;
            }

            static::$manager->where($column, $operator, $value);
            return static::$_instance;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function create(array $data)
    {
        try {
            if (static::$_instance === null) {
                static::$_instance = new static;
            }

            return static::$manager->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function update(array $data)
    {
        try {
            if (static::$_instance === null) {
                static::$_instance = new static;
            }

            if($this->__isset('id')) {
                static::$manager->where('id', '=', $this->id);
            }

            static::$manager->update($data);
            return static::first();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function __get($key)
    {
        return $this->$key ?? null;
    }

    public function __set($key, $value)
    {
        $this->$key = $value;
    }

    public function __isset($key)
    {
        return isset($this->$key);
    }

    public function __unset($key)
    {
        unset($this->$key);
    }

    protected function pluralize($quantity, $singular, $plural=null) {
        if($quantity==1 || !strlen($singular)) return $singular;
        if($plural!==null) return $plural;
    
        $last_letter = strtolower($singular[strlen($singular)-1]);
        switch($last_letter) {
            case 'y':
                return substr($singular,0,-1).'ies';
            case 's':
                return $singular.'es';
            default:
                return $singular.'s';
        }
    }

    protected function getTableName(): string
    {
        $className = explode('\\', static::class);
        $className = end($className);
        $pieces = preg_split('/(?=[A-Z])/', $className);

        $pieces = array_map(
            function($piece) {
                return strtolower($piece);
            },
            $pieces
        );

        $pieces = array_values(
            array_filter(
                $pieces,
                fn($value) => !is_null($value) && $value !== ''
            )
        );

        $pieces[array_search(end($pieces), $pieces)] = self::pluralize(2, end($pieces));

        $tablename = implode('_', $pieces);

        return $tablename;
    }
}