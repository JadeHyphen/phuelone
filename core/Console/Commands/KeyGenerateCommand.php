<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/KeyGenerateCommand.php
 *  --------------------------------------------------------------------------
 *  Generates a secure random APP_KEY and sets it in the .env file.
 */

namespace Phuelone\Console\Commands;

use Phuelone\Console\Command;
use Phuelone\Support\Filesystem;

/**
 * Class KeyGenerateCommand
 *
 * Generates a base64-encoded secure key and updates the .env file.
 */
class KeyGenerateCommand extends Command
{
    /**
     * Command signature.
     *
     * @var string
     */
    protected string $signature = 'key:generate';

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle(): void
    {
        $key = base64_encode(random_bytes(32));
        $envPath = base_path('.env');

        if (!Filesystem::exists($envPath)) {
            $this->error('.env file not found.');
            return;
        }

        $envContent = Filesystem::get($envPath);

        // Replace existing APP_KEY or add it
        if (preg_match('/^APP_KEY=.*$/m', $envContent)) {
            $envContent = preg_replace('/^APP_KEY=.*$/m', "APP_KEY=base64:{$key}", $envContent);
        } else {
            $envContent .= PHP_EOL . "APP_KEY=base64:{$key}" . PHP_EOL;
        }

        Filesystem::put($envPath, $envContent);

        $this->success('Application key [APP_KEY] set successfully.');
    }
}

?>