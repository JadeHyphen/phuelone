<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Support/Filesystem.php
 *  --------------------------------------------------------------------------
 *  This class provides static helper methods for handling file system
 *  operations such as writing, reading, checking, and deleting files.
 */

namespace Core\Support;

/**
 * Class Filesystem
 *
 * A helper for file read/write/delete tasks.
 */
class Filesystem
{
    /**
     * Write contents to a file. Automatically creates any missing directories.
     *
     * @param string $path    Full file path.
     * @param string $content Contents to write into the file.
     * @return void
     */
    public static function put(string $path, string $content): void
    {
        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($path, $content);
    }

    /**
     * Read and return the contents of a file.
     *
     * @param string $path
     * @return string|null
     */
    public static function get(string $path): ?string
    {
        return file_exists($path) ? file_get_contents($path) : null;
    }

    /**
     * Delete a file if it exists.
     *
     * @param string $path
     * @return void
     */
    public static function delete(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Check if a file or directory exists.
     *
     * @param string $path
     * @return bool
     */
    public static function exists(string $path): bool
    {
        return file_exists($path);
    }
}

?>