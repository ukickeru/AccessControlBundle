<?php

namespace ukickeru\AccessControlBundle\UseCase;

use DomainException;
use ukickeru\AccessControlBundle\Model\User;

interface UserRepositoryInterface
{

    /**
     * @return array|User[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return User
     * @throws DomainException
     */
    public function getOne(string $id): User;

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user): User;

    /**
     * @param User $user
     * @return User
     */
    public function update(User $user): User;

    /**
     * @param string $id
     * @return bool
     * @throws DomainException
     */
    public function remove(string $id): bool;
}
