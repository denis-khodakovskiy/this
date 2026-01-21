<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use This\Contracts\ContainerInterface;
use This\Validator\Pipeline\ValidationPipeline;
use This\Validator\Pipeline\ValidationPipelineInterface;

return static function (ContainerInterface $container): void {
    $container
        ->singleton(id: ValidationPipelineInterface::class, definition: static fn () => new ValidationPipeline())
    ;
};
