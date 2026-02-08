<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\i18n\Middleware;

use This\Contracts\RequestContextInterface;
use This\Contracts\KernelConfigProviderInterface;
use This\Contracts\MiddlewareInterface;
use This\i18n\Contracts\LocaleProviderInterface;

class TranslationMiddleware implements MiddlewareInterface
{
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        $context->getContainer()->get(LocaleProviderInterface::class)->setLocale(
            $context->isCli()
                ? $this->getCliLocale($context)
                : $this->getHttpLocale($context)
        );

        $next($context);
    }

    private function getCliLocale(RequestContextInterface $context): string
    {
        /** @var KernelConfigProviderInterface $kernelConfigProvider */
        $kernelConfigProvider = $context->getContainer()->get(KernelConfigProviderInterface::class);
        $kernelConfig = $kernelConfigProvider->getConfig();
        $request = $context->getRequest();

        return $request->getAttribute(key: 'locale') ?? $kernelConfig->getDefaultLocale();
    }

    private function getHttpLocale(RequestContextInterface $context): string
    {
        /** @var KernelConfigProviderInterface $kernelConfigProvider */
        $kernelConfigProvider = $context->getContainer()->get(KernelConfigProviderInterface::class);
        $kernelConfig = $kernelConfigProvider->getConfig();
        $request = $context->getRequest();
        $languages = $request->server()['HTTP_ACCEPT_LANGUAGE'] ?? null;

        if (!$languages) {
            return $kernelConfig->getDefaultLocale();
        }

        $preferences = array_map(
            callback: static function (string $language) {
                if (!str_contains($language, ';')) {
                    return [
                        'code' => $language,
                        'weight' => 1,
                    ];
                }

                [$code, $weightQuery] = explode(';', $language);
                [, $weight] = explode('=', $weightQuery);

                return [
                    'code' => $code,
                    'weight' => (float) $weight,
                ];
            },
            array: explode(',', $languages),
        );

        if (!$preferences) {
            return $kernelConfig->getDefaultLocale();
        }

        usort($preferences, static fn (array $a, array $b) => $b['weight'] <=> $a['weight']);

        $language = array_shift($preferences);

        return $language['code'];
    }
}
