<?php

namespace Core\Database\Manager;

use Core\Database\Database;
use PDO;

class DatabaseManager
    extends AbstractDatabaseManager
    implements DatabaseManagerInterface
{
    public string $table;

    public array $wheres;

    protected string $FETCH_CLASS = Database::class;

    public function __construct($FETCH_CLASS)
    {
        parent::__construct();

        $this->FETCH_CLASS = $FETCH_CLASS;
    }
    
    public function table(string $table): void
    {
        $this->table = $table;
    }
    
    public function get(): array
    {
        $this->prepareSelectStatememt();
    
        $this->statement->execute();
    
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $this->FETCH_CLASS);
    }
    
    public function first(): object|null
    {
        $this->prepareSelectStatememt();

        $this->limit(1);
    
        $this->statement->execute();
    
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $this->FETCH_CLASS)[0] ?? null;
    }
    
    public function where(string $column, $operator = '=', $value = ''): void
    {
        $this->wheres[$column] = [
            'operator' => $operator,
            'value' => $value
        ];
    }
    
    public function create(array $data)
    {
        $this->prepareInsertStatement($data);
        
        $this->statement->execute();
        
        $this->where('id', '=', $this->pdo->lastInsertId());

        return $this->first();
    }
    
    public function update(array $data)
    {
        $this->prepareUpdateStatement($data);
        
        $this->statement->execute();
        
        return $this->statement->fetchAll(PDO::FETCH_CLASS, $this->FETCH_CLASS);
    }
    
    public function delete()
    {

    }
}