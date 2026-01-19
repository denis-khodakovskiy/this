<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

namespace This\i18n\Translator;

use This\i18n\Contracts\TranslationFilePathResolverInterface;

class TranslationFilePathResolver implements TranslationFilePathResolverInterface
{
    public function getFilePathForLanguage(
        string $translationDirectoryPath,
        string $locale,
        string $category
    ): ?string {
        $path = implode(DIRECTORY_SEPARATOR, [
            $translationDirectoryPath,
            $locale,
            $category . '.php',
        ]);

        return file_exists($path)
            ? $path
            : null;
    }
}
