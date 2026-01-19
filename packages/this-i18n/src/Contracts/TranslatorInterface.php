<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

namespace This\i18n\Contracts;

use This\i18n\Config\TranslatorConfig;

interface TranslatorInterface
{
    public function translate(
        string $message,
        array $parameters = [],
        string $category = 'app',
    ): string;

    public function getConfig(): TranslatorConfig;
}
