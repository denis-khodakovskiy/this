<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\i18n\Translator;

use This\i18n\Config\TranslatorConfig;
use This\i18n\Contracts\LocaleProviderInterface;
use This\i18n\Contracts\TranslationFilePathResolverInterface;
use This\i18n\Contracts\TranslatorInterface;

final readonly class Translator implements TranslatorInterface
{
    public function __construct(
        public TranslatorConfig $config,
        public TranslationFilePathResolverInterface $filePathResolver,
        private LocaleProviderInterface $localeProvider,
    ) {
    }

    public function translate(
        string $message,
        array $parameters = [],
        string $category = 'app',
    ): string {
        $translationsFilePath = $this->filePathResolver->getFilePathForLanguage(
            translationDirectoryPath: $this->config->translationsDirectoryPath,
            locale: $this->localeProvider->getLocale() ?? $this->config->defaultLocale,
            category: $category,
        );

        if (
            !$translationsFilePath
            || !fopen($translationsFilePath, 'r')
        ) {
            return $this->getMessage(message: $message, parameters: $parameters);
        }

        $translations = require_once $translationsFilePath;

        if (!isset($translations[$message])) {
            return $this->getMessage(message: $message, parameters: $parameters);
        }

        return $this->getMessage(message: $translations[$message], parameters: $parameters);
    }

    public function getConfig(): TranslatorConfig
    {
        return $this->config;
    }

    private function getMessage(string $message, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $message = str_replace('{'.$key.'}', (string)$value, $message);
        }

        return $message;
    }
}
