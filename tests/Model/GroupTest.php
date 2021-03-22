<?php

namespace ukickeru\AccessControlBundle\Tests\Model;

use DateTime;
use PHPUnit\Framework\TestCase;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControlBundle\Model\User;

class GroupTest extends TestCase
{
    public const AVAILABLE_ROUTES = [
        '/test1',
        '/test2'
    ];

    public static function createGroupForTest(): Group
    {
        $user = new User('test','12345678');
        $name = 'test';

        $user1 = new User('test','12345678');
        $user2 = new User('test','12345678');
        $users = [
            $user1,
            $user2
        ];

        return new Group(
            $name, $user, self::AVAILABLE_ROUTES, $users
        );
    }

    public function testGroupCreation()
    {
        $user = new User('test','12345678');
        $name = 'test';
        $creationDate = (new DateTime())->format('d.m.Y');

        $user1 = new User('test','12345678');
        $user2 = new User('test','12345678');
        $users = [
            $user1,
            $user2
        ];

        $group = new Group(
            $name, $user, self::AVAILABLE_ROUTES, $users
        );

        $this->assertSame($name, $group->getName());
        $this->assertSame($user, $group->getCreator());
        $this->assertSame($creationDate, $group->getCreationDate());
        $this->assertNotSame(self::AVAILABLE_ROUTES, $group->getAvailableRoutes());
        $this->assertSame(
            array_merge(self::AVAILABLE_ROUTES,ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES),
            $group->getAvailableRoutes()
        );
        $this->assertSame($users, $group->getUsers());
    }

    public function testAddingAvailableRoutesToGroup()
    {
        $group = self::createGroupForTest();

        $group->addAvailableRoute('test3');

        $this->assertSame(
            array_merge(self::AVAILABLE_ROUTES,['test3'],ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES),
            $group->getAvailableRoutes()
        );

        $group->addAvailableRoute('test3');

        $this->assertSame(
            array_merge(self::AVAILABLE_ROUTES,['test3'],ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES),
            $group->getAvailableRoutes()
        );
    }

    public function testRemovingAvailableRoutesToGroup()
    {
        $group = self::createGroupForTest();

        $group->removeAvailableRoute('/test1');

        $this->assertSame(
            array_merge(['/test2'],ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES),
            $group->getAvailableRoutes()
        );

        $group->removeAllAvailableRoutes();

        $this->assertSame(
            ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES,
            $group->getAvailableRoutes()
        );
    }

    public function testAddingUsersToGroup()
    {
        $group = self::createGroupForTest();

        $group->removeAllUsers();

        $user1 = new User('test1','12345678');
        $user2 = new User('test2','12345678');
        $user3 = new User('test3','12345678');

        $group->addUsers([
            $user1,
            $user2,
            $user3
        ]);

        $this->assertSame(
            [ $user1, $user2, $user3 ],
            $group->getUsers()
        );

        $user4 = new User('test4','12345678');

        $group->addUser($user4);

        $this->assertSame(
            [ $user1, $user2, $user3, $user4 ],
            $group->getUsers()
        );

        $user5 = new User('test5','12345678');
        $user6 = new User('test6','12345678');

        $group->setUsers([ $user5, $user6 ]);

        $this->assertSame(
            [ $user5, $user6 ],
            $group->getUsers()
        );
    }

    public function testRemovingUsersFromGroup()
    {
        $group = self::createGroupForTest();

        $group->removeAllUsers();

        $user1 = new User('test1','12345678');
        $user2 = new User('test2','12345678');
        $user3 = new User('test3','12345678');

        $group->addUsers([
            $user1,
            $user2,
            $user3
        ]);

        $this->assertSame(
            [ $user1, $user2, $user3 ],
            $group->getUsers()
        );

        $group->removeUser($user3);

        $this->assertSame(
            [ $user1, $user2 ],
            $group->getUsers()
        );

        $group->removeAllUsers();

        $this->assertSame([], $group->getUsers());
    }
}
