<?php

namespace App\Entity;

use App\Repository\ControlerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControlerRepository::class)]
class Controler
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Intervention::class)]
    #[ORM\JoinColumn(name: "num_intervenant", referencedColumnName: "id", nullable: false)]
    private ?Intervention $intervention = null;

    #[ORM\ManyToOne(targetEntity: Materiel::class)]
    #[ORM\JoinColumn(name: "num_serie", referencedColumnName: "num_serie", nullable: false)]
    private ?Materiel $materiel = null;

    #[ORM\Column(name: "temps_passe", length: 255, nullable: true)]
    private ?string $tempsPasse = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    public function getTempsPasse(): ?string
    {
        return $this->tempsPasse;
    }

    public function setTempsPasse(?string $tempsPasse): static
    {
        $this->tempsPasse = $tempsPasse;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getIntervention(): ?Intervention
    {
        return $this->intervention;
    }

    public function setIntervention(?Intervention $intervention): static
    {
        $this->intervention = $intervention;
        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): static
    {
        $this->materiel = $materiel;
        return $this;
    }
}
