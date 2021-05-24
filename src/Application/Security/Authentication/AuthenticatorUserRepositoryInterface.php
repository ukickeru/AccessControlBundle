<?php

namespace ukickeru\AccessControlBundle\Application\Security\Authentication;

use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use ukickeru\AccessControl\Model\User;
use ukickeru\AccessControlBundle\Application\Security\DomainException;

interface AuthenticatorUserRepositoryInterface extends PasswordUpgraderInterface
{

    /**
     * @param string $username
     * @return User
     * @throws DomainException
     */
    public function getOneByUsername(string $username): User;

}