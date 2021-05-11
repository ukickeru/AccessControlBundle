<?php

namespace ukickeru\AccessControlBundle\UseCase;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use ukickeru\AccessControlBundle\UseCase\GroupDTO;
use ukickeru\AccessControlBundle\UseCase\UserDTO;
use ukickeru\AccessControlBundle\UseCase\GroupRepositoryInterface;
use ukickeru\AccessControlBundle\UseCase\UserRepositoryInterface;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\User;

class AccessControlUseCase
{

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var Security */
    private $security;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
        Security $security,
        UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->userRepository = $userRepository;
        $this->groupRepository = $groupRepository;
        $this->security = $security;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return array|GroupDTO[]
     */
    public function getAllGroups()
    {
        $groupDTOs = [];

        foreach ($this->groupRepository->getAll() as $group) {
            $groupDTOs[] = GroupDTO::createFromGroup($group);
        }

        return $groupDTOs;
    }

    /**
     * @param string $id
     * @return GroupDTO
     */
    public function getGroup(string $id)
    {
        return GroupDTO::createFromGroup($this->groupRepository->getOne($id));
    }

    /**
     * @param GroupDTO $groupDTO
     * @return GroupDTO
     */
    public function createGroup(GroupDTO $groupDTO)
    {
        /** @var User $creator */
        $creator = $this->security->getUser();

        $group = new Group(
            $groupDTO->name,
            $creator,
            $groupDTO->parentGroup,
            $groupDTO->availableRoutes,
            $groupDTO->users
        );

        $this->groupRepository->save($group);

        return $groupDTO;
    }

    /**
     * @param GroupDTO $groupDTO
     * @return GroupDTO
     */
    public function editGroup(GroupDTO $groupDTO)
    {
        $group = $this->groupRepository->getOne($groupDTO->id);

        $group
            ->setName($groupDTO->name)
            ->setParentGroup($groupDTO->parentGroup)
            ->setAvailableRoutes($groupDTO->availableRoutes)
            ->setUsers($groupDTO->users)
        ;

        $this->groupRepository->save($group);

        return $groupDTO;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function removeGroup(string $id)
    {
        return $this->groupRepository->remove($id);
    }

    /**
     * @return array|UserDTO[]
     */
    public function getAllUsers()
    {
        $userDTOs = [];

        foreach ($this->userRepository->getAll() as $user) {
            $userDTOs[] = UserDTO::createFromUser($user);
        }

        return $userDTOs;
    }

    /**
     * @param string $id
     * @return UserDTO
     */
    public function getUser(string $id)
    {
        return UserDTO::createFromUser($this->userRepository->getOne($id));
    }

    /**
     * @param UserDTO $userDTO
     * @return UserDTO
     */
    public function createUser(UserDTO $userDTO)
    {
        $user = new User(
            $userDTO->username,
            $userDTO->password,
            $userDTO->roles,
            $userDTO->groups
        );

        $user->updatePassword($this->passwordEncoder->encodePassword($user,$userDTO->password));

        $this->userRepository->save($user);

        return $userDTO;
    }

    /**
     * @param UserDTO $userDTO
     * @return UserDTO
     */
    public function editUser(UserDTO $userDTO)
    {
        $user = $this->userRepository->getOne($userDTO->id);

        $user
            ->setUsername($userDTO->username)
            ->updatePassword($this->passwordEncoder->encodePassword($user,$userDTO->password))
            ->setRoles($userDTO->roles)
            ->setGroups($userDTO->groups)
        ;

        $this->userRepository->save($user);

        return $userDTO;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function removeUser(string $id)
    {
        return $this->userRepository->remove($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function turnAdministrativePermissionsToAnotherUser(string $id): bool
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        if ($currentUser->isAdmin() === false) {
            throw new \DomainException('У вас нет административных прав!');
        }

        $user = $this->userRepository->getOne($id);

        $user->setAdmin(true);
        $this->userRepository->save($user);

        $currentUser->setAdmin(false);
        $this->userRepository->save($currentUser);
    }
}
