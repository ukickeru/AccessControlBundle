<?php

namespace ukickeru\AccessControlBundle\UseCase;

use DomainException;
use ukickeru\AccessControlBundle\Model\Group;

interface GroupRepositoryInterface
{
    /**
     * @return array|Group[]
     */
    public function getAll(): array;

    /**
     * @param string $id
     * @return Group
     * @throws DomainException
     */
    public function getOne(string $id): Group;

    /**
     * @param Group $group
     * @return Group
     */
    public function save(Group $group): Group;

    /**
     * @param Group $group
     * @return Group
     */
    public function update(Group $group): Group;

    /**
     * @param string $id
     * @return bool
     * @throws DomainException
     */
    public function remove(string $id): bool;
}
