<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

use App\This\Core\Kernel\Context;

interface ExceptionHandlerInterface
{
    public function handle(\Throwable $exception, Context $context): void;
}
