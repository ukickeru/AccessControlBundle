<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Repository\Doctrine;

use ukickeru\AccessControlBundle\Model\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use ukickeru\AccessControlBundle\Infrastructure\Repository\GroupRepositoryInterface;

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

    public function getAll(): array
    {
        return $this->findAll();
    }

    public function getOneById(string $id): Group
    {
        return $this->find($id);
    }

    public function save(Group $user): Group
    {
        $this->_em->persist($user);
        $this->_em->flush();
        
        return $user;
    }

    public function update(Group $user): Group
    {
        $this->_em->persist($user);
        $this->_em->flush();
        
        return $user;
    }

    public function remove(Group $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
}
