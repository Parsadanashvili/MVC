<?php

namespace Core\Database\Manager;

use PDO;

abstract class AbstractDatabaseManager
{
    public PDO $pdo;

    public $statement;

    public string $query;
    
    public string $table;
    
    public function __construct()
    {
        $this->pdo = new PDO(
            sprintf(
                'mysql:host=%s;dbname=%s',
                $_ENV['DATABASE_HOST'],
                $_ENV['DATABASE_NAME']
            ),
            $_ENV['DATABASE_USERNAME'],
            $_ENV['DATABASE_PASSWORD']
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function prepareSelectStatememt()
    {
        $this->query = 'SELECT * FROM ' . $this->table;

        $this->setWheresInSQL();

        $this->statement = $this->pdo->prepare($this->query);

        $this->bindWhereValues();
    }

    protected function setWheresInSQL()
    {
        if(isset($this->wheres)) {
            $wheres = array_map(function ($where) {
                return $where . ' ' . $this->wheres[$where]['operator'] . ' :' . $where;
            }, array_keys($this->wheres));

            $this->query .= ' WHERE ' . implode(' AND ', $wheres);
        }
    }

    protected function bindWhereValues()
    {
        if(isset($this->wheres)) {
            foreach(array_keys($this->wheres) as $where) {
                $this->statement->bindValue(':' . $where, $this->wheres[$where]['value']);
            }
        }
    }

    protected function prepareInsertStatement(array $data)
    {
        $this->query = 'INSERT INTO ' . $this->table;

        $this->setColumnsInSQL($data);

        $this->setValuesInSQL($data);

        $this->statement = $this->pdo->prepare($this->query);

        $this->bindValues($data);
    }

    protected function setColumnsInSQL(array $data)
    {
        $columns = array_keys($data);

        $this->query .= ' (' . implode(', ', $columns) . ')';
    }

    protected function setValuesInSQL(array $data)
    {
        $values = array_map(function ($column) {
            return ':'.$column;
        }, array_keys($data));

        $this->query .= ' VALUES (' . implode(', ', $values) . ')';
    }

    protected function bindValues(array $data)
    {
        foreach(array_keys($data) as $column) {
            $this->statement->bindValue(':' . $column, $data[$column]);
        }
    }

    protected function prepareUpdateStatement(array $data)
    {
        $this->query = 'UPDATE ' . $this->table;

        $this->setUpdateColumnsInSQL($data);

        $this->setWheresInSQL();

        $this->statement = $this->pdo->prepare($this->query);

        $this->bindValues($data);
        $this->bindWhereValues();
    }

    protected function setUpdateColumnsInSQL(array $data)
    {
        $columns = array_keys($data);

        $this->query .= ' SET ' . implode(', ', array_map(function ($column) {
            return $column . ' = :' . $column;
        }, $columns));
    }

    protected function limit(int $limit)
    {
        $this->query .= ' LIMIT ' . $limit;
    }
}