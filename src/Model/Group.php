<?php

namespace ukickeru\AccessControlBundle\Model;

use ukickeru\AccessControlBundle\Infrastructure\Repository\Doctrine\GroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class Group
{

    private $name;

    public function __construct()
    {
        $this->name = 'test';
    }

    public function getName()
    {
        return $this->name;
    }
}
