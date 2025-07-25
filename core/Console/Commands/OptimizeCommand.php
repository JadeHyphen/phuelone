<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Console/Commands/OptimizeCommand.php
 *  --------------------------------------------------------------------------
 *  Command to optimize the application by caching config, views, and clearing caches.
 */

namespace Phuelone\Console\Commands;

use Phuelone\Console\Command;
use Phuelone\Support\Filesystem;
use Core\Cache\RedisCache;

/**
 * Class OptimizeCommand
 *
 * Caches config files, compiles views, and clears old cache files.
 */
class OptimizeCommand extends Command
{
    /**
     * Command signature.
     *
     * @var string
     */
    protected string $signature = 'optimize';

    /**
     * Execute the optimization process.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Starting optimization...');

        $this->cacheConfig();
        $this->compileViews();
        $this->clearOldCache();

        $this->success('Optimization complete.');
    }

    /**
     * Cache configuration files into a single cache file.
     *
     * @return void
     */
    protected function cacheConfig(): void
    {
        $this->info('Loading configuration file...');
        $config = require __DIR__ . '/../../../config/app.php';
        $this->info('Configuration file loaded.');

        $this->info('Initializing RedisCache...');
        $cache = new RedisCache();
        $this->info('RedisCache initialized.');

        $this->info('Setting cache for app_config...');
        $cache->set('app_config', json_encode($config));
        $this->info('Cache set for app_config.');
    }

    /**
     * Compile Phuel views into PHP for faster rendering.
     *
     * @return void
     */
    protected function compileViews(): void
    {
        $viewsPath = __DIR__ . '/../../../app/Views';
        $compiledPath = __DIR__ . '/../../../storage/views';

        Filesystem::compileViews($viewsPath, $compiledPath);
        $this->info('Compiling views...');
    }

    /**
     * Clear old cache, compiled files, and temporary data.
     *
     * @return void
     */
    protected function clearOldCache(): void
    {
        $cache = new RedisCache();
        $cache->delete('app_config');
        Filesystem::clearDirectory(__DIR__ . '/../../../storage/cache');
        $this->info('Clearing old cache files...');
    }
}

namespace Phuelone\Support;

class Filesystem
{
    public static function compileViews(string $viewsPath, string $compiledPath): void
    {
        // Logic to compile views
    }

    public static function clearDirectory(string $directoryPath): void
    {
        // Logic to clear directory
    }
}

require_once __DIR__ . '/../../../core/Cache/RedisCache.php';

?>