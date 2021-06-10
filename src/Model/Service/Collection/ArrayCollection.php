<?php

namespace ukickeru\AccessControlBundle\Model\Service\Collection;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;
use ukickeru\AccessControl\Model\Service\Collection\Collection;

class ArrayCollection extends DoctrineArrayCollection implements Collection
{
}