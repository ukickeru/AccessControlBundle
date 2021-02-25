<?php

namespace ukickeru\AccessControlBundle\Model;

interface RoutesContainerInterface
{
    public function getRoutesNamesPrefix(): string;

    public function getRoutes(): array;
}
