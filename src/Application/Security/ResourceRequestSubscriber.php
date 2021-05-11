<?php

namespace ukickeru\AccessControlBundle\Application\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use ukickeru\AccessControlBundle\Model\User;

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
        /** @var User $user */
        $user = $this->security->getUser();
        $request = $event->getRequest();
        $requestedRoutePath = $request->getPathInfo();
        $requestedRouteName = $request->get('_route');

        if (
            $user instanceof User &&
            !(
                is_string($requestedRoutePath) && $user->isRouteAvailable($requestedRoutePath) ||
                is_string($requestedRouteName) && $user->isRouteAvailable($requestedRouteName)
            )
        ) {
//            throw new \DomainException('Доступ к запрашиваемому ресурсу запрещён!');
        }
    }
}
