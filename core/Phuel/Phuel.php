<?php
/**
 * --------------------------------------------------------------------------
 *  File: core/Phuel/Phuel.php
 *  --------------------------------------------------------------------------
 *  Main Phuel templating engine class.
 *  Compiles and renders .phuel.php templates.
 */

namespace Phuelone\Phuel;

use Core\Support\Filesystem;
use Core\Phuel\Compiler;
use Parsedown;

class Phuel
{
    /**
     * Path to the views directory.
     *
     * @var string
     */
    protected string $viewsPath;

    /**
     * Compiler instance.
     *
     * @var Compiler
     */
    protected Compiler $compiler;

    /**
     * Path to the cache directory.
     *
     * @var string
     */
    protected string $cachePath;

    /**
     * Variables to pass to the view.
     *
     * @var array
     */
    protected array $data = [];

    // Debugging support
    protected bool $debugMode = false;

    /**
     * Constructor.
     *
     * @param string $viewsPath
     * @param string $cachePath
     */
    public function __construct(string $viewsPath, string $cachePath)
    {
        $this->compiler = new Compiler();
        $this->registerDirectives();
        $this->viewsPath = rtrim($viewsPath, DIRECTORY_SEPARATOR);
        $this->cachePath = rtrim($cachePath, DIRECTORY_SEPARATOR);
    }

    /**
     * Enable debug mode.
     *
     * @return void
     */
    public function enableDebugMode(): void
    {
        $this->debugMode = true;
    }

    /**
     * Render a template with variables.
     *
     * @param string $view  Dot notation (e.g., 'admin.dashboard')
     * @param array  $data Variables to inject.
     * @return string
     */
    public function render(string $view, array $data = []): void
    {
        try {
            $compiler = new Compiler();
            $templateFile = base_path("views/{$view}.phuel.php");
            $compiledFile = base_path("storage/phuel/cache/" . md5($view) . ".php");

            if (!file_exists($compiledFile) || filemtime($templateFile) > filemtime($compiledFile)) {
                $compiler->compileFile($templateFile, $compiledFile);
            }

            extract($data);
            include $compiledFile;
        } catch (\Exception $e) {
            if ($this->debugMode) {
                echo "<strong>Template Error:</strong> " . $e->getMessage();
            } else {
                throw $e;
            }
        }
    }

    /**
     * Get the path to the compiled template file.
     *
     * @param string $view
     * @return string
     */
    protected function getCompiledFilePath(string $view): string
    {
        $compiledName = str_replace(['.', '\\'], '_', $view) . '.php';
        return $this->cachePath . DIRECTORY_SEPARATOR . $compiledName;
    }

    protected function compile(string $templateFile, string $compiledFile): void
    {
        $content = file_get_contents($templateFile);

        // 1. Remove comments {{-- comment --}}
        $content = preg_replace('/\{\{\-\-(.*?)\-\-\}\}/s', '', $content);

        // 2. Check for @extends('parent')
        if (preg_match('/@extends\(\'([^\']+)\'\)/', $content, $matches)) {
            $parentView = $matches[1];
            $content = preg_replace('/@extends\(\'([^\']+)\'\)/', '', $content);

            // Extract sections
            $sections = [];
            preg_match_all('/@section\(\'([^\']+)\'\)(.*?)@endsection/s', $content, $secMatches, PREG_SET_ORDER);
            foreach ($secMatches as $sec) {
                $sections[$sec[1]] = trim($sec[2]);
            }

            // Remove sections from child content (so leftover content doesn’t get output)
            $content = preg_replace('/@section\(\'[^\']+\'\)(.*?)@endsection/s', '', $content);

            // Load parent template file
            $parentFile = $this->viewsPath . DIRECTORY_SEPARATOR . str_replace(['.', '\\'], DIRECTORY_SEPARATOR, $parentView) . '.phuel.php';
            if (!file_exists($parentFile)) {
                throw new \Exception("Parent view '{$parentView}' not found.");
            }
            $parentContent = file_get_contents($parentFile);

            // Replace @yield('section') in parent with section content or empty string
            $parentContent = preg_replace_callback('/@yield\(\'([^\']+)\'\)/', function ($yieldMatches) use ($sections) {
                $sectionName = $yieldMatches[1];
                return $sections[$sectionName] ?? '';
            }, $parentContent);

            // The final content is parent content with yielded sections replaced
            $content = $parentContent;
        }

        // 3. Variable echoes, control structures etc. (same as before)

        // Echo escaped: {{ variable }}
        $content = preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/s', function ($matches) {
            return '<?= htmlspecialchars(' . $matches[1] . ', ENT_QUOTES, \'UTF-8\') ?>';
        }, $content);

        // Echo raw: {!! variable !!}
        $content = preg_replace_callback('/\{\!\!\s*(.+?)\s*\!\!\}/s', function ($matches) {
            return '<?= ' . $matches[1] . ' ?>';
        }, $content);

        // Control structures
        $replacements = [
            '/@if\s*\((.+)\)/'       => '<?php if ($1): ?>',
            '/@elseif\s*\((.+)\)/'   => '<?php elseif ($1): ?>',
            '/@else/'                => '<?php else: ?>',
            '/@endif/'               => '<?php endif; ?>',
            '/@foreach\s*\((.+)\)/'  => '<?php foreach ($1): ?>',
            '/@endforeach/'          => '<?php endforeach; ?>',
            '/@for\s*\((.+)\)/'      => '<?php for ($1): ?>',
            '/@endfor/'              => '<?php endfor; ?>',
            '/@while\s*\((.+)\)/'    => '<?php while ($1): ?>',
            '/@endwhile/'            => '<?php endwhile; ?>',
        ];

        foreach ($replacements as $pattern => $replace) {
            $content = preg_replace($pattern, $replace, $content);
        }

        // Save compiled PHP to cache file
        Filesystem::put($compiledFile, $content);
    }

    // Register Directives
    protected function registerDirectives(): void
    {
        $this->compiler->directive('datetime', function ($expression) {
            return "<?php echo date('F j, Y', strtotime($expression)); ?>";
        });

        $this->compiler->directive('markdown', function ($expression) {
            return "<?php echo (new Parsedown)->text($expression); ?>";
        });

        // Add more directives here as needed
    }

    // Internationalization (i18n) support
    protected function translate(string $key, array $params = []): string
    {
        $translations = include base_path('lang/en.php'); // Example path
        $translation = $translations[$key] ?? $key;

        foreach ($params as $param => $value) {
            $translation = str_replace(':' . $param, $value, $translation);
        }

        return $translation;
    }

    // Partial template inclusion
    public function includePartial(string $partial, array $data = []): string
    {
        $partialFile = $this->viewsPath . DIRECTORY_SEPARATOR . $partial . '.phuel.php';
        if (!file_exists($partialFile)) {
            throw new \Exception("Partial view '{$partial}' not found.");
        }

        extract($data);
        ob_start();
        include $partialFile;
        return ob_get_clean();
    }
}