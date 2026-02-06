<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Error;

use App\This\Core\Kernel\KernelConfigProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\ContextInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Contracts\KernelConfigProviderInterface;

final readonly class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @throws \Throwable
     */
    public function handle(\Throwable $exception, ContextInterface $context): void
    {
        echo match (true) {
            $context->isCli() => $exception->getMessage(),
            $context->isHttp() => $this->renderHtml($exception, $context),
            default => sprintf(
                'An error occurred in %s:%d: %s',
                $exception->getFile(),
                $exception->getLine(),
                $exception->getMessage()
            ),
        } . PHP_EOL;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function renderHtml(\Throwable $exception, ContextInterface $context): string
    {
        /** @var KernelConfigProvider $configProvider */
        $configProvider = $context->getContainer()->get(id: KernelConfigProviderInterface::class);

        $exceptionFrame = [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'code' => $this->extractCodeSnippet(
                $exception->getFile(),
                $exception->getLine()
            ),
        ];

        $traceFrames = [];

        foreach ($exception->getTrace() as $row) {
            if (empty($row['file']) || empty($row['line'])) {
                continue;
            }

            $traceFrames[] = [
                'class' => $row['class'] ?? null,
                'function' => $row['function'] ?? null,
                'file' => $row['file'],
                'line' => $row['line'],
                'code' => $this->extractCodeSnippet(
                    $row['file'],
                    $row['line']
                ),
            ];
        }

        ob_start();
        require_once $configProvider->getConfig()->path('%app%') . '/This/Core/templates/error.html.php';

        return ob_get_clean();
    }

    private function extractCodeSnippet(string $file, int $line, int $radius = 5): array
    {
        if (!is_readable($file)) {
            return [];
        }

        $fileObject = new \SplFileObject($file);
        $fileObject->setFlags(\SplFileObject::DROP_NEW_LINE);

        $start = max(1, $line - $radius);
        $end   = $line + $radius;

        $fileObject->seek($start - 1);

        $result = [];

        for ($i = $start; $i <= $end && !$fileObject->eof(); $i++) {
            $result[] = [
                'number'    => $i,
                'content'   => $fileObject->current(),
                'highlight' => $i === $line,
            ];
            $fileObject->next();
        }

        return $result;
    }
}
