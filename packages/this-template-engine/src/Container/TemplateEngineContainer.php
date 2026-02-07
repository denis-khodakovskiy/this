<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Container;

use This\Contracts\ContainerInterface;
use This\Contracts\EnvContainerInterface;
use This\Contracts\RequestProviderInterface;
use This\TemplateEngine\Renderer\ClosureRenderer;
use This\TemplateEngine\Renderer\ComposedRenderer;
use This\TemplateEngine\Renderer\InlineRenderer;
use This\TemplateEngine\Renderer\ViewRendererRegistry;
use This\TemplateEngine\Renderer\ViewRendererRegistryInterface;
use This\TemplateEngine\TemplateEngine;
use This\TemplateEngine\TemplateEngineInterface;

final class TemplateEngineContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container) {
            $container
                ->bind(id: ViewRendererRegistryInterface::class, definition: static function (ContainerInterface $container) {
                    return (new ViewRendererRegistry())
                        ->registerRenderer(new ClosureRenderer())
                        ->registerRenderer(new ComposedRenderer(
                            $container->get(EnvContainerInterface::class)->get('VIEWS_PATH'),
                        ))
                        ->registerRenderer(new InlineRenderer())
                    ;
                }, priority: 100)
                ->singleton(id: TemplateEngineInterface::class, definition: static fn(ContainerInterface $container) => new TemplateEngine(
                    $container->get(ViewRendererRegistryInterface::class),
                    $container->get(RequestProviderInterface::class),
                ), priority: 100)
            ;
        };
    }
}
