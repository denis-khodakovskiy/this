<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Handlers\TestHandler;
use This\Contracts\ContainerInterface;
use This\i18n\Contracts\TranslatorInterface;

return static function (ContainerInterface $container): void {
    $container
        ->bind(id: TestHandler::class, definition: static fn (ContainerInterface $container) => new TestHandler(
            $container->get(id: TranslatorInterface::class),
        ))
    ;
};
