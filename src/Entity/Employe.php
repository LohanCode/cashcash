<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EmployeRepository::class)]
class Employe implements UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateEmbauche = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null; // Utilisé par Symfony Security

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    // Relation ManyToOne avec Agence
    #[ORM\ManyToOne(inversedBy: 'employes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Agence $agence = null;

    // Relation OneToMany avec Intervention (interventions affectées à cet employé)
    #[ORM\OneToMany(mappedBy: 'technicien', targetEntity: Intervention::class)]
    private Collection $interventionsAffectees;
    
    // CHAMPS SPÉCIFIQUES AUX TECHNICIENS (Peut être nullable pour les gestionnaires)
    #[ORM\Column(nullable: true)]
    private ?int $telMobile = null; 

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $qualification = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $dateObtentionQualification = null;


    public function __construct()
    {
        $this->interventionsAffectees = new ArrayCollection();
    }
    
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

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(?\DateTimeInterface $dateEmbauche): static
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }
    
    /**
     * @see UserInterface
     */
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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    
    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->Matricule;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si vous utilisez un mot de passe non haché temporaire, effacez-le ici
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

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventionsAffectees(): Collection
    {
        return $this->interventionsAffectees;
    }

    public function addInterventionsAffectee(Intervention $interventionsAffectee): static
    {
        if (!$this->interventionsAffectees->contains($interventionsAffectee)) {
            $this->interventionsAffectees->add($interventionsAffectee);
            $interventionsAffectee->setTechnicien($this);
        }

        return $this;
    }

    public function removeInterventionsAffectee(Intervention $interventionsAffectee): static
    {
        if ($this->interventionsAffectees->removeElement($interventionsAffectee)) {
            if ($interventionsAffectee->getTechnicien() === $this) {
                $interventionsAffectee->setTechnicien(null);
            }
        }

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

    public function getQualification(): ?string
    {
        return $this->qualification;
    }

    public function setQualification(?string $qualification): static
    {
        $this->qualification = $qualification;

        return $this;
    }

    public function getDateObtentionQualification(): ?\DateTimeInterface
    {
        return $this->dateObtentionQualification;
    }

    public function setDateObtentionQualification(?\DateTimeInterface $dateObtentionQualification): static
    {
        $this->dateObtentionQualification = $dateObtentionQualification;

        return $this;
    }
}