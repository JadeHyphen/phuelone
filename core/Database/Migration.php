<?php

namespace Core\Database;

abstract class Migration
{
    protected $connection;

    public function __construct()
    {
        $this->connection = new \PDO('mysql:host=localhost;dbname=phuelone', 'root', 'password');
    }

    protected function execute(string $query): void
    {
        $this->connection->exec($query);
    }

    abstract public function up(): void;

    abstract public function down(): void;
}
