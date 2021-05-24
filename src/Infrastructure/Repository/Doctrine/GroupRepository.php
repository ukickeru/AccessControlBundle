<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Repository\Doctrine;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DomainException;
use ukickeru\AccessControl\Model\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ukickeru\AccessControl\UseCase\GroupRepositoryInterface;

/**
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository implements GroupRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    /**
     * @return Group[]
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    /**
     * @param string $id
     * @return Group
     * @throws DomainException
     */
    public function getOneById(string $id): Group
    {
        $group = $this->find($id);

        if ($group === null) {
            throw new DomainException('Пользовательская группа с ID = "'.$id.'" не найдена!');
        }

        return $group;
    }

    /**
     * @param Group $group
     * @return Group
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Group $group): Group
    {
        $this->_em->persist($group);
        $this->_em->flush();
        
        return $group;
    }

    /**
     * @param Group $group
     * @return Group
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(Group $group): Group
    {
        $this->_em->persist($group);
        $this->_em->flush();

        return $group;
    }

    /**
     * @param string $id
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws DomainException
     */
    public function remove(string $id): bool
    {
        $group = $this->getOne($id);

        $this->_em->remove($group);
        $this->_em->flush();

        return true;
    }
}
