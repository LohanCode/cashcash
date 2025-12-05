<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomEmploye = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenomEmploye = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseEmploye = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateEmbauche = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasseGerant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->Matricule;
    }

    public function setMatricule(string $Matricule): static
    {
        $this->Matricule = $Matricule;

        return $this;
    }

    public function getNomEmploye(): ?string
    {
        return $this->nomEmploye;
    }

    public function setNomEmploye(?string $nomEmploye): static
    {
        $this->nomEmploye = $nomEmploye;

        return $this;
    }

    public function getPrenomEmploye(): ?string
    {
        return $this->prenomEmploye;
    }

    public function setPrenomEmploye(?string $prenomEmploye): static
    {
        $this->prenomEmploye = $prenomEmploye;

        return $this;
    }

    public function getAdresseEmploye(): ?string
    {
        return $this->adresseEmploye;
    }

    public function setAdresseEmploye(?string $adresseEmploye): static
    {
        $this->adresseEmploye = $adresseEmploye;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTime
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(?\DateTime $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getMotDePasseGerant(): ?string
    {
        return $this->motDePasseGerant;
    }

    public function setMotDePasseGerant(string $motDePasseGerant): static
    {
        $this->motDePasseGerant = $motDePasseGerant;

        return $this;
    }
}
