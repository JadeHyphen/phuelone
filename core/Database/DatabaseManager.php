<?php

namespace Core\Database;

use Core\Support\Env;

class DatabaseManager
{
    private static ?DatabaseDriverInterface $driver = null;

    public static function initialize(): void
    {
        $dbType = Env::get('DB_TYPE', 'mysql');

        switch ($dbType) {
            case 'mysql':
                self::$driver = new MySQLDriver();
                break;
            // Add cases for other database types (e.g., PostgreSQL, SQLite)
            default:
                throw new \Exception("Unsupported database type: {$dbType}");
        }

        $config = [
            'host'     => Env::get('DB_HOST', '127.0.0.1'),
            'port'     => Env::get('DB_PORT', '3306'),
            'database' => Env::get('DB_DATABASE', 'phuelone'),
            'username' => Env::get('DB_USERNAME', 'root'),
            'password' => Env::get('DB_PASS', ''),
        ];

        self::$driver->connect($config);
    }

    public static function query(string $sql, array $params = []): array|bool
    {
        return self::$driver->query($sql, $params);
    }

    public static function one(string $sql, array $params = []): array|false
    {
        return self::$driver->one($sql, $params);
    }

    public static function disconnect(): void
    {
        self::$driver->disconnect();
    }
}
