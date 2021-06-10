<?php

namespace ukickeru\AccessControlBundle\Application\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use ukickeru\AccessControl\Model\Service\CheckResourceAvailability\ResourceAvailabilityChecker;
use ukickeru\AccessControl\Model\Service\CheckResourceAvailability\ResourceInfo;
use ukickeru\AccessControl\Model\UserInterface;

class ResourceRequestSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $security;

    /** @var ResourceAvailabilityChecker */
    private $resourceAvailabilityChecker;

    public function __construct(
        Security $security,
        ResourceAvailabilityChecker $resourceAvailabilityChecker
    )
    {
        $this->security = $security;
        $this->resourceAvailabilityChecker = $resourceAvailabilityChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                ['checkIfResourceAvailableForUser', 7],
            ],
        ];
    }

    public function checkIfResourceAvailableForUser(RequestEvent $event)
    {
        /** @var UserInterface $user */
        $user = $this->security->getUser();

        if (
            !$this->isProductionModEnabled() &&
            $this->isDebugEnabled() &&
            !$user instanceof UserInterface
        ) {
            return;
        }

        $request = $event->getRequest();
        $requestedRouteName = $request->get('_route');
        $requestedRoutePath = $request->getPathInfo();
        $resourceInfo = new ResourceInfo($requestedRoutePath,$requestedRouteName);

        if ($this->resourceAvailabilityChecker->checkResourceAvailableForUser($user,$resourceInfo) === false) {
            throw new \DomainException('Доступ к запрашиваемому ресурсу запрещён!');
        }
    }

    public function isProductionModEnabled(): bool
    {
        return $_ENV['APP_ENV'] === 'prod';
    }

    public function isDebugEnabled(): bool
    {
        return $_ENV['APP_DEBUG'] == 'true';
    }
}
