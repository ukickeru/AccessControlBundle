<?php

namespace ukickeru\AccessControlBundle\Model\Routes;

use Psr\Container\ContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Routing\Route as FrameworkRoute;
use ukickeru\AccessControlBundle\Model\Routes\Route;
use ukickeru\AccessControlBundle\Model\Routes\RoutesGetterInterface;

class RoutesGetter implements RoutesGetterInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function createRoutesCollection(): iterable
    {
        $router = $this->container->get('router');
        $loadedRoutes = $router->getRouteCollection();

        if (empty($loadedRoutes)) {
            throw new DomainException('Список путей приложения не может быть пуст! Чтобы AccessControlBundle мог корректно работать, нужно повысить приоритет загрузчика маршрутов (RoutesLoader)!');
        }

        $routes = [];

        foreach ($loadedRoutes as $routeName => $route) {
            $routePath = $route->getPath();
            $controllerClass = $this->getControllerClass($route);
            $controllerMethod = $this->getControllerMethod($route);
            $methods = $route->getMethods();

            $newRoute = new Route($routeName, $routePath, $controllerClass, $controllerMethod, $methods);
            $routes[] = $newRoute;
        }

        return new ArrayCollection($routes);
    }

    protected function getControllerClass(FrameworkRoute $route): ?string
    {
        $defaults = $route->getDefaults();

        if (isset($defaults['_controller'])) {
            $controllerClassAndMethod = explode('::', $defaults['_controller']);
            $controllerClass = $controllerClassAndMethod[0];
        }

        return $controllerClass ?? null;
    }

    protected function getControllerMethod(FrameworkRoute $route): ?string
    {
        $defaults = $route->getDefaults();

        if (isset($defaults['_controller'])) {
            $controllerClassAndMethod = explode('::', $defaults['_controller']);
            $controllerMethod = $controllerClassAndMethod[1];
        }

        return $controllerMethod ?? null;
    }
}
