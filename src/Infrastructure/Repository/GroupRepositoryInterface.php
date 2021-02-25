<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Repository;

use ukickeru\AccessControlBundle\Model\Group;

interface GroupRepositoryInterface
{
    public function getAll(): array;

    public function getOneById(string $id): Group;

    public function save(Group $user): Group;

    public function update(Group $user): Group;

    public function remove(Group $user): void;
}
