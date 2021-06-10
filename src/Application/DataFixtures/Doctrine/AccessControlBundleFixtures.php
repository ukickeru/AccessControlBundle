<?php

namespace ukickeru\AccessControlBundle\Application\DataFixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
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
        $user->updatePassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $manager->persist($user);

        $userGroup = new Group(
            'Пользователи',
            $user
        );
        $userGroup->addAvailableRoutes(ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES);
        $userGroup->addUser($user);
        $manager->persist($userGroup);

        $manager->flush();

        $admin = new User(
            'admin',
            'admin123456',
            ['user', 'admin']
        );
        $admin->setAdmin(true);
        $admin->updatePassword($this->passwordEncoder->encodePassword($admin, $admin->getPassword()));
        $manager->persist($admin);

        $adminGroup = new Group(
            'Администраторы',
            $admin,
        );
        $adminGroup->setParentGroup($userGroup);
        $adminGroup->addAvailableRoutes(ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES_FOR_ADMIN);
        $adminGroup->addUser($admin);
        $manager->persist($adminGroup);

        $manager->flush();
    }
}
