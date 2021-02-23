<?php

namespace Components\AccessControl\Tests;

use App\Util\Calculator;
use PHPUnit\Framework\TestCase;
use Components\AccessControl\Model\User;

class GroupTest extends TestCase
{
    public function testUserCreation()
    {
        $user = new User();

        $this->assertSame('test', $user->getName());
    }
}
