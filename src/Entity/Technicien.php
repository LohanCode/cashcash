<?php

namespace App\Entity;

use App\Repository\TechnicienRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TechnicienRepository::class)]
class Technicien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(nullable: true)]
    private ?int $telMobile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qualif = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateObtention = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getTelMobile(): ?int
    {
        return $this->telMobile;
    }

    public function setTelMobile(?int $telMobile): static
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    public function getQualif(): ?string
    {
        return $this->qualif;
    }

    public function setQualif(?string $qualif): static
    {
        $this->qualif = $qualif;

        return $this;
    }

    public function getDateObtention(): ?\DateTime
    {
        return $this->dateObtention;
    }

    public function setDateObtention(?\DateTime $dateObtention): static
    {
        $this->dateObtention = $dateObtention;

        return $this;
    }
}
