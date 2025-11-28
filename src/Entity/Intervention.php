<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numIntervenant = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateVisite = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $heureVisite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumIntervenant(): ?string
    {
        return $this->numIntervenant;
    }

    public function setNumIntervenant(string $numIntervenant): static
    {
        $this->numIntervenant = $numIntervenant;

        return $this;
    }

    public function getDateVisite(): ?\DateTime
    {
        return $this->dateVisite;
    }

    public function setDateVisite(?\DateTime $dateVisite): static
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    public function getHeureVisite(): ?\DateTime
    {
        return $this->heureVisite;
    }

    public function setHeureVisite(?\DateTime $heureVisite): static
    {
        $this->heureVisite = $heureVisite;

        return $this;
    }
}
