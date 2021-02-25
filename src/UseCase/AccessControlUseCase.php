<?php

namespace ukickeru\AccessControlBundle\UseCase;

use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

class AccessControlUseCase
{

    public function CreateGroup(Group $group)
    {
    }

    public function EditGroup(Group $group)
    {
    }

    public function RemoveGroup(Group $group)
    {
    }

    public function AddGroupToParentGroup(Group $group, Group $parentGroup)
    {
    }

    public function RemoveGroupFromParentGroup(Group $group, Group $parentGroup)
    {
    }

    public function CreateUser(User $user)
    {
    }

    public function EditUser(User $user)
    {
    }

    public function RemoveUser(User $user)
    {
    }

    public function AddUserToGroup(User $user, Group $group)
    {
    }

    public function RemoveUserFromGroup(User $user, Group $group)
    {
    }
}
