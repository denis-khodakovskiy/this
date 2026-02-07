<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\i18n\Container;

use This\Contracts\ContainerInterface;
use This\Contracts\KernelConfigProviderInterface;
use This\i18n\Config\TranslatorConfig;
use This\i18n\Contracts\LocaleProviderInterface;
use This\i18n\Contracts\TranslationFilePathResolverInterface;
use This\i18n\Contracts\TranslatorInterface;
use This\i18n\Locale\LocaleProvider;
use This\i18n\Middleware\TranslationMiddleware;
use This\i18n\Translator\TranslationFilePathResolver;
use This\i18n\Translator\Translator;

final class I18nContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container): void {
            $container
                ->bind(
                    id: TranslationFilePathResolverInterface::class,
                    definition: static fn () => new TranslationFilePathResolver(),
                    priority: 100,
                )
                ->singleton(id: LocaleProviderInterface::class, definition: static fn() => new LocaleProvider(), priority: 100)
                ->singleton(
                    id: TranslatorInterface::class,
                    definition: static fn () => new Translator(
                        new TranslatorConfig(
                            defaultLocale: $container->get(id: KernelConfigProviderInterface::class)
                                ->getConfig()
                                ->getDefaultLocale(),
                            translationsDirectoryPath: $container->get(KernelConfigProviderInterface::class)
                                ->getConfig()
                                ->path('%app%') . '/translations',
                        ),
                        $container->get(TranslationFilePathResolverInterface::class),
                        $container->get(id: LocaleProviderInterface::class)
                    ),
                    priority: 100,
                )
                ->bind(id: TranslationMiddleware::class, definition: static fn () => new TranslationMiddleware(), priority: 100)
            ;
        };
    }
}
