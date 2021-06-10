<?php

namespace ukickeru\AccessControlBundle\Application\Security\Authentication;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use ukickeru\AccessControlBundle\Model\User;

class UserProvider implements UserProviderInterface
{
    private $userRepository;

    public function __construct(
        AuthenticatorUserRepositoryInterface $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername(string $username)
    {
        try {
            $user = $this->userRepository->getOneByUsername($username);
        } catch (\Exception $exception) {
            throw new \DomainException(
                sprintf('Пользователь с именем "%s" не найден!', $username)
            );
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $username = $user->getUsername();

        try {
            $user = $this->userRepository->getOneByUsername($username);
        } catch (\Exception $exception) {
            throw new \DomainException(
                sprintf('Пользователь с именем "%s" не найден!', $username)
            );
        }

        return $user;
    }

    public function supportsClass(string $class)
    {
        return $class === User::class;
    }

    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Пользователь класса "%s" не поддерживается!.', get_debug_type($user)));
        }

        if ($this->userRepository instanceof PasswordUpgraderInterface) {
            $this->userRepository->upgradePassword($user, $newEncodedPassword);
        }
    }
}