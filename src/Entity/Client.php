<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $raisSociale = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?string $siren = null;

    #[ORM\Column(nullable: true)]
    private ?int $codeApe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseClient = null;

    #[ORM\Column(nullable: true)]
    private ?int $telephoneClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailClient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $dureeDeplacement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $distanceKm = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumClient(): ?string
    {
        return $this->numClient;
    }

    public function setNumClient(string $numClient): static
    {
        $this->numClient = $numClient;

        return $this;
    }

    public function getRaisSociale(): ?string
    {
        return $this->raisSociale;
    }

    public function setRaisSociale(?string $raisSociale): static
    {
        $this->raisSociale = $raisSociale;

        return $this;
    }

    public function getSiren(): ?string
    {
        return $this->siren;
    }

    public function setSiren(?string $siren): static
    {
        $this->siren = $siren;

        return $this;
    }

    public function getCodeApe(): ?int
    {
        return $this->codeApe;
    }

    public function setCodeApe(?int $codeApe): static
    {
        $this->codeApe = $codeApe;

        return $this;
    }

    public function getAdresseClient(): ?string
    {
        return $this->adresseClient;
    }

    public function setAdresseClient(?string $adresseClient): static
    {
        $this->adresseClient = $adresseClient;

        return $this;
    }

    public function getTelephoneClient(): ?int
    {
        return $this->telephoneClient;
    }

    public function setTelephoneClient(?int $telephoneClient): static
    {
        $this->telephoneClient = $telephoneClient;

        return $this;
    }

    public function getEmailClient(): ?string
    {
        return $this->emailClient;
    }

    public function setEmailClient(?string $emailClient): static
    {
        $this->emailClient = $emailClient;

        return $this;
    }

    public function getDureeDeplacement(): ?string
    {
        return $this->dureeDeplacement;
    }

    public function setDureeDeplacement(?string $dureeDeplacement): static
    {
        $this->dureeDeplacement = $dureeDeplacement;

        return $this;
    }

    public function getDistanceKm(): ?string
    {
        return $this->distanceKm;
    }

    public function setDistanceKm(?string $distanceKm): static
    {
        $this->distanceKm = $distanceKm;

        return $this;
    }
}
