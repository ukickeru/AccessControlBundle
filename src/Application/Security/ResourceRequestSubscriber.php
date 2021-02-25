<?php

namespace ukickeru\AccessControlBundle\Application\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class ResourceRequestSubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => [
                ['checkIfResourceAvailableForUser', 10],
            ],
        ];
    }

    public function checkIfResourceAvailableForUser(ControllerEvent $event)
    {
        $user = $this->security->getUser();
        $requestedPath = $event->getRequest()->getPathInfo();

        /*
         * @todo: check if user allow to request path
         */
    }
}
