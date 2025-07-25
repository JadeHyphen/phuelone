<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/ConsoleKernel.php
 *  --------------------------------------------------------------------------
 *  Handles registration and execution of CLI commands.
 */

namespace Phuelone\Console;

use Phuelone\Console\Command;
use ReflectionClass;

/**
 * Class ConsoleKernel
 *
 * Registers commands and runs them based on CLI input.
 */
class ConsoleKernel
{
    /**
     * List of command instances.
     *
     * @var Command[]
     */
    protected array $commands = [];

    /**
     * Register all available command classes here.
     *
     * @return void
     */
    public function registerCommands(): void
    {
        // Manually register command classes here
        $this->commands[] = new Commands\Make\ModelCommand();
        $this->commands[] = new Commands\Make\ControllerCommand();
        $this->commands[] = new Commands\Make\ViewCommand();
        $this->commands[] = new Commands\Make\CommandCommand();
        $this->commands[] = new Commands\Make\MigrationCommand();
        $this->commands[] = new Commands\KeyGenerateCommand();
        $this->commands[] = new Commands\OptimizeCommand();
        $this->commands[] = new Commands\HelpCommand();
        $this->commands[] = new Commands\VersionCommand();
    }

    /**
     * Run the CLI application.
     *
     * @param array $argv CLI arguments
     * @return void
     */
    public function handle(array $argv): void
    {
        $this->registerCommands();

        if (count($argv) < 2) {
            $this->displayUsage();
            return;
        }

        $inputCommand = $argv[1];
        $inputArgs = array_slice($argv, 2);

        foreach ($this->commands as $command) {
            if ($command->getName() === $inputCommand) {
                $command->__construct($inputArgs); // Pass args to command constructor
                $command->handle();
                return;
            }
        }

        echo "Command '{$inputCommand}' not found. Run 'php phuelone help' for list.\n";
    }

    /**
     * Display basic usage info.
     *
     * @return void
     */
    protected function displayUsage(): void
    {
        echo "Usage: php phuelone <command> [arguments]\n";
        echo "Run 'php phuelone help' for available commands.\n";
    }
}

?>