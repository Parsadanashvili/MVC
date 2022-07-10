<?php

namespace Core\Database;

use Core\Database\Manager\DatabaseManager;
use Core\Database\Manager\DatabaseManagerInterface;
use PDO;
use ReflectionMethod;

class Migration
{
    public string $migrationsDir;

    public DatabaseManagerInterface $manager;

    public function __construct($migrationsDir)
    {
        $this->manager = new DatabaseManager(Database::class);
        $this->migrationsDir = $migrationsDir;
    }

    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];

        $migrationFiles = $this->getMigrationFiles();
        $toApplyMigrations = array_diff($migrationFiles, $appliedMigrations);
        
        foreach($toApplyMigrations as $migration) {
            require_once $this->migrationsDir . '/' . $migration . '.php';
            $className = pathinfo($migration, PATHINFO_FILENAME);
            
            if(class_exists($className)) {
                $instance = new $className();

                if(method_exists($instance, 'up')) {
                    $this->log("\033[33m Applying migration $migration...\033[0m");
                    
                    $instance->up(...$this->getMethodArgs($instance, 'up'));

                    $this->log("\033[92m Applied migration $migration\033[0m");
    
                    $newMigrations[] = $migration;
                } else {
                    $this->log("\033[31m Migration $migration does not have an up() method\033[0m");
                }
            } else {
                $this->log("\033[31m Migration $migration does not exist\033[0m");
            }
        }

        if(!empty($newMigrations)) {
            $this->saveAppliedMigrations($newMigrations);
        } else {
            $this->log("\033[31m No new migrations to apply\033[0m");
        }
    }

    public function createMigration($name)
    {
        $newMigrationId = $this->getNewMigrationId();
        $newMigrationName = $newMigrationId . '_' . $name;
        $filePath = $this->migrationsDir . '/' . $newMigrationName . '.php';

        if($this->checkIfMigrationAlreadyExists($name)) return;
        
        $this->log("\033[33m Creating migration \033[32m$newMigrationName\033[33m...\033[0m");


        $template = file_get_contents(__DIR__ . '/Templates/migration.template');
        $template = '<?php' . PHP_EOL . $template;

        $template = str_replace('{id}', $newMigrationId, $template);
        $template = str_replace('{name}', strtolower($name), $template);
        
        file_put_contents($filePath, $template);
        
        $this->log("\033[92m Migration Created\033[0m");
    }

    public function log($message)
    {
        echo '[' . date("H:i:s") . '] - ' . $message . PHP_EOL;
    }

    protected function createMigrationsTable()
    {
        $this->manager->pdo->exec("CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `migration` VARCHAR(255) NOT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
    }

    protected function getAppliedMigrations()
    {
        $statement = $this->manager->pdo->prepare("SELECT migration FROM `migrations`");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function saveAppliedMigrations(array $migrations)
    {
        $statement = $this->manager->pdo->prepare("INSERT INTO `migrations` (`migration`, `created_at`) VALUES (:migration, :created_at)");
        foreach($migrations as $migration) {
            $statement->execute([
                'migration' => $migration,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    protected function getMigrationFiles()
    {
        $migrationFiles = scandir($this->migrationsDir);
        $migrationFiles = array_map(function ($file) {
            if($file === '.' || $file === '..') {
                return;
            }
            return str_replace('.php', '', $file);
        }, $migrationFiles);
        $migrationFiles = array_filter($migrationFiles);
        $migrationFiles = array_values($migrationFiles);
        return $migrationFiles;
    }

    protected function getLastMigrationId()
    {
        $migrations = $this->getMigrationFiles();
        $lastMigration = end($migrations);
        preg_match('/m[0-9]+/', $lastMigration, $matches);
        $lastMigrationId = $matches[0] ?? 'm0001';
        return $lastMigrationId;
    }

    protected function getNewMigrationId()
    {
        $migrations = $this->getMigrationFiles();
        $lastMigration = end($migrations);
        preg_match('/m[0-9]+/', $lastMigration, $matches);
        $lastMigrationId = $matches[0];
        if(!$lastMigrationId) {
            return 'm0001';
        }
        $lastMigrationId = str_replace('m', '', $lastMigrationId);
        $lastMigrationId = intval($lastMigrationId);
        $lastMigrationId++;
        return 'm' . str_pad($lastMigrationId, 4, '0', STR_PAD_LEFT);
    }

    protected function checkIfMigrationAlreadyExists($name)
    {
        $lastMigration = $name;
        $migrations = $this->getMigrationFiles();
        $migrations = array_map(function ($file) {
            return preg_replace('/m[0-9]+_/', '', $file);
        }, $migrations);
        if(in_array($lastMigration, $migrations)) {
            $this->log("\033[31m Migration $lastMigration already exists\033[0m");
            return true;
        }
        return false;
    }

    protected function getMethodArgs($class, $method)
    {
        $ref = new ReflectionMethod($class, $method);
        $args = [];
        foreach($ref->getParameters() as $param) {
            if($param->getClass()) {
                $args[] = $param->getClass()->newInstance();
            } else if($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $args[] = null;
            }
        }
        
        return $args;
    }
}