<?php

namespace ukickeru\AccessControlBundle\Application\DataFixtures\Doctrine\Test;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
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
        if ($_ENV['APP_ENV'] !== 'test') {
            return;
        }

        $user = new User(
            'admin',
            'admin123456',
            ['user', 'admin']
        );
        $user->updatePassword($this->passwordEncoder->encodePassword($user, 'admin123456'));
        $manager->persist($user);

        $user = new User(
            'user',
            'user123456',
            ['user']
        );
        $user->updatePassword($this->passwordEncoder->encodePassword($user, 'user123456'));
        $manager->persist($user);
        
        $manager->flush();
    }
}
