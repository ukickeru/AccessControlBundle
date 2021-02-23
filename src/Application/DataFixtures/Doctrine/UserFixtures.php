<?php

namespace ukickeru\AccessControlBundle\Application\DataFixtures\Doctrine;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use ukickeru\AccessControlBundle\Model\User;

class UserFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getGroups()
    {
        return ['core'];
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setRoles(['user', 'admin']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'admin123456'));
        $manager->persist($user);

        $user = new User();
        $user->setUsername('user');
        $user->setRoles(['user']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'user123456'));
        $manager->persist($user);
        
        $manager->flush();
    }
}
