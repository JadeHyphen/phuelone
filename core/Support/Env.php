<?php

namespace Core\Support;

/**
 * Class Env
 *
 * Securely loads and retrieves environment variables.
 */
class Env
{
    /**
     * Internal cache of loaded values.
     */
    protected static array $loaded = [];

    /**
     * Load the .env file manually.
     *
     * @param string $path Path to .env file
     */
    public static function load(string $path = __DIR__ . '/../../../.env'): void
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Skip comments
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $line, 2), 2, null);

            if ($key !== null) {
                $key = trim($key);
                $value = trim((string) $value);

                // Remove surrounding quotes
                $value = trim($value, '"\'');

                // Cache and setenv
                static::$loaded[$key] = static::cast($value);
                putenv("$key=$value");
            }
        }
    }

    /**
     * Get an env variable with optional default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, static::$loaded)) {
            return static::$loaded[$key];
        }

        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return static::cast($value);
    }

    /**
     * Set an environment variable manually.
     */
    public static function set(string $key, mixed $value): void
    {
        static::$loaded[$key] = $value;
        putenv("$key=$value");
    }

    /**
     * Typecast string values to native PHP types.
     */
    protected static function cast(string $value): mixed
    {
        return match (strtolower($value)) {
            'true'  => true,
            'false' => false,
            'null'  => null,
            default => is_numeric($value) ? (float)$value + 0 : $value,
        };
    }
}

?>