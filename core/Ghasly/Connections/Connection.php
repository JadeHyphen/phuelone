<?php

namespace Core\Ghasly\Connections;

use PDO;
use PDOException;
use Core\Support\Env;

class Connection
{
    /**
     * The PDO instance.
     *
     * @var PDO|null
     */
    protected static ?PDO $pdo = null;

    /**
     * Establishes and returns a PDO connection.
     *
     * @return PDO
     * @throws \Exception if connection fails
     */
    public static function get(): PDO
    {
        if (self::$pdo === null) {
            self::connect();
        }

        return self::$pdo;
    }

    /**
     * Connects to the database using credentials from the .env file.
     *
     * @return void
     * @throws \Exception
     */
    protected static function connect(): void
    {
        $driver   = Env::get('DB_CONNECTION', 'mysql');
        $host     = Env::get('DB_HOST', '127.0.0.1');
        $port     = Env::get('DB_PORT', '3306');
        $database = Env::get('DB_DATABASE');
        $username = Env::get('DB_USERNAME');
        $password = Env::get('DB_PASSWORD');
        $charset  = Env::get('DB_CHARSET', 'utf8mb4');

        $dsn = "$driver:host=$host;port=$port;dbname=$database;charset=$charset";

        try {
            self::$pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage());
        }
    }


    // Get Instance
    public static function getInstance(): ?PDO
    {
        return self::$pdo;
    }
}

?>