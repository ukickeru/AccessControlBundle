<?php

namespace ukickeru\AccessControlBundle\Infrastructure\Repository;

use ukickeru\AccessControlBundle\Model\User;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function getOneById(string $id): User;

    public function save(User $user): User;

    public function update(User $user): User;

    public function remove(User $user): void;
}
