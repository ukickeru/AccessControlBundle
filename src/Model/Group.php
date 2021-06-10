<?php

namespace ukickeru\AccessControlBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use ukickeru\AccessControl\Model\Group as DomainGroup;
use ukickeru\AccessControl\Model\GroupInterface;
use ukickeru\AccessControl\Model\Service\IdGenerator;
use ukickeru\AccessControl\Model\UserInterface;

class Group extends DomainGroup
{

    public function __construct(
        string $name,
        UserInterface $creator,
        GroupInterface $parentGroup = null,
        iterable $availableRoutes = [],
        iterable $users = []
    )
    {
        $this->id = IdGenerator::generate();
        $this->setName($name);
        $this->setCreator($creator);
        $this->availableRoutes = new ArrayCollection();
        $this->setAvailableRoutes($availableRoutes);
        $this->users = new ArrayCollection();
        $this->setUsers($users);
        $this->setParentGroup($parentGroup);
        $this->setCreationDate();
    }

    public static function createFromDomainUser(DomainGroup $domainGroup): self
    {
        $group = new self(
            $domainGroup->getName(),
            $domainGroup->getCreator(),
            $domainGroup->getParentGroup(),
            $domainGroup->getAvailableRoutes()->toArray(),
            $domainGroup->getUsers()->toArray()
        );

        $group->id = $domainGroup->getId();

        return $group;
    }

}