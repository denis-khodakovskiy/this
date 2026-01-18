<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Execution;

use App\This\Core\Enums\RequestMethodsEnum;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use This\Contracts\ContextInterface;
use This\Contracts\MiddlewareInterface;

final class ExecutionMiddleware implements MiddlewareInterface
{
    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ContextInterface $context, callable $next): void
    {
        $handler = $context->getContainer()->get(id: $context->getRoute()->getHandler());

        if (!$handler) {
            throw new \RuntimeException('No route handler found');
        }

        $requestDto = null;
        $request = $context->getRequest();

        if ($context->getRoute()->getRequestFQCN()) {
            $serializer = new Serializer([new ObjectNormalizer()]);

            $requestDto = $serializer->denormalize(
                match (true) {
                    $context->isCli() => $request->getAttributes(),
                    $request->getMethod() === RequestMethodsEnum::GET => $request->get(),
                    $request->getMethod() === RequestMethodsEnum::POST => $request->post(),
                    default => $request->getBodyParameters(),
                },
                $context->getRoute()->getRequestFQCN(),
            );
        }

        call_user_func_array(
            $handler,
            $requestDto
                ? [...array_values($request->getPathParameters()), $requestDto]
                : array_values($request->getPathParameters()),
        );
    }
}
