<?php

namespace ukickeru\AccessControlBundle\Model\Fixtures;

use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

class Fixtures
{

    public function getDataFixturesToPersist(): FixturesDTO
    {
        $user = new User(
            'user',
            'user123456',
            ['user']
        );

        $userGroup = new Group(
            'Пользователи',
            $user
        );
        $userGroup->addUser($user);

        $admin = new User(
            'admin',
            'admin123456',
            ['user', 'admin']
        );
        $admin->setAdmin(true);

        $adminGroup = new Group(
            'Администраторы',
            $admin,
            $userGroup
        );
        $adminGroup->addUser($admin);
        $adminGroup->setParentGroup($userGroup);

        return new FixturesDTO(
            $user,
            $userGroup,
            $admin,
            $adminGroup
        );
    }

}