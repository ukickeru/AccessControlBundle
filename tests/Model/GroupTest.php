<?php

namespace Components\AccessControl\Tests;

use App\Util\Calculator;
use PHPUnit\Framework\TestCase;
use Components\AccessControl\Model\Group;
use ukickeru\AccessControlBundle\UseCase\AccessControlUseCase;

class GroupTest extends TestCase
{
    public function testUserCreation()
    {
        $group = new Group();

        $this->assertSame('test', $user->getName());
    }
}
