<?php

use Core\Database\Manager\DatabaseManager;

class m0001_create_users_table
{
    public function up(DatabaseManager $database)
    {
        $database->pdo->exec('CREATE TABLE IF NOT EXISTS `users` (
            `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NULL,
            `email_verified_at` TIMESTAMP NULL,
            `password` VARCHAR(255) NULL,
            `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }

    public function down(DatabaseManager $database)
    {
        $database->pdo->exec('DROP TABLE IF NOT EXISTS `users`');
    }
}