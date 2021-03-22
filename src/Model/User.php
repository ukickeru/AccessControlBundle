<?php

namespace ukickeru\AccessControlBundle\Model;

use ukickeru\AccessControlBundle\Infrastructure\Repository\Doctrine\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\UserInterface;
use ukickeru\AccessControlBundle\Model\Group;
use ukickeru\AccessControlBundle\Model\Routes\ApplicationRoutesContainer;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    public const PASSWORD_MIN_LENGTH = 8;
    public const DEFAULT_ROLE = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="users", cascade={"PERSIST", "REMOVE"}, fetch="EAGER")
     * @ORM\JoinTable(name="users_groups")
     */
    private $groups;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $admin = false;

    public function __construct(
        string $username,
        string $password,
        iterable $roles = [],
        iterable $groups = []
    )
    {
        $this->setUsername($username);
        $this->setPassword($password);

        $roles = array_unique($roles);
        if (empty($roles)) {
            $this->roles = new ArrayCollection();
        } else {
            $this->roles = new ArrayCollection((array) $roles);
        }

        $groups = array_unique($groups);
        if (empty($groups)) {
            $this->groups = new ArrayCollection();
        } else {
            if (empty($this->groups)) {
                $this->groups = new ArrayCollection((array) $groups);
            } else {
                $this->setGroups($groups);
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $username = trim($username);

        if ($username === '') {
            throw new \DomainException('Имя пользователя не может быть пустой строкой!');
        }

        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        return $this->updatePassword($password);
    }

    public function updatePassword(string $password): self
    {
        $password = trim($password);

        if ($password === '') {
            throw new \DomainException('Пароль пользователя не может быть пустой строкой!');
        }

        if (strlen($password) < self::PASSWORD_MIN_LENGTH) {
            throw new \DomainException('Пароль пользователя не может быть короче '.self::PASSWORD_MIN_LENGTH.' символов!');
        }

        $this->password = $password;

        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles->toArray();
        // guarantee every user at least has ROLE_USER
        $roles[] = self::DEFAULT_ROLE;

        return array_unique($roles);
    }

    public function addRole(string $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function addRoles(iterable $roles): self
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function removeRole(string $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    public function removeAllRoles(): self
    {
        $this->roles->clear();

        return $this;
    }

    public function setRoles(iterable $roles): self
    {
        $this->removeAllRoles();
        $this->addRoles($roles);

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addUser($this);
        }

        return $this;
    }

    public function addGroups(iterable $groups): self
    {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeUser($this);
        }

        return $this;
    }

    public function removeAllGroups(): self
    {
        foreach ($this->groups as $group) {
            $this->removeGroup($group);
        }

        return $this;
    }

    public function setGroups(iterable $groups): self
    {
        foreach ($this->groups as $group) {
            if (!in_array($group,(array) $groups)) {
                $this->removeGroup($group);
            }
        }

        $this->addGroups($groups);

        return  $this;
    }

    public function getAvailableRoutes(): array
    {
        $availableRoutes = [];

        foreach ($this->getGroups() as $group) {
            $availableRoutes[] = $group->getAvailableRoutes();
        }

        if (empty($availableRoutes)) {
            return ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES;
        }

        return array_unique($availableRoutes);
    }

    public function isRouteAvailable(string $route): bool
    {
        if (strlen($route) > 1 && mb_substr($route,strlen($route) - 1,1) === '/') {
            $route = mb_substr($route,0,strlen($route) - 1);
        }

        foreach ($this->getGroups() as $group) {
            if ($group->isRouteAvailable($route)) {
                return true;
            };
        }

        if (empty($this->availableRoutes)) {
            return in_array($route,ApplicationRoutesContainer::GUARANTEED_ACCESSIBLE_ROUTES);
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    public function getSalt()
    {
        return $this->id . $this->username;
    }

    public function eraseCredentials()
    {
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
