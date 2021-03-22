<?php

namespace ukickeru\AccessControlBundle\Application\Presenters\Group\Routes;

use Symfony\Component\Form\DataTransformerInterface;
use ukickeru\AccessControlBundle\Model\Routes\ApplicationRoutesContainer;
use ukickeru\AccessControlBundle\Model\Routes\Route;

class ApplicationRoutesTransformer implements DataTransformerInterface
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
     * Трансформирует string[] в Route[]
     */
    public function transform($value)
    {
        return $this->getApplicationRoutesByNames($value);
    }

    /**
     * @inheritDoc
     * @param $value Route[]
     * Трансформирует Route[] в string[]
     */
    public function reverseTransform($value)
    {
        return array_unique($this->getApplicationRoutesNamesArray($value));
    }

    private function getApplicationRoutesByNames(array $paths): array
    {
        if (empty($paths)) return [];

        /** @var Route[] $applicationRoutes */
        $applicationRoutes = $this->applicationRoutesContainer->getRoutes();
        $applicationRoutesNames = [];

        foreach ($applicationRoutes as $route) {
            $routeName = $route->getName();
            if (in_array($routeName,$paths) && !in_array($route,$applicationRoutesNames)) {
                $applicationRoutesNames[] = $route;
            }
        }

        return $applicationRoutesNames;
    }

    private function getApplicationRoutesNamesArray(array $routes = []): array
    {
        $applicationRoutesNames = [];

        foreach ($routes as $route) {
            $applicationRoutesNames[] = $route->getName();
        }

        return array_unique($applicationRoutesNames);
    }
}