<?php

namespace ukickeru\AccessControlBundle\Application\DataFixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ukickeru\AccessControlBundle\Model\Fixtures\Fixtures;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

class AccessControlBundleFixtures extends Fixture
{

    private $fixtures;

    private $passwordEncoder;

    public function __construct(
        Fixtures $fixtures,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->fixtures = $fixtures;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $fixturesDTO = $this->fixtures->getDataFixturesToPersist();


        $user = $fixturesDTO->getUser();
        $user->updatePassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $manager->persist($user);

        $userGroup = $fixturesDTO->getUserGroup();
        $manager->persist($userGroup);

        $manager->flush();


        $admin = $fixturesDTO->getUser();
        $admin->updatePassword($this->passwordEncoder->encodePassword($admin, $admin->getPassword()));
        $manager->persist($admin);

        $adminGroup = $fixturesDTO->getAdminGroup();
        $manager->persist($adminGroup);
        
        $manager->flush();
    }
}
