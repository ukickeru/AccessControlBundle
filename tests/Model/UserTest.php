<?php

namespace Components\AccessControl\Tests;

use App\Util\Calculator;
use PHPUnit\Framework\TestCase;
use Components\AccessControl\Model\User;
use ukickeru\AccessControlBundle\UseCase\AccessControlUseCase;

class UserTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User();

        $this->assertSame('test', $user->getName());
    }
}
