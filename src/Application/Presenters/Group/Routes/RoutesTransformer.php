<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\Group\Routes;

use Symfony\Component\Form\DataTransformerInterface;
use ukickeru\AccessControl\Model\Routes\ApplicationRoutesContainer;

class RoutesTransformer implements DataTransformerInterface
{

    /** @var ApplicationRoutesContainer */
    private $applicationRoutesContainer;

    public function __construct(ApplicationRoutesContainer $applicationRoutesContainer)
    {
        $this->applicationRoutesContainer = $applicationRoutesContainer;
    }

    /**
     * @inheritDoc
     * @param $value array|string[]
     * Трансформирует 1 общий массив доступных маршрутов в RoutesDTO (DTO с маршрутами приложения и кастомными маршрутами)
     */
    public function transform($value)
    {
        $routesDTO = new RoutesDTO();

        $value = array_unique($value);

        $applicationRoutes = $this->getApplicationRoutesNamesArray();

        foreach ($value as $route) {
            if (in_array($route,$applicationRoutes)) {
                $routesDTO->applicationRoutes[] = $route;
            } else {
                $routesDTO->customRoutes[] = $route;
            }
        }

        return $routesDTO;
    }

    /**
     * @inheritDoc
     * @param $value RoutesDTO
     * Трансформирует RoutesDTO (DTO с маршрутами приложения и кастомными маршрутами) в 1
     */
    public function reverseTransform($value)
    {
        return array_unique(
            array_merge(
                $value->applicationRoutes,
                $value->customRoutes
            )
        );
    }

    private function getApplicationRoutesNamesArray(array $routes = []): array
    {
        $applicationRoutes = empty($routes) ? $this->applicationRoutesContainer->getRoutes() : $routes;
        $applicationRoutesNames = [];

        foreach ($applicationRoutes as $route) {
            $applicationRoutesNames[] = $route->getName();
        }

        return array_unique($applicationRoutesNames);
    }
}