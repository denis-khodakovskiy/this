<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Asset;

class AbstractAsset implements AssetInterface
{
    /** @var array<array-key, non-empty-string> */
    protected array $css = [];

    /** @var array<array-key, non-empty-string> */
    protected array $js = [];

    /** @var array<array-key, class-string> */
    protected array $depends = [];

    public function css(): array
    {
        return $this->css;
    }

    public function js(): array
    {
        return $this->js;
    }

    public function depends(): array
    {
        return $this->depends;
    }
}
