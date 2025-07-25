<?php

namespace Core\Database;

use Core\Support\Env;

/**
 * Class Database
 *
 * Handles all database connections and raw queries securely using PDO.
 */
class Database
{
    /**
     * Establish and return the PDO connection.
     */
    public static function connection(): void
    {
        DatabaseManager::initialize();
    }

    /**
     * Execute a secure SQL query with optional parameters.
     */
    public static function query(string $sql, array $params = []): array|bool
    {
        return DatabaseManager::query($sql, $params);
    }

    /**
     * Run a SQL query and return a single row.
     */
    public static function one(string $sql, array $params = []): array|false
    {
        return DatabaseManager::one($sql, $params);
    }

    /**
     * Disconnect the database connection.
     */
    public static function disconnect(): void
    {
        DatabaseManager::disconnect();
    }
}

?>