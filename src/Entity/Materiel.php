<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numSerie = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateVente = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateInstallation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emplacement = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumSerie(): ?string
    {
        return $this->numSerie;
    }

    public function setNumSerie(string $numSerie): static
    {
        $this->numSerie = $numSerie;

        return $this;
    }

    public function getDateVente(): ?\DateTime
    {
        return $this->dateVente;
    }

    public function setDateVente(?\DateTime $dateVente): static
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getDateInstallation(): ?\DateTime
    {
        return $this->dateInstallation;
    }

    public function setDateInstallation(?\DateTime $dateInstallation): static
    {
        $this->dateInstallation = $dateInstallation;

        return $this;
    }

    public function getPrixVente(): ?string
    {
        return $this->prixVente;
    }

    public function setPrixVente(?string $prixVente): static
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

}
