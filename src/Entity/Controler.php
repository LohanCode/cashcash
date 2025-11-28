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

    #[ORM\Column(length: 255)]
    private ?string $numSerie = null;

    #[ORM\Column(length: 255)]
    private ?string $numIntervenant = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tempsPasse = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

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

    public function getNumIntervenant(): ?string
    {
        return $this->numIntervenant;
    }

    public function setNumIntervenant(string $numIntervenant): static
    {
        $this->numIntervenant = $numIntervenant;

        return $this;
    }

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
}
