<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/Make/ModelCommand.php
 *  --------------------------------------------------------------------------
 *  This command generates a new model class inside core/Ghasly/Models.
 *  Models serve as ORM representations of database tables.
 */

namespace Phuelone\Console\Commands\Make;

use Phuelone\Console\Command;
use Phuelone\Support\Filesystem;

/**
 * Class ModelCommand
 *
 * Generates a new Ghasly ORM model.
 *
 * Usage:
 *   php phuelone make:model Post
 */
class ModelCommand extends Command
{
    /**
     * CLI command signature.
     *
     * @var string
     */
    protected string $signature = 'make:model {name : The name of the model class}';

    /**
     * Handles the model creation logic.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $modelName = ucfirst($name);
        $path = base_path("core/Ghasly/Models/{$modelName}.php");

        if (Filesystem::exists($path)) {
            $this->error("Model {$modelName} already exists.");
            return;
        }

        Filesystem::put($path, $this->modelStub($modelName));

        $this->success("Model {$modelName} created in core/Ghasly/Models/");
    }

    /**
     * Returns the PHP class stub for a new model.
     *
     * @param string $className
     * @return string
     */
    protected function modelStub(string $className): string
    {
        return <<<PHP
<?php
/**
 * Model: {$className}
 * This class represents the "{$className}" table in the database,
 * handled by the Ghasly ORM system.
 */

namespace Phuelone\Ghasly\Models;

use Phuelone\Ghasly\Model;

class {$className} extends Model
{
    /**
     * Define table name if different from default plural.
     *
     * protected string \$table = 'custom_table_name';
     */

    /**
     * Define fillable columns for mass assignment.
     *
     * protected array \$fillable = ['title', 'body'];
     */
}
PHP;
    }
}

?>