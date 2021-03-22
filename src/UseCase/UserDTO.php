<?php

namespace ukickeru\AccessControlBundle\UseCase;

use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserDTO
{
    public $id;

    /**
     * @Assert\NotBlank()
     */
    public $username;

    /**
     * @Assert\NotBlank()
     */
    public $password;

    /** @var array */
    public $roles = [];

    /** @var Group[]|array */
    public $groups = [];

    /** @var boolean */
    public $admin;

    public static function createFromUser(User $user) {
        $userDTO = new self();
        $userDTO->id = $user->getId();
        $userDTO->username = $user->getUsername();
        $userDTO->password = $user->getPassword();
        $userDTO->roles = $user->getRoles();
        $userDTO->groups = $user->getGroups();
        $userDTO->admin = $user->isAdmin();

        return $userDTO;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): iterable
    {
        return $this->roles;
    }

    public function setRoles(?iterable $roles): self
    {
        if ($roles === null) {
            $this->roles = [];
        } else {
            $this->roles = $roles;
        }

        return $this;
    }

    public function getGroups(): iterable
    {
        return $this->groups;
    }

    public function setGroups(?iterable $groups): self
    {
        if ($groups === null) {
            $this->groups = [];
        } else {
            $this->groups = $groups;
        }

        return $this;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

}