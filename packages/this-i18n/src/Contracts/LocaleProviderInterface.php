<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\i18n\Contracts;

interface LocaleProviderInterface
{
    public function setLocale(string $locale): void;

    public function getLocale(): string;
}
