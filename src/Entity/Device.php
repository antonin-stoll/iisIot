<?php

namespace App\Entity;

use App\Repository\DeviceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DeviceRepository::class)]
class Device
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $userAlias = null;

    #[ORM\ManyToMany(targetEntity: System::class, mappedBy: 'devices')]
    private Collection $systems;

    #[ORM\OneToMany(mappedBy: 'device', targetEntity: KPI::class, orphanRemoval: true)]
    private Collection $kpis;

    #[ORM\OneToMany(mappedBy: 'device', targetEntity: Parameter::class, orphanRemoval: true)]
    private Collection $parameters;

    #[ORM\ManyToOne(inversedBy: 'devices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $owner = null;

    public function __construct()
    {
        $this->kpis = new ArrayCollection();
        $this->parameters = new ArrayCollection();
        $this->systems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

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

    public function getUserAlias(): ?string
    {
        return $this->userAlias;
    }

    public function setUserAlias(?string $userAlias): static
    {
        $this->userAlias = $userAlias;

        return $this;
    }

    public function getSystems(): Collection
    {
        return $this->systems;
    }

    public function addSystem(System $system): self
    {
        if (!$this->systems->contains($system)) {
            $this->systems[] = $system;
            $system->addDevice($this);
        }

        return $this;
    }

    public function removeSystem(System $system): self
    {
        if ($this->systems->removeElement($system)) {
            $system->removeDevice($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, KPI>
     */
    public function getKpis(): Collection
    {
        return $this->kpis;
    }

    public function addKpi(KPI $kpi): static
    {
        if (!$this->kpis->contains($kpi)) {
            $this->kpis->add($kpi);
            $kpi->setDevice($this);
        }

        return $this;
    }

    public function removeKpi(KPI $kpi): static
    {
        if ($this->kpis->removeElement($kpi)) {
            // set the owning side to null (unless already changed)
            if ($kpi->getDevice() === $this) {
                $kpi->setDevice(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Parameter>
     */
    public function getParameters(): Collection
    {
        return $this->parameters;
    }

    public function addParameter(Parameter $parameter): static
    {
        if (!$this->parameters->contains($parameter)) {
            $this->parameters->add($parameter);
            $parameter->setDevice($this);
        }

        return $this;
    }

    public function removeParameter(Parameter $parameter): static
    {
        if ($this->parameters->removeElement($parameter)) {
            // set the owning side to null (unless already changed)
            if ($parameter->getDevice() === $this) {
                $parameter->setDevice(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?Account
    {
        return $this->owner;
    }

    public function setOwner(?Account $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
