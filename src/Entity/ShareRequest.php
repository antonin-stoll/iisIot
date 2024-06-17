<?php

namespace App\Entity;

use App\Repository\ShareRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShareRequestRepository::class)]
class ShareRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shareRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $requester = null;

    #[ORM\ManyToOne(inversedBy: 'shareRequest')]
    #[ORM\JoinColumn(nullable: false)]
    private ?System $system = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequester(): ?Account
    {
        return $this->requester;
    }

    public function setRequester(?Account $requester): static
    {
        $this->requester = $requester;

        return $this;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(?System $system): static
    {
        $this->system = $system;

        return $this;
    }
}
