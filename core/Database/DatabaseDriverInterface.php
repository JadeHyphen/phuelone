<?php

namespace Core\Database;

interface DatabaseDriverInterface
{
    public function connect(array $config): void;
    public function query(string $sql, array $params = []): array|bool;
    public function one(string $sql, array $params = []): array|false;
    public function disconnect(): void;
}
