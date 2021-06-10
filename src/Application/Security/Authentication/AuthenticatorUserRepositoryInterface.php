<?php

namespace ukickeru\AccessControlBundle\Application\Security\Authentication;

use DomainException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use ukickeru\AccessControl\Model\UserInterface;

interface AuthenticatorUserRepositoryInterface extends PasswordUpgraderInterface
{

    /**
     * @param string $username
     * @return UserInterface
     * @throws DomainException
     */
    public function getOneByUsername(string $username): UserInterface;

}