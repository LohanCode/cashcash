<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')] // Nom de la table en base de données
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    public const TYPE_EMPLOYE = 'employe';
    public const TYPE_TECHNICIEN = 'technicien';
    public const TYPE_ADMIN = 'admin';

    #[ORM\Column(length: 50)]
    private string $type_utilisateur = self::TYPE_EMPLOYE;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Agence $agence = null;

    #[ORM\OneToMany(mappedBy: 'technicien', targetEntity: Intervention::class)]
    private Collection $interventionsAffectees;

    #[ORM\OneToMany(mappedBy: 'technicien', targetEntity: Intervention::class)]
    private Collection $interventionsRealisees;

    public function __construct()
    {
        $this->roles = [];
        $this->interventionsAffectees = new ArrayCollection();
        $this->interventionsRealisees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password ?? '';
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function eraseCredentials(): void
    {
    }

    public function getTypeUtilisateur(): string
    {
        return $this->type_utilisateur;
    }

    public function setTypeUtilisateur(string $type_utilisateur): self
    {
        $this->type_utilisateur = $type_utilisateur;
        return $this;
    }

    public function isTechnicien(): bool
    {
        return $this->type_utilisateur === self::TYPE_TECHNICIEN;
    }

    public function isEmploye(): bool
    {
        return $this->type_utilisateur === self::TYPE_EMPLOYE;
    }

    public function isAdmin(): bool
    {
        return $this->type_utilisateur === self::TYPE_ADMIN;
    }

    /**
     * @return Collection|Intervention[]
     */
    public function getInterventionsAffectees(): Collection
    {
        return $this->interventionsAffectees;
    }

    public function addInterventionAffectee(Intervention $intervention): self
    {
        if (!$this->interventionsAffectees->contains($intervention)) {
            $this->interventionsAffectees[] = $intervention;
            $intervention->setTechnicien($this);
        }

        return $this;
    }

    public function removeInterventionAffectee(Intervention $intervention): self
    {
        if ($this->interventionsAffectees->removeElement($intervention)) {
            if ($intervention->getTechnicien() === $this) {
                $intervention->setTechnicien(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Intervention[]
     */
    public function getInterventionsRealisees(): Collection
    {
        return $this->interventionsRealisees;
    }

    public function addInterventionRealisee(Intervention $intervention): self
    {
        if (!$this->interventionsRealisees->contains($intervention)) {
            $this->interventionsRealisees[] = $intervention;
            $intervention->setTechnicien($this);
        }

        return $this;
    }

    public function removeInterventionRealisee(Intervention $intervention): self
    {
        if ($this->interventionsRealisees->removeElement($intervention)) {
            if ($intervention->getTechnicien() === $this) {
                $intervention->setTechnicien(null);
            }
        }

        return $this;
    }
}