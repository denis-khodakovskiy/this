<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Container;

use This\Contracts\ContainerInterface;
use This\Validator\Pipeline\ValidationPipeline;
use This\Validator\Pipeline\ValidationPipelineInterface;
use This\Validator\Validator\Validator;
use This\Validator\Validator\ValidatorInterface;

final class ValidatorContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container): void {
            $container
                ->singleton(id: ValidationPipelineInterface::class, definition: static fn () => new ValidationPipeline())
                ->bind(id: ValidatorInterface::class, definition: static fn () => new Validator())
            ;
        };
    }
}
