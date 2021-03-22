<?php

namespace ukickeru\AccessControlBundle\Model\Routes;

interface RoutesGetterInterface
{
    public function createRoutesCollection(): iterable;
}
