<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\i18n\Locale;

use This\i18n\Contracts\LocaleProviderInterface;

class LocaleProvider implements LocaleProviderInterface
{
    private ?string $locale = null;

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}
