<?php

namespace App\Entity;

use App\Repository\KPIRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KPIRepository::class)]
class KPI
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $expression = null;

    #[ORM\ManyToOne(inversedBy: 'kpis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $device = null;

    #[ORM\ManyToOne(inversedBy: 'kpis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Parameter $parameter = null;

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

    public function getExpression(): ?string
    {
        return $this->expression;
    }

    public function setExpression(string $expression): static
    {
        $this->expression = $expression;

        return $this;
    }

    public function getDevice(): ?Device
    {
        return $this->device;
    }

    public function setDevice(?Device $device): static
    {
        $this->device = $device;

        return $this;
    }

    public function getParameter(): ?Parameter
    {
        return $this->parameter;
    }

    public function setParameter(?Parameter $parameter): static
    {
        $this->parameter = $parameter;

        return $this;
    }
}
