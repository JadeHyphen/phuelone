<?php

namespace Core\Auth;

use Core\Http\Request;
use Core\Http\Response;

/**
 * Class Auth
 *
 * Handles authentication and authorization.
 */
class Auth
{
    protected static ?User $user = null;

    public static function login(string $email, string $password): bool
    {
        // Replace with actual database lookup
        $userData = [
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'password', // Hash passwords in production
            'roles' => ['admin'],
        ];

        if ($email === $userData['email'] && $password === $userData['password']) {
            static::$user = new User($userData);
            $_SESSION['user'] = static::$user->toArray();
            return true;
        }

        return false;
    }

    public static function logout(): void
    {
        static::$user = null;
        unset($_SESSION['user']);
    }

    public static function user(): ?User
    {
        if (static::$user === null && isset($_SESSION['user'])) {
            static::$user = new User($_SESSION['user']);
        }

        return static::$user;
    }

    public static function check(): bool
    {
        return static::user() !== null;
    }

    public static function authorize(string $role): bool
    {
        $user = static::user();
        return $user && $user->hasRole($role);
    }
}

?>
