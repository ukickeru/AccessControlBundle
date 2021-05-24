<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Repository\Doctrine;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DomainException;
use ukickeru\AccessControl\Model\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ukickeru\AccessControl\UseCase\UserRepositoryInterface;
use ukickeru\AccessControlBundle\Application\Security\Authentication\AuthenticatorUserRepositoryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, UserRepositoryInterface, AuthenticatorUserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    /**
     * @param string $id
     * @return User
     */
    public function getOneById(string $id): User
    {
        $user = $this->find($id);

        if ($user === null) {
            throw new DomainException('Пользователь с ID = "'.$id.'" не найден!');
        }

        return $user;
    }

    /**
     * @param string $username
     * @return User
     */
    public function getOneByUsername(string $username): User
    {
        $user = $this->findOneBy(['username' => $username]);

        if ($user === null) {
            throw new DomainException('Пользователь с именем = "'.$username.'" не найден!');
        }

        return $user;
    }

    /**
     * @param User $user
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): User
    {
        $this->_em->persist($user);
        $this->_em->flush();

        return $user;
    }

    /**
     * @param User $user
     * @return User
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function update(User $user): User
    {
        $this->_em->persist($user);
        $this->_em->flush();
        
        return $user;
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
        $user = $this->getOne($id);

        $this->_em->remove($user);
        $this->_em->flush();

        return true;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
