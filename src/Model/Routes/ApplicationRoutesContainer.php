<?php

namespace ukickeru\AccessControlBundle\Model\Routes;

use ukickeru\AccessControlBundle\Model\Routes\RoutesGetterInterface;

class ApplicationRoutesContainer
{

    public const GUARANTEED_ACCESSIBLE_ROUTES =[
        self::INDEX_PATH,
        self::LOGIN_ROUTE_PATH,
        self::LOGOUT_ROUTE_PATH,
        self::ACCOUNT_ROUTE_PATH,
        self::ACCOUNT_GROUPS_ROUTE_PATH,
        self::ACCOUNT_SETTINGS_ROUTE_PATH
    ];

    public const INDEX_PATH = 'app_index';

    public const LOGIN_ROUTE_PATH = 'login';

    public const LOGOUT_ROUTE_PATH = 'logout';

    public const ACCOUNT_ROUTE_PATH = 'account_index';

    public const ACCOUNT_GROUPS_ROUTE_PATH = 'account_groups';

    public const ACCOUNT_SETTINGS_ROUTE_PATH = 'account_settings';

    protected $routesCollection;

    public function __construct(RoutesGetterInterface $routesCreator)
    {
        $this->routesCollection = $routesCreator->createRoutesCollection();
    }

    /**
     * @return iterable|Route[]
     */
    public function getRoutes(): iterable
    {
        return $this->routesCollection;
    }
}
