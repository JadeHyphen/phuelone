<?php

namespace Core\Ghasly\Models;

use Core\Ghasly\Query\Builder;

abstract class Model
{
    protected string $table;
    protected array $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public static function query(): Builder
    {
        $instance = new static;
        return new Builder($instance->getTable());
    }

    public function getTable(): string
    {
        return $this->table ?? strtolower(static::class) . 's';
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }

    public static function find(int $id): ?static
    {
        $result = static::query()->where('id', '=', $id)->first((new static)->getTable());
        return $result ? new static($result) : null;
    }

    public static function all(): array
    {
        $results = static::query()->get((new static)->getTable());
        return array_map(fn($row) => new static($row), $results);
    }

    public static function create(array $data): static
    {
        $instance = new static($data);
        $instance->beforeCreate();
        $instance->beforeSave();

        static::query()->insert($instance->getTable(), $data);

        $instance->afterCreate();
        $instance->afterSave();

        return $instance;
    }

    public function save(): void
    {
        $query = static::query();

        if (isset($this->attributes['id'])) {
            $id = $this->attributes['id'];
            $data = $this->attributes;
            unset($data['id']);

            $this->beforeUpdate();
            $this->beforeSave();

            $query->where('id', '=', $id)->update($this->getTable(), $data);

            $this->afterUpdate();
            $this->afterSave();
        } else {
            $this->beforeCreate();
            $this->beforeSave();

            $query->insert($this->getTable(), $this->attributes);

            $this->afterCreate();
            $this->afterSave();
        }
    }

    public function delete(): void
    {
        if (!isset($this->attributes['id'])) return;

        $this->beforeDelete();
        static::query()->where('id', '=', $this->attributes['id'])->delete($this->getTable());
        $this->afterDelete();
    }

    // 🔁 Event stubs (can be overridden by concrete model)
    protected function beforeCreate(): void {}
    protected function afterCreate(): void {}
    protected function beforeUpdate(): void {}
    protected function afterUpdate(): void {}
    protected function beforeSave(): void {}
    protected function afterSave(): void {}
    protected function beforeDelete(): void {}
    protected function afterDelete(): void {}
}

?>