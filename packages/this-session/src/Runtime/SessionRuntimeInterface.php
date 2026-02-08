<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\Runtime;

interface SessionRuntimeInterface
{
    public function boot(): void;
}
