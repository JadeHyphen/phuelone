<?php

namespace Core\Ghasly\Query;

use Core\Ghasly\Connections\Connection;
use PDO;
use PDOStatement;

/**
 * Class Builder
 *
 * A fluent query builder for constructing and executing SQL queries securely.
 */
class Builder
{
    protected string $table;
    protected array $columns = ['*'];
    protected array $wheres = [];
    protected array $bindings = [];

    protected ?int $limit = null;

    protected PDO $connection;

    protected array $results = [];

    public function __construct(string $table)
    {
        $this->table = $table;
        $this->connection = Connection::get();
    }

    /**
     * Specify columns to select.
     */
    public function select(array $columns = ['*']): static
    {
        $this->columns = $columns;
        return $this;
    }

   /**
 * Add a basic WHERE clause to the query.
 *
 * @param string $column
 * @param string $operator
 * @param mixed $value
 * @param string $boolean
 * @return $this
 */
public function where(string $column, string $operator, mixed $value, string $boolean = 'AND'): static
{
    $this->wheres[] = [
        'type'     => 'Basic',
        'column'   => $column,
        'operator' => $operator,
        'value'    => $value,
        'boolean'  => $boolean
    ];

    return $this;
}

    /**
 * Compile the WHERE clause into SQL.
 *
 * @return string
 */
protected function compileWheres(): string
{
    if (empty($this->wheres)) {
        return '';
    }

    $sql = 'WHERE ';
    foreach ($this->wheres as $index => $where) {
        $prefix = $index === 0 ? '' : $where['boolean'] . ' ';
        $sql .= "{$prefix}{$where['column']} {$where['operator']} ?";
        if ($index < count($this->wheres) - 1) {
            $sql .= ' ';
        }
    }

    return $sql;
}

    /**
     * Limit the number of results.
     */
    public function limit(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    /**
 * Execute a SELECT query and return the results.
 *
 * @param string $table
 * @param array $columns
 * @return array
 */
public function get(string $table, array $columns = ['*']): array
{
    $columnList = implode(', ', $columns);
    $sql = "SELECT {$columnList} FROM {$table}";

    $whereClause = $this->compileWheres();
    if ($whereClause) {
        $sql .= " {$whereClause}";
    }

    $stmt = $this->connection->prepare($sql);

    // Bind values from where conditions
    $values = array_map(fn($w) => $w['value'], $this->wheres);
    foreach ($values as $i => $value) {
        $stmt->bindValue($i + 1, $value);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

    /**
 * Execute a SELECT query and return the first result.
 *
 * @param string $table
 * @param array $columns
 * @return array|null
 */
public function first(string $table, array $columns = ['*']): ?array
{
    $columnList = implode(', ', $columns);
    $sql = "SELECT {$columnList} FROM {$table}";

    $whereClause = $this->compileWheres();
    if ($whereClause) {
        $sql .= " {$whereClause}";
    }

    // Limit to 1 result
    $sql .= " LIMIT 1";

    $stmt = $this->connection->prepare($sql);

    // Bind where values
    $values = array_map(fn($w) => $w['value'], $this->wheres);
    foreach ($values as $i => $value) {
        $stmt->bindValue($i + 1, $value);
    }

    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
}

   /**
 * Insert a new record into the given table.
 *
 * @param string $table
 * @param array $data
 * @return bool
 */
public function insert(string $table, array $data): bool
{
    if (empty($data)) {
        return false;
    }

    $columns = implode(', ', array_keys($data));
    $placeholders = implode(', ', array_fill(0, count($data), '?'));
    $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

    $stmt = $this->connection->prepare($sql);

    // Bind values securely
    $i = 1;
    foreach ($data as $value) {
        $stmt->bindValue($i++, $value);
    }

    return $stmt->execute();
}

    /**
     * Compile SELECT SQL.
     */
    protected function compileSelect(): string
    {
        $columns = implode(', ', $this->columns);
        $sql = "SELECT $columns FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        if ($this->limit !== null) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }

    /**
     * Execute a query and return the statement.
     */
    protected function execute(string $sql, array $bindings): PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($bindings);
        return $stmt;
    }

    /**
 * Perform an UPDATE operation on a table.
 *
 * @param string $table
 * @param array $data
 * @return bool
 */
public function update(string $table, array $data): bool
{
    if (empty($this->wheres)) {
        throw new \Exception("Unsafe update operation: no WHERE clause defined.");
    }

    $set = [];
    $bindings = [];

    foreach ($data as $column => $value) {
        $set[] = "{$column} = ?";
        $bindings[] = $value;
    }

    $setClause = implode(', ', $set);

    $whereClause = $this->compileWheres();
    $whereBindings = array_map(fn($w) => $w['value'], $this->wheres);

    $sql = "UPDATE {$table} SET {$setClause} {$whereClause}";

    $stmt = $this->connection->prepare($sql);

    $allBindings = array_merge($bindings, $whereBindings);
    foreach ($allBindings as $i => $value) {
        $stmt->bindValue($i + 1, $value);
    }

    return $stmt->execute();
}


   /**
 * Paginate the results of a SELECT query, with total count and last page.
 *
 * @param string $table
 * @param int $perPage
 * @param int $page
 * @param array $columns
 * @return array
 */
public function paginate(string $table, int $perPage = 15, int $page = 1, array $columns = ['*']): array
{
    $offset = ($page - 1) * $perPage;
    $columnList = implode(', ', $columns);
    $sql = "SELECT {$columnList} FROM {$table}";

    $whereClause = $this->compileWheres();
    if ($whereClause) {
        $sql .= " {$whereClause}";
    }

    $sql .= " LIMIT {$perPage} OFFSET {$offset}";

    $stmt = $this->connection->prepare($sql);

    // Bind where values
    $values = array_map(fn($w) => $w['value'], $this->wheres);
    foreach ($values as $i => $value) {
        $stmt->bindValue($i + 1, $value);
    }

    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Count total results
    $countSql = "SELECT COUNT(*) FROM {$table}";
    if ($whereClause) {
        $countSql .= " {$whereClause}";
    }

    $countStmt = $this->connection->prepare($countSql);

    foreach ($values as $i => $value) {
        $countStmt->bindValue($i + 1, $value);
    }

    $countStmt->execute();
    $total = (int) $countStmt->fetchColumn();
    $lastPage = (int) ceil($total / $perPage);

    return [
        'data' => $results,
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $page,
        'last_page' => $lastPage
    ];
}

/**
 * Insert multiple records into a table in a single query.
 *
 * @param string $table
 * @param array $rows
 * @return bool
 */
public function insertMany(string $table, array $rows): bool
{
    if (empty($rows)) {
        return false;
    }

    $columns = array_keys($rows[0]);
    $columnList = implode(', ', $columns);

    $placeholders = [];
    $bindings = [];

    foreach ($rows as $row) {
        $rowPlaceholders = [];
        foreach ($columns as $column) {
            $rowPlaceholders[] = '?';
            $bindings[] = $row[$column];
        }
        $placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
    }

    $sql = "INSERT INTO {$table} ({$columnList}) VALUES " . implode(', ', $placeholders);

    $stmt = $this->connection->prepare($sql);

    foreach ($bindings as $i => $value) {
        $stmt->bindValue($i + 1, $value);
    }

    return $stmt->execute();
}

    /**
 * Delete records from the table matching the WHERE clauses.
 *
 * @param string $table
 * @return bool
 * @throws \Exception If no WHERE clause is specified.
 */
public function delete(string $table): bool
{
    if (empty($this->wheres)) {
        throw new \Exception('Unsafe delete operation: WHERE clause is required.');
    }

    $sql = "DELETE FROM {$table} " . $this->compileWheres();

    $stmt = $this->connection->prepare($sql);

    // Bind WHERE values
    $values = array_map(fn($w) => $w['value'], $this->wheres);
    foreach ($values as $i => $value) {
        $stmt->bindValue($i + 1, $value);
    }

    return $stmt->execute();
}


/**
 * Manually hydrate raw rows into the builder’s internal result set.
 *
 * @param array $rows
 * @return $this
 */
public function hydrateRawRows(array $rows): static
{
    $this->results = $rows;
    return $this;
}


  

}

?>