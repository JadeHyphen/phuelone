<?php

namespace Core\Auth;

/**
 * Class User
 *
 * Represents a user in the system.
 */
class User
{
    protected int $id;
    protected string $name;
    protected string $email;
    protected string $password;
    protected array $roles = [];

    public function __construct(array $attributes = [])
    {
        $this->id = $attributes['id'] ?? 0;
        $this->name = $attributes['name'] ?? '';
        $this->email = $attributes['email'] ?? '';
        $this->password = $attributes['password'] ?? '';
        $this->roles = $attributes['roles'] ?? [];
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function assignRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, fn($r) => $r !== $role);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'roles' => $this->roles,
        ];
    }
}

?>
