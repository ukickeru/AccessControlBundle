<?php

namespace ukickeru\AccessControlBundle\UseCase;

use Symfony\Component\Validator\Constraints as Assert;


class ChangeAdminPermissionsDTO
{

    /**
     * @Assert\Type(UserDTO::class)
     */
    public $newAdmin = null;

    /**
     * @Assert\IsTrue()
     */
    public $confirmed = false;

    public function getNewAdmin(): UserDTO
    {
        return $this->newAdmin;
    }

    public function setNewAdmin($newAdmin): self
    {
        $this->newAdmin = $newAdmin;

        return $this;
    }

    public function getConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed($confirmed): self
    {
        $this->confirmed = $confirmed;

        return $this;
    }
}