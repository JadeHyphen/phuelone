<?php

namespace Core\Database;

use PDO;
use PDOException;

class PostgreSQLDriver implements DatabaseDriverInterface
{
    private ?PDO $connection = null;

    public function connect(array $config): void
    {
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']};";

        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = []): array|bool
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);

            if (stripos($sql, 'SELECT') === 0) {
                return $stmt->fetchAll();
            }

            return true;
        } catch (PDOException $e) {
            die('Database query error: ' . $e->getMessage());
        }
    }

    public function one(string $sql, array $params = []): array|false
    {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            die('Database fetch error: ' . $e->getMessage());
        }
    }

    public function disconnect(): void
    {
        $this->connection = null;
    }
}
