<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/VersionCommand.php
 *  --------------------------------------------------------------------------
 *  Displays the current version of the Phuelone framework.
 */

namespace Phuelone\Console\Commands;

use Phuelone\Console\Command;

/**
 * Class VersionCommand
 *
 * Outputs the current framework version.
 */
class VersionCommand extends Command
{
    /**
     * CLI command signature.
     *
     * @var string
     */
    protected string $signature = 'version';

    /**
     * Current version string.
     *
     * @var string
     */
    protected string $version = 'Phuelone Framework v1.0.0';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info($this->version);
    }
}

?>