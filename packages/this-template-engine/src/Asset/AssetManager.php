<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Asset;

final class AssetManager
{
    /** @var array<array-key, non-empty-string> */
    private array $assets = [];

    /** @var array<string, true> */
    private array $css = [];

    /** @var array<string, true> */
    private array $js = [];

    /** @var array<non-empty-string, bool> */
    private array $resolving;

    public function register(array $assetClasses): void
    {
        foreach ($assetClasses as $class) {
            $this->registerAsset($class);
        }
    }

    public function renderCss(): string
    {
        return implode("\n", array_map(
            fn($href) => "<link rel=\"stylesheet\" href=\"$href\">",
            array_keys($this->css)
        ));
    }

    public function renderJs(): string
    {
        return implode("\n", array_map(
            fn($src) => "<script src=\"$src\"></script>",
            array_keys($this->js)
        ));
    }

    private function registerAsset(string $class): void
    {
        if (isset($this->assets[$class])) {
            return;
        }

        if (isset($this->resolving[$class])) {
            throw new \RuntimeException("Circular asset dependency: $class");
        }

        $this->resolving[$class] = true;

        $asset = new $class();

        foreach ($asset->depends() as $dep) {
            $this->registerAsset($dep);
        }

        unset($this->resolving[$class]);

        $this->assets[$class] = $asset;

        foreach ($asset->css() as $css) {
            $this->css[$css] = true;
        }

        foreach ($asset->js() as $js) {
            $this->js[$js] = true;
        }
    }
}
