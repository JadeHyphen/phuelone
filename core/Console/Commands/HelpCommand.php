<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/HelpCommand.php
 *  --------------------------------------------------------------------------
 *  Displays a list of all available CLI commands with descriptions.
 */

namespace Phuelone\Console\Commands;

use Phuelone\Console\Command;

/**
 * Class HelpCommand
 *
 * Shows help info and available commands.
 */
class HelpCommand extends Command
{
    /**
     * Command signature.
     *
     * @var string
     */
    protected string $signature = 'help';

    /**
     * Array of commands with descriptions.
     *
     * @var array<string, string>
     */
    protected array $commands = [
        'make:model'        => 'Create a new model class',
        'make:controller'   => 'Create a new controller class',
        'make:view'         => 'Create a new view file',
        'make:command'      => 'Create a new CLI command',
        'make:migration'    => 'Create a new migration file',
        'key:generate'      => 'Generate the APP_KEY in .env',
        'optimize'          => 'Cache config and views for optimization',
        'help'              => 'Display this help message',
        'version'           => 'Show the Phuelone framework version',
    ];

    /**
     * Execute the help command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info("Phuelone CLI - Available Commands:");
        foreach ($this->commands as $command => $description) {
            printf("  %-20s %s\n", $command, $description);
        }
    }
}

?>