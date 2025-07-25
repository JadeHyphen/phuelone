<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/Make/ControllerCommand.php
 *  --------------------------------------------------------------------------
 *  This command generates a new controller class inside /app/Controllers.
 *  It accepts the controller name and optionally creates a resource controller
 *  with standard CRUD methods (index, show, store, update, destroy).
 */

namespace Phuelone\Console\Commands\Make;

use Phuelone\Console\Command;
use Phuelone\Support\Filesystem;

/**
 * Class ControllerCommand
 *
 * Creates a new controller file with optional resource methods.
 *
 * Usage:
 *   php phuelone make:controller UserController
 *   php phuelone make:controller UserController --resource
 */
class ControllerCommand extends Command
{
    /**
     * Command signature recognized by the CLI.
     *
     * @var string
     */
    protected string $signature = 'make:controller {name : The name of the controller} {--resource : Generate CRUD methods}';

    /**
     * Handles the execution of the controller generator command.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $isResource = $this->option('resource');

        $controllerPath = base_path("app/Controllers/{$name}.php");

        if (file_exists($controllerPath)) {
            $this->error("Controller {$name} already exists.");
            return;
        }

        $stub = $isResource ? $this->resourceStub($name) : $this->basicStub($name);

        Filesystem::put($controllerPath, $stub);

        $this->success("Controller {$name} created at app/Controllers/");
    }

    /**
     * Returns a basic controller class stub.
     *
     * @param string $name
     * @return string
     */
    protected function basicStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Controllers;

/**
 * Class {$name}
 *
 * A basic controller class.
 */
class {$name}
{
    // Your methods go here
}
PHP;
    }

    /**
     * Returns a resourceful controller class stub with CRUD methods.
     *
     * @param string $name
     * @return string
     */
    protected function resourceStub(string $name): string
    {
        return <<<PHP
<?php

namespace App\Controllers;

/**
 * Class {$name}
 *
 * A resource controller with standard CRUD methods.
 */
class {$name}
{
    public function index()
    {
        // List all resources
    }

    public function show(\$id)
    {
        // Show a specific resource
    }

    public function store()
    {
        // Create a new resource
    }

    public function update(\$id)
    {
        // Update an existing resource
    }

    public function destroy(\$id)
    {
        // Delete a resource
    }
}
PHP;
    }
}

?>