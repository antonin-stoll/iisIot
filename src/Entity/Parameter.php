<?php

namespace App\Entity;

use App\Repository\ParameterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParameterRepository::class)]
class Parameter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?float $value = null;

    #[ORM\Column(nullable: true)]
    private ?float $minValue = null;

    #[ORM\Column(nullable: true)]
    private ?float $FmaxValue = null;

    #[ORM\ManyToOne(inversedBy: 'parameters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Device $device = null;

    #[ORM\OneToMany(mappedBy: 'parameter', targetEntity: KPI::class, orphanRemoval: true)]
    private Collection $kpis;

    public function __construct()
    {
        $this->kpis = new ArrayCollection();
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getMinValue(): ?float
    {
        return $this->minValue;
    }

    public function setMinValue(?float $minValue): static
    {
        $this->minValue = $minValue;

        return $this;
    }

    public function getFmaxValue(): ?float
    {
        return $this->FmaxValue;
    }

    public function setFmaxValue(?float $FmaxValue): static
    {
        $this->FmaxValue = $FmaxValue;

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
            $kpi->setParameter($this);
        }

        return $this;
    }

    public function removeKpi(KPI $kpi): static
    {
        if ($this->kpis->removeElement($kpi)) {
            // set the owning side to null (unless already changed)
            if ($kpi->getParameter() === $this) {
                $kpi->setParameter(null);
            }
        }

        return $this;
    }

    public function isKpiExpressionTrue(KPI $kpi): bool
    {
        $expression = $kpi->getExpression();
        $value = $this->getValue();

        $operator = $expression[0];
        $number = substr($expression, 1);

        switch ($operator) {
            case '>':
                return $value > $number;
            case '<':
                return $value < $number;
            case '=':
                return $value == $number;
            default:
                return false;
        }
    }
}
