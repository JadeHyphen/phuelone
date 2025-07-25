<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/Make/MigrationCommand.php
 *  --------------------------------------------------------------------------
 *  This command creates a timestamped migration file in database/migrations.
 *  Migrations define schema changes and are interpreted by the Ghasly ORM engine.
 */

namespace Phuelone\Console\Commands\Make;

use Phuelone\Console\Command;
use Phuelone\Support\Filesystem;

/**
 * Class MigrationCommand
 *
 * Scaffolds a new migration file with up() and down() methods.
 *
 * Usage:
 *   php phuelone make:migration create_users_table
 */
class MigrationCommand extends Command
{
    /**
     * Command signature for CLI.
     *
     * @var string
     */
    protected string $signature = 'make:migration {name : The name of the migration (snake_case)}';

    /**
     * Handle the migration file creation.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $timestamp = date('Y_m_d_His');
        $fileName = "{$timestamp}_{$name}.php";
        $path = base_path("database/migrations/{$fileName}");

        if (Filesystem::exists($path)) {
            $this->error("Migration {$fileName} already exists.");
            return;
        }

        Filesystem::put($path, $this->migrationStub($name));

        $this->success("Migration {$fileName} created in database/migrations/");
    }

    /**
     * Returns stub content for a new migration.
     *
     * @param string $name
     * @return string
     */
    protected function migrationStub(string $name): string
    {
        $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));

        return <<<PHP
<?php
/**
 * Migration: {$className}
 * Automatically generated migration using CLI.
 */

use Phuelone\Ghasly\Schema\Migration;
use Phuelone\Ghasly\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @param Blueprint \$table
     * @return void
     */
    public function up(Blueprint \$table): void
    {
        // \$table->create('table_name')->id()->string('column')->timestamps();
    }

    /**
     * Reverse the migrations.
     *
     * @param Blueprint \$table
     * @return void
     */
    public function down(Blueprint \$table): void
    {
        // \$table->drop('table_name');
    }
};
PHP;
    }
}

?>