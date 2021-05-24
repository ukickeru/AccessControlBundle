<?php

namespace ukickeru\AccessControlBundle\Application\Security\Authentication;

use Symfony\Component\Security\Core\User\UserInterface;
use ukickeru\AccessControl\Model\User;

class UserAdapter implements UserInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getRoles()
    {
        return $this->user->getRoles();
    }

    public function getPassword()
    {
        return $this->user->getPassword();
    }

    public function getSalt()
    {
        return $this->user->getSalt();
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function eraseCredentials()
    {
        return $this->user->eraseCredentials();
    }
}