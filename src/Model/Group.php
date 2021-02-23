<?php

namespace ukickeru\AccessControlBundle\Model;

class Group
{

    private $name;

    public function __construct()
    {
        $this->name = 'test';
    }

    public function getName()
    {
        return $this->name;
    }
}
