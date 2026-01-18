<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\User;

use App\Handlers\Dto\CreateUserRequestDto;
use App\Services\MyServiceInterface;
use This\Validator\Constraint\MaxValue;
use This\Validator\Constraint\MinValue;
use This\Validator\FieldType\IntType;
use This\Validator\Validator\ScalarValidator;

final readonly class CreateUserHandler
{
    public function __construct(
        private MyServiceInterface $myService,
    ) {
    }

    public function __invoke(CreateUserRequestDto $createUserRequestDto): void
    {
        var_dump(ScalarValidator::validate(
            value: 100,
            fieldType: new IntType(),
            constraints: [
                new MinValue(
                    min: 150,
                    message: fn () => $this->myService->translate(
                        message: 'Bla bla bla {min} shit!',
                        params: ['{min}' => 150],

                    )
                ),
                new MaxValue(max: 1000),
            ],
        ));
    }
}
