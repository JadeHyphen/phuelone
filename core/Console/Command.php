<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Command.php
 *  --------------------------------------------------------------------------
 *  Base class that all Phuelone CLI commands extend from.
 *  Provides argument parsing, signature handling, and common helpers.
 */

namespace Phuelone\Console;

/**
 * Class Command
 *
 * Core logic shared by all CLI commands.
 */
abstract class Command
{
    /**
     * Signature format: `command:name {arg1} {arg2?}`
     *
     * @var string
     */
    protected string $signature;

    /**
     * Parsed CLI arguments (positional).
     *
     * @var array
     */
    protected array $arguments = [];

    /**
     * Construct with raw input arguments.
     *
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        $this->parseArguments($input);
        $this->parseOptions($input);
    }

    /**
     * Parse CLI arguments into $arguments array.
     *
     * @param array $input
     * @return void
     */
    protected function parseArguments(array $input): void
    {
        $parts = explode(' ', $this->signature);
        $argDefinitions = array_slice($parts, 1);

        foreach ($argDefinitions as $index => $part) {
            if (preg_match('/\{(.+?)\}/', $part, $matches)) {
                $name = explode(' ', $matches[1])[0];
                $this->arguments[$name] = $input[$index] ?? null;
            }
        }
    }

    /**
     * Get a specific argument value.
     *
     * @param string $name
     * @return string|null
     */
    public function argument(string $name): ?string
    {
        return $this->arguments[$name] ?? null;
    }

    /**
     * Must be implemented by child commands.
     *
     * @return void
     */
    abstract public function handle(): void;

    /**
     * Display a success message.
     *
     * @param string $message
     * @return void
     */
    protected function success(string $message): void
    {
        echo "\033[32m✔ {$message}\033[0m" . PHP_EOL;
    }

    /**
     * Display an error message.
     *
     * @param string $message
     * @return void
     */
    protected function error(string $message): void
    {
        echo "\033[31m✘ {$message}\033[0m" . PHP_EOL;
    }

    /**
     * Display a neutral info message.
     *
     * @param string $message
     * @return void
     */
    protected function info(string $message): void
    {
        echo "\033[36m{$message}\033[0m" . PHP_EOL;
    }

    /**
 * Parsed CLI options (named with --option).
 *
 * @var array
 */
protected array $options = [];

/**
 * Parse CLI options from input.
 *
 * @param array $input
 * @return void
 */
protected function parseOptions(array $input): void
{
    foreach ($input as $arg) {
        if (str_starts_with($arg, '--')) {
            $option = substr($arg, 2);
            $this->options[$option] = true; // Simple flag support
        }
    }
}

/**
 * Get an option value or flag.
 *
 * @param string $name
 * @return mixed|null
 */
public function option(string $name)
{
    return $this->options[$name] ?? null;
}

/**
 * Get the command name (the first part of the signature).
 *
 * @return string
 */
public function getName(): string
{
    return explode(' ', $this->signature)[0];
}
  }

?>