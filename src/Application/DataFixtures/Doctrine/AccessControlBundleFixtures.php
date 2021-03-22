<?php

namespace ukickeru\AccessControlBundle\Application\DataFixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

class AccessControlBundleFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User(
            'user',
            'user123456',
            ['user']
        );
        $user->updatePassword($this->passwordEncoder->encodePassword($user, 'user123456'));
        $manager->persist($user);

        $userGroup = new Group(
            'Пользователи',
            $user
        );
        $userGroup->addUser($user);
        $manager->persist($userGroup);

        $manager->flush();

        $admin = new User(
            'admin',
            'admin123456',
            ['user', 'admin']
        );
        $admin->updatePassword($this->passwordEncoder->encodePassword($admin, 'admin123456'));
        $admin->setAdmin(true);
        $manager->persist($admin);

        $adminGroup = new Group(
            'Администраторы',
            $admin,
            $userGroup
        );
        $adminGroup->addUser($admin);
        $manager->persist($adminGroup);
        
        $manager->flush();
    }
}
