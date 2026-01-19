<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers;

use App\Forms\MyObject;
use This\Validator\Validator\ValidatorInterface;

final readonly class TestHandler
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(): void
    {
        $dto = new MyObject('f', 18, 'iuhgff');

        print_r($this->validator->validate($dto));
    }
}
