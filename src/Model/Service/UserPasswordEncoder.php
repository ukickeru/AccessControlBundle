<?php

namespace ukickeru\AccessControlBundle\Model\Service;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as FrameworkUserPasswordEncoderInterface;
use ukickeru\AccessControl\Model\Service\UserPasswordEncoderInterface;
use ukickeru\AccessControl\Model\UserInterface;
use ukickeru\AccessControlBundle\Model\User;

class UserPasswordEncoder implements UserPasswordEncoderInterface
{

    private $frameworkPasswordEncoder;

    public function __construct(FrameworkUserPasswordEncoderInterface $frameworkPasswordEncoder)
    {
        $this->frameworkPasswordEncoder = $frameworkPasswordEncoder;
    }

    /**
     * @param UserInterface|User $user
     * @param string $password
     * @return string
     */
    public function encodePassword(UserInterface $user, string $password): string
    {
        return $this->frameworkPasswordEncoder->encodePassword(
            $user,
            $user->getPassword()
        );
    }
}