<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numAgence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomAgence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseAgence = null;

    #[ORM\Column(nullable: true)]
    private ?int $telAgence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumAgence(): ?string
    {
        return $this->numAgence;
    }

    public function setNumAgence(string $numAgence): static
    {
        $this->numAgence = $numAgence;

        return $this;
    }

    public function getNomAgence(): ?string
    {
        return $this->nomAgence;
    }

    public function setNomAgence(?string $nomAgence): static
    {
        $this->nomAgence = $nomAgence;

        return $this;
    }

    public function getAdresseAgence(): ?string
    {
        return $this->adresseAgence;
    }

    public function setAdresseAgence(?string $adresseAgence): static
    {
        $this->adresseAgence = $adresseAgence;

        return $this;
    }

    public function getTelAgence(): ?int
    {
        return $this->telAgence;
    }

    public function setTelAgence(?int $telAgence): static
    {
        $this->telAgence = $telAgence;

        return $this;
    }
}
