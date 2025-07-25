<?php
// core/Phuel/Compiler.php

namespace Core\Phuel;

use Core\Support\Filesystem;

/**
 * Compiler class responsible for parsing and compiling Phuel templates into raw PHP.
 */
class Compiler
{
    /**
     * Compile a template file to raw PHP and write it to the compiled file.
     *
     * @param string $templateFile
     * @param string $compiledFile
     * @return void
     */
    public function compileFile(string $templateFile, string $compiledFile): void
    {
        $raw = file_get_contents($templateFile);
        $compiled = $this->compile($raw);
        Filesystem::put($compiledFile, $compiled);
    }

    /**
     * Compile raw template content to PHP.
     *
     * @param string $content
     * @return string
     */
    public function compile(string $content): string
    {
        $content = $this->compileComments($content);
        $content = $this->compileIncludes($content);
        $content = $this->compileEchos($content);
        $content = $this->compileRawEchos($content);
        $content = $this->compileStatements($content);
        $content = $this->compileCustomDirectives($content);
        $content = $this->compileMarkdownBlocks($content);
        return $content;
    }

    protected function compileComments(string $content): string
    {
        return preg_replace('/\{\{\-\-(.*?)\-\-\}\}/s', '', $content);
    }

    protected function compileEchos(string $content): string
    {
        return preg_replace_callback('/\{\{\s*(.+?)\s*\}\}/s', function ($matches) {
            return '<?= htmlspecialchars(' . $matches[1] . ', ENT_QUOTES, \'UTF-8\') ?>';
        }, $content);
    }

    protected function compileRawEchos(string $content): string
    {
        return preg_replace_callback('/\{\!\!\s*(.+?)\s*\!\!\}/s', function ($matches) {
            return '<?= ' . $matches[1] . ' ?>';
        }, $content);
    }

    protected function compileStatements(string $content): string
    {
        $patterns = [
            '/@if\s*\((.+?)\)/'       => '<?php if ($1): ?>',
            '/@elseif\s*\((.+?)\)/'   => '<?php elseif ($1): ?>',
            '/@else/'                 => '<?php else: ?>',
            '/@endif/'                => '<?php endif; ?>',
            '/@foreach\s*\((.+?)\)/'  => '<?php foreach ($1): ?>',
            '/@endforeach/'           => '<?php endforeach; ?>',
            '/@for\s*\((.+?)\)/'      => '<?php for ($1): ?>',
            '/@endfor/'               => '<?php endfor; ?>',
            '/@while\s*\((.+?)\)/'    => '<?php while ($1): ?>',
            '/@endwhile/'             => '<?php endwhile; ?>',
        ];

        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        return $content;
    }

    protected function compileIncludes(string $content): string
    {
        return preg_replace_callback('/@include\([\'"](.+?)[\'"]\)/', function ($matches) {
            $viewPath = $this->resolveViewPath($matches[1]);
            return "<?php include '$viewPath'; ?>";
        }, $content);
    }

    protected function resolveViewPath(string $view): string
    {
        $path = str_replace('.', '/', $view);
        return base_path("views/{$path}.phuel.php");
    }

    /**
 * Array of custom directives.
 *
 * @var array<string, callable>
 */
protected array $customDirectives = [];

/**
 * Register a custom directive.
 *
 * @param string $name
 * @param callable $callback
 * The callback receives the expression (like "$post->created_at")
 */
public function directive(string $name, callable $callback): void
{
    $this->customDirectives[$name] = $callback;
}

/**
 * Compile all custom directives in the template.
 */
protected function compileCustomDirectives(string $content): string
{
    foreach ($this->customDirectives as $name => $handler) {
        $pattern = "/@{$name}\((.*?)\)/";
        $content = preg_replace_callback($pattern, function ($matches) use ($handler) {
            return $handler(trim($matches[1]));
        }, $content);
    }

    return $content;
  }

    /**
 * Compile markdown blocks.
 */
protected function compileMarkdownBlocks(string $content): string
{
    return preg_replace_callback(
        '/@markdown(.*?)@endmarkdown/s',
        function ($matches) {
            $markdownContent = trim($matches[1]);
            return "<?php echo (new Parsedown)->text(<<<'MD'\n{$markdownContent}\nMD\n); ?>";
        },
        $content
    );
}
}

?>