<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class Account implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\OneToMany(mappedBy: 'Owner', targetEntity: System::class, orphanRemoval: true)]
    private Collection $systems;

    #[ORM\ManyToMany(targetEntity: System::class, mappedBy: 'User')]
    private Collection $systemUser;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Device::class, orphanRemoval: true)]
    private Collection $devices;

    #[ORM\OneToMany(mappedBy: 'requester', targetEntity: ShareRequest::class, orphanRemoval: true)]
    private Collection $shareRequests;

    public function __construct()
    {
        $this->systems = new ArrayCollection();
        $this->systemUser = new ArrayCollection();
        $this->devices = new ArrayCollection();
        $this->shareRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, System>
     */
    public function getSystems(): Collection
    {
        return $this->systems;
    }

    public function addSystem(System $system): static
    {
        if (!$this->systems->contains($system)) {
            $this->systems->add($system);
            $system->setOwner($this);
        }

        return $this;
    }

    public function removeSystem(System $system): static
    {
        if ($this->systems->removeElement($system)) {
            // set the owning side to null (unless already changed)
            if ($system->getOwner() === $this) {
                $system->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, System>
     */
    public function getSystemUser(): Collection
    {
        return $this->systemUser;
    }

    public function addSystemUser(System $systemUser): static
    {
        if (!$this->systemUser->contains($systemUser)) {
            $this->systemUser->add($systemUser);
            $systemUser->addUser($this);
        }

        return $this;
    }

    public function removeSystemUser(System $systemUser): static
    {
        if ($this->systemUser->removeElement($systemUser)) {
            $systemUser->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Device>
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): static
    {
        if (!$this->devices->contains($device)) {
            $this->devices->add($device);
            $device->setOwner($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): static
    {
        if ($this->devices->removeElement($device)) {
            // set the owning side to null (unless already changed)
            if ($device->getOwner() === $this) {
                $device->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShareRequest>
     */
    public function getShareRequests(): Collection
    {
        return $this->shareRequests;
    }

    public function addShareRequest(ShareRequest $shareRequest): static
    {
        if (!$this->shareRequests->contains($shareRequest)) {
            $this->shareRequests->add($shareRequest);
            $shareRequest->setRequester($this);
        }

        return $this;
    }

    public function removeShareRequest(ShareRequest $shareRequest): static
    {
        if ($this->shareRequests->removeElement($shareRequest)) {
            // set the owning side to null (unless already changed)
            if ($shareRequest->getRequester() === $this) {
                $shareRequest->setRequester(null);
            }
        }

        return $this;
    }
}
