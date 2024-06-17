<?php

namespace App\Entity;

use App\Repository\SystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemRepository::class)]
#[ORM\Table(name: '`system`')]
class System
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'systems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $Owner = null;

    #[ORM\ManyToMany(targetEntity: Account::class, inversedBy: 'systemUser')]
    private Collection $User;

    #[ORM\ManyToMany(targetEntity: Device::class, inversedBy: 'systems')]
    private Collection $devices;

    #[ORM\OneToMany(mappedBy: 'system', targetEntity: ShareRequest::class, orphanRemoval: true)]
    private Collection $shareRequests;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->devices = new ArrayCollection();
        $this->shareRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOwner(): ?Account
    {
        return $this->Owner;
    }

    public function setOwner(?Account $Owner): static
    {
        $this->Owner = $Owner;

        return $this;
    }

    /**
     * @return Collection<int, Account>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(Account $user): static
    {
        if (!$this->User->contains($user)) {
            $this->User->add($user);
        }

        return $this;
    }

    public function removeUser(Account $user): static
    {
        $this->User->removeElement($user);

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
            $this->devices[] = $device;
            $device->addSystem($this);
        }

        return $this;
    }

    public function removeDevice(Device $device): static
    {
        if ($this->devices->removeElement($device)) {
            $device->removeSystem($this);
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
            $shareRequest->setSystem($this);
        }

        return $this;
    }

    public function removeShareRequest(ShareRequest $shareRequest): static
    {
        if ($this->shareRequests->removeElement($shareRequest)) {
            // set the owning side to null (unless already changed)
            if ($shareRequest->getSystem() === $this) {
                $shareRequest->setSystem(null);
            }
        }

        return $this;
    }
}
