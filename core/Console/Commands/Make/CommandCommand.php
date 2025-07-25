<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/Make/CommandCommand.php
 *  --------------------------------------------------------------------------
 *  This command scaffolds a new custom CLI command class in core/Console/Commands.
 *  Developers can define signature, description, and handle logic.
 */

namespace Phuelone\Console\Commands\Make;

use Phuelone\Console\Command;
use Phuelone\Support\Filesystem;

/**
 * Class CommandCommand
 *
 * Generates a new CLI command scaffold class.
 *
 * Usage:
 *   php phuelone make:command SendEmailsCommand
 */
class CommandCommand extends Command
{
    /**
     * CLI signature for this command.
     *
     * @var string
     */
    protected string $signature = 'make:command {name : The class name for the command (must end with "Command")}';

    /**
     * Handles the creation of a new command class.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');

        if (!str_ends_with($name, 'Command')) {
            $this->error("Command name must end with 'Command'.");
            return;
        }

        $filename = "core/Console/Commands/{$name}.php";

        if (Filesystem::exists(base_path($filename))) {
            $this->error("Command {$name} already exists.");
            return;
        }

        Filesystem::put(base_path($filename), $this->commandStub($name));

        $this->success("Command {$name} created at core/Console/Commands/");
    }

    /**
     * Returns the stub content for a basic CLI command class.
     *
     * @param string $name
     * @return string
     */
    protected function commandStub(string $name): string
    {
        $className = $name;
        $signature = strtolower(preg_replace('/Command$/', '', $className));

        return <<<PHP
<?php

namespace Phuelone\Console\Commands;

use Phuelone\Console\Command;

/**
 * Class {$className}
 *
 * Custom CLI command generated via CLI.
 */
class {$className} extends Command
{
    /**
     * The command signature used in the CLI.
     *
     * @var string
     */
    protected string \$signature = '{$signature}';

    /**
     * The logic executed when the command is run.
     *
     * @return void
     */
    public function handle(): void
    {
        // Your CLI command logic here
        \$this->info("{$signature} command executed.");
    }
}
PHP;
    }
}

?>