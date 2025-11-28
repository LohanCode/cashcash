<?php

namespace App\Entity;

use App\Repository\ContratMaintenanceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContratMaintenanceRepository::class)]
class ContratMaintenance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numContrat = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateSignature = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateEcheance = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumContrat(): ?string
    {
        return $this->numContrat;
    }

    public function setNumContrat(string $numContrat): static
    {
        $this->numContrat = $numContrat;

        return $this;
    }

    public function getDateSignature(): ?\DateTime
    {
        return $this->dateSignature;
    }

    public function setDateSignature(?\DateTime $dateSignature): static
    {
        $this->dateSignature = $dateSignature;

        return $this;
    }

    public function getDateEcheance(): ?\DateTime
    {
        return $this->dateEcheance;
    }

    public function setDateEcheance(?\DateTime $dateEcheance): static
    {
        $this->dateEcheance = $dateEcheance;

        return $this;
    }
}
