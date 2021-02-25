<?php

namespace ukickeru\AccessControlBundle\Model;

class ApplicationRoutesContainer
{
    protected $routesContainers;

    public function __construct(iterable $routesContainers)
    {
        $this->routesContainers = $routesContainers;
    }
    
    public function getContainers(): array
    {
        return $this->routesContainers;
    }
}
