<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Controller\Http;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractSymfonyController;
use ukickeru\AccessControlBundle\Application\Security\Authentication\UserAdapter;

class AbstractController extends AbstractSymfonyController
{

    protected function getUser()
    {
        /** @var UserAdapter $userAdapter */
        $userAdapter =  parent::getUser();

        return $userAdapter->getUser();
    }

}