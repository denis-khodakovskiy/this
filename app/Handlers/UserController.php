<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers;

final class UserController
{
    public function list(): string
    {
        throw new \Exception('Blah blah blah', 500);

        return 'user list';
    }
}
