<?php

namespace ukickeru\AccessControlBundle\Model;

interface RoutesHolderInterface
{

    public function getAvailableRoutes(): array;
}
