<?php

namespace ukickeru\AccessControlBundle\Tests\Model;

use PHPUnit\Framework\TestCase;
use ukickeru\AccessControl\Model\Group;
use ukickeru\AccessControl\Model\User;

class UserTest extends TestCase
{
    public const AVAILABLE_ROLES = [
        'role1',
        'role2'
    ];

    public static function createUserForTest(): User
    {
        $name = 'test';
        $password = '12345678';

        $groupsCreator = new User('test','12345678');
        $groups = [
            new Group('test1', $groupsCreator),
            new Group('test1', $groupsCreator)
        ];

        return new User(
            $name,
            $password,
            self::AVAILABLE_ROLES,
            $groups
        );
    }

    public function testGroupCreation()
    {
        $name = 'test';
        $password = '12345678';

        $groupsCreator = new User('test','12345678');
        $groups = [
            new Group('test1', $groupsCreator),
            new Group('test1', $groupsCreator)
        ];

        $user = new User(
            $name,
            $password,
            self::AVAILABLE_ROLES,
            $groups
        );

        $this->assertSame($name, $user->getName());
        $this->assertSame($password, $user->getPassword());
        $this->assertNotSame(self::AVAILABLE_ROLES, $user->getRoles());
        $this->assertSame(
            array_merge(self::AVAILABLE_ROLES,[User::DEFAULT_ROLE]),
            $user->getRoles()
        );
    }

    public function testAddingRoles()
    {
        $user = self::createUserForTest();

        $user->removeAllRoles();

        $this->assertSame(
            [User::DEFAULT_ROLE],
            $user->getRoles()
        );

        $user->addRole('test3');

        $this->assertSame(
            array_merge(['test3'],[User::DEFAULT_ROLE]),
            $user->getRoles()
        );

        $user->setRoles(['test4', 'test5']);

        $this->assertSame(
            ['test4', 'test5', User::DEFAULT_ROLE],
            $user->getRoles()
        );
    }

    public function testRemovingRoles()
    {
        $user = self::createUserForTest();

        $user->removeAllRoles();

        $this->assertSame(
            [User::DEFAULT_ROLE],
            $user->getRoles()
        );

        $user->setRoles(['test1', 'test2', 'test3']);

        $this->assertSame(
            array_merge(['test1', 'test2', 'test3'],[User::DEFAULT_ROLE]),
            $user->getRoles()
        );

        $user->removeRole('test1');

        $this->assertEquals(
            [
                1 => 'test2',
                2 => 'test3',
                3 => User::DEFAULT_ROLE
            ],
            $user->getRoles()
        );
    }
}
