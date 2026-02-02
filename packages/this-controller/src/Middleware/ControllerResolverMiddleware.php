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
        if ($context->getResponse() !== null || $context->isCli()) {
            $next($context);

            return;
        }

        $handler = $context->getContainer()->get(id: $context->getRoute()->getHandler());

        if (!$handler) {
            throw new \RuntimeException('No route handler found');
        }

        $pathParts = explode('/', trim($context->getRoute()->getPath(), '/'));
        $action = $pathParts[1] ?? 'index';

        $pathParameters = [];

        if (str_starts_with($action, '{') && str_ends_with($action, '}')) {
            $pathParameters = $context->getRequest()->getPathParameters();

            $action = array_shift($pathParameters);
        }

        $reflection = new \ReflectionClass($handler);

        if (!$reflection->hasMethod($action)) {
            $next($context);

            return;
        }

        $method = $reflection->getMethod($action);

        if (!$method->isPublic()) {
            $next($context);

            return;
        }

        $context->setResponse(
            new Response(
                statusCode: 200,
                content: (new ControllerTarget(
                    static fn () => $handler->{$action}(...$pathParameters)
                ))->execute(),
                headers: [],
            ),
        );

        $next($context);
    }
}
