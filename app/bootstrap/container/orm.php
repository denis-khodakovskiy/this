<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Handlers\ORMHandler;
use This\Contracts\ContainerInterface;
use This\ORM\Compiler\QueryCompiler;
use This\ORM\Compiler\ExpressionCompiler;
use This\ORM\ORM;
use This\ORM\Transport\MySQLTransport;

return static function (ContainerInterface $container) {
    $container
        ->singleton(id: ORM::class, definition: static fn () => new ORM(
            queryCompiler: new QueryCompiler(
                expressionCompiler: new ExpressionCompiler(),
            ),
            transport: new MySQLTransport(),
        ))
        ->bind(id: ORMHandler::class, definition: static fn (ContainerInterface $container) => new ORMHandler(
            $container->get(id: ORM::class),
        ))
    ;
};
