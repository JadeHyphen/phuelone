<?php

namespace Core\Database;

use PDO;
use PDOException;
use Core\Support\Env;

/**
 * Class Database
 *
 * Handles all database connections and raw queries securely using PDO.
 */
class Database
{
    protected static ?PDO $connection = null;

    /**
     * Establish and return the PDO connection.
     */
    public static function connection(): PDO
    {
        if (static::$connection === null) {
            $host     = Env::get('DB_HOST', '127.0.0.1');
            $port     = Env::get('DB_PORT', '3306');
            $database = Env::get('DB_DATABASE', 'phuelone');
            $username = Env::get('DB_USERNAME', 'root');
            $password = Env::get('DB_PASS', '');

            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

            try {
                static::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false, // Real prepared statements (prevents SQL injection)
                ]);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return static::$connection;
    }

    /**
     * Execute a secure SQL query with optional parameters.
     */
    public static function query(string $sql, array $params = []): array|bool
    {
        try {
            $stmt = static::connection()->prepare($sql);
            $stmt->execute($params);

            // Auto-detect SELECT vs INSERT/UPDATE
            if (stripos($sql, 'SELECT') === 0) {
                return $stmt->fetchAll();
            }

            return true;
        } catch (PDOException $e) {
            die('Database query error: ' . $e->getMessage());
        }
    }

    /**
     * Run a SQL query and return a single row.
     */
    public static function one(string $sql, array $params = []): array|false
    {
        try {
            $stmt = static::connection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die('Database fetch error: ' . $e->getMessage());
        }
    }

    /**
     * Disconnect the database connection.
     */
    public static function disconnect(): void
    {
        static::$connection = null;
    }
}

?>