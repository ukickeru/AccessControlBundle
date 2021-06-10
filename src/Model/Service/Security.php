<?php

namespace ukickeru\AccessControlBundle\Model\Service;

use Symfony\Component\Security\Core\Security as FrameworkSecurity;
use ukickeru\AccessControl\Model\Service\SecurityInterface;
use ukickeru\AccessControl\Model\UserInterface;

class Security implements SecurityInterface
{

    private $frameworkSecurity;

    public function __construct(FrameworkSecurity $frameworkSecurity)
    {
        $this->frameworkSecurity = $frameworkSecurity;
    }

    public function getUser(): ?UserInterface
    {
        return $this->frameworkSecurity->getUser();
    }

}