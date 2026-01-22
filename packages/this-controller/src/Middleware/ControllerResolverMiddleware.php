<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Controller\Middleware;

use App\This\Core\Response\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\ContextInterface;
use This\Controller\Target\ControllerTarget;

final class ControllerResolverMiddleware
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    public function __invoke(ContextInterface $context, callable $next): void
    {
        if ($context->getResponse() !== null) {
            $next($context);

            return;
        }

        $handler = $context->getContainer()->get(id: $context->getRoute()->getHandler());

        if (!$handler) {
            throw new \RuntimeException('No route handler found');
        }

        $pathParts = explode('/', trim($context->getRoute()->getPath(), '/'));
        $action = $pathParts[1] ?? 'index';

        $reflection = new \ReflectionClass($handler);
        if (!$reflection->hasMethod($action)) {
            $next($context);

            return;
        }

        $method = $reflection->getMethod($action);
        if (!$method->isPublic()) {
            throw new \RuntimeException(sprintf(
                'Method %s::%s() needs to be public',
                $handler::class,
                $action,
            ));
        }

        $context->setResponse(
            new Response(
                statusCode: 200,
                content: (new ControllerTarget(
                    static fn () => $handler->{$action}()
                ))->execute(),
                headers: [],
            ),
        );

        $next($context);
    }
}
