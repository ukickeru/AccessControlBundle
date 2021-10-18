<?php

namespace ukickeru\AccessControlBundle\Application\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControl\Model\Service\CheckResourceAvailability\ResourceAvailabilityChecker;
use ukickeru\AccessControl\Model\Service\CheckResourceAvailability\ResourceInfo;
use ukickeru\AccessControl\Model\UserInterface;

class ResourceRequestSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private Security $security;

    /** @var ResourceAvailabilityChecker */
    private ResourceAvailabilityChecker $resourceAvailabilityChecker;

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

        $request = $event->getRequest();
        $requestedRouteName = $request->get('_route');
        $requestedRoutePath = $request->getPathInfo();

        if ($requestedRoutePath === ApplicationRoutesContainer::LOGIN_ROUTE_PATH) return;

        if ($this->isProfilerPanelRequest()) return;

        $resourceInfo = new ResourceInfo($requestedRoutePath,$requestedRouteName);

        if ($resourceInfo->isApiLoginPage()) return;

        if (
            $this->resourceAvailabilityChecker->checkResourceAvailableForUser($user, $resourceInfo) === false
        ) {
            throw new \DomainException('Доступ к запрашиваемому ресурсу запрещён!');
        }
    }

    public function isProfilerPanelRequest(): bool
    {
        return $this->isProductionModDisabled() &&
            $this->isDebugEnabled();
    }

    public function isProductionModDisabled(): bool
    {
        return mb_strtolower($_ENV['APP_ENV']) !== 'prod';
    }

    public function isDebugEnabled(): bool
    {
        return $_ENV['APP_DEBUG'] == true;
    }

    public function isProfilerPanelPath(string $requestedRoutePath): bool
    {
        return mb_substr($requestedRoutePath,0,5) === ApplicationRoutesContainer::DEVELOPER_TOOLBAR_PATH;
    }

    public function isNotUser($user): bool
    {
        return !$user instanceof UserInterface;
    }

}
