<?php

namespace ukickeru\AccessControlBundle\Model\Service;

use Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use ukickeru\AccessControl\Model\Service\IdGenerator as DomainIdGenerator;

class IdGenerator extends AbstractIdGenerator
{

    public function generate(EntityManager $em, $entity)
    {
        $entityName = $em->getClassMetadata(get_class($entity))->getName();

        while (true)
        {
            $id = DomainIdGenerator::generate();
            $entityFound = $em->find($entityName, $id);

            if (!$entityFound)
            {
                return $id;
            }
        }

        throw new Exception(sprintf('Не удалось сгенерировать ID для сущности класса "%s"!', get_class($entity)));
    }
}