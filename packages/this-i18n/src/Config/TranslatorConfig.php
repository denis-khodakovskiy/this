<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\i18n\Config;

final class TranslatorConfig
{
    public function __construct(
        public string $defaultLocale = 'en',
        public string $translationsDirectoryPath = 'translations',
    ) {
    }
}
