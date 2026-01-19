<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

namespace This\i18n\Contracts;

interface TranslationFilePathResolverInterface
{
    public function getFilePathForLanguage(
        string $translationDirectoryPath,
        string $locale,
        string $category
    ): ?string;
}
