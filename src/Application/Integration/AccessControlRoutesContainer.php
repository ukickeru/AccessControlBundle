<?php

namespace ukickeru\AccessControlBundle\Application\Integration;

use ukickeru\AccessControlBundle\Model\RoutesContainerInterface;

class AccessControlRoutesContainer implements RoutesContainerInterface
{
    private $routesNamesPrefix;
    
    private $routes;

    public function __construct(string $routesNamesPrefix = '', array $routes = [])
    {
        /*
         * @todo: сделать автозагрузку префикса имени маршрутов контроллеров и списка маршрутов для каждого модуля
         */
        $this->routesNamesPrefix = 'access_control';
        $this->routes = [
            'login',
            'logout'
        ];
    }

    public function getRoutesNamesPrefix(): string
    {
        return $this->routesNamesPrefix;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
