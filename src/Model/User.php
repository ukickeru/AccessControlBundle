<?php

namespace ukickeru\AccessControlBundle\Model;

use ukickeru\AccessControl\Model\Service\IdGenerator;
use ukickeru\AccessControl\Model\User as DomainUser;
use ukickeru\AccessControl\Model\UserInterface as DomainUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ukickeru\AccessControlBundle\Model\Service\Collection\ArrayCollection;

class User extends DomainUser implements UserInterface
{
    public function __construct(
        string $username,
        string $password,
        iterable $roles = [],
        iterable $groups = []
    )
    {
        $this->id = IdGenerator::generate();
        $this->setUsername($username);
        $this->setPassword($password);
        $this->roles = new ArrayCollection();
        $this->setRoles($roles);
        $this->groups = new ArrayCollection();
        $this->setGroups($groups);
    }

    public static function createFromDomainUser(DomainUserInterface $domainUser): self
    {
        $user = new self(
            $domainUser->getUsername(),
            $domainUser->getPassword(),
            $domainUser->getRoles(),
            $domainUser->getGroups()->toArray()
        );

        $user->id = $domainUser->getId();

        return $user;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        return false;
    }
}