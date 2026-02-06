<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Infrastructure\Factories;

use App\This\Core\Request\Resolvers\CLIRequestResolver;
use App\This\Core\Request\Resolvers\HTTPRequestResolver;
use App\This\Core\Request\Resolvers\RequestResolversRegistry;
use This\Contracts\RequestResolversRegistryInterface;

final class RequestResolversRegistryFactory
{
    public function __invoke(): RequestResolversRegistryInterface
    {
        return new RequestResolversRegistry([
            new CLIRequestResolver(),
            new HTTPRequestResolver(),
        ]);
    }
}
