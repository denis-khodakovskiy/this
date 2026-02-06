<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use This\Contracts\ContainerInterface;
use This\Contracts\EnvContainerInterface;
use This\ORM\Compiler\ExpressionCompiler;
use This\ORM\Compiler\QueryCompiler;
use This\ORM\Hydrator\Hydrator;
use This\ORM\Hydrator\HydratorInterface;
use This\ORM\ORM;
use This\ORM\ORMInterface;
use This\ORM\Repository\RepositoryContext;
use This\ORM\Repository\RepositoryContextInterface;
use This\ORM\Transport\MySQLTransport;
use This\ORM\Transport\TransportInterface;

return static function (ContainerInterface $container): void {
    $container
        ->bind(id: TransportInterface::class, definition: static fn () => new MySQLTransport())
        ->singleton(id: ORMInterface::class, definition: static function (ContainerInterface $container): ORM {
            /** @var EnvContainerInterface $env */
            $env = $container->get(id: EnvContainerInterface::class);

            return new ORM(
                queryCompiler: new QueryCompiler(
                    expressionCompiler: new ExpressionCompiler(),
                ),
                transport: $container->get(id: TransportInterface::class),
                pdo: new PDO(
                    sprintf(
                        '%s:host=%s;dbname=%s;charset=%s',
                        $env->get('DB_DRIVER', 'mysql'),
                        $env->get('DB_HOST'),
                        $env->get('DB_DATABASE'),
                        $env->get('DB_CHARSET', 'utf8mb4'),
                    ),
                    $env->get('DB_USER'),
                    $env->get('DB_PASSWORD'),
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ],
                ),
            );
        })
        ->bind(
            id: RepositoryContextInterface::class,
            definition: static fn (ContainerInterface $container) => new RepositoryContext(
                $container->get(id: ORMInterface::class),
            ),
        )
        ->bind(id: HydratorInterface::class, definition: static fn () => new Hydrator())
    ;
};
