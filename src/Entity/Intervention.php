<?php

namespace App\Entity;

use App\Repository\InterventionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterventionRepository::class)]
class Intervention
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Supprime la colonne numIntervenant, remplacée par la relation technicien

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateVisite = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $heureVisite = null;

    // 1. Relation ManyToOne avec Client (le client concerné par l'intervention)
    #[ORM\ManyToOne(inversedBy: 'interventions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    // 2. Relation ManyToOne avec Utilisateur (le Technicien affecté)
    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'interventionsAffectees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $technicien = null;

    // 3. Relation OneToMany avec Controler (la table qui porte les détails du contrôle)
    #[ORM\OneToMany(mappedBy: 'intervention', targetEntity: Controler::class)]
    private Collection $controles;


    public function __construct()
    {
        $this->controles = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    // Le getter/setter pour numIntervenant n'est plus nécessaire

    public function getDateVisite(): ?\DateTimeInterface
    {
        return $this->dateVisite;
    }

    public function setDateVisite(?\DateTimeInterface $dateVisite): static
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    public function getHeureVisite(): ?\DateTimeInterface
    {
        return $this->heureVisite;
    }

    public function setHeureVisite(?\DateTimeInterface $heureVisite): static
    {
        $this->heureVisite = $heureVisite;

        return $this;
    }

    // -----------------------------------------------------------
    // Getters/Setters pour les Relations
    // -----------------------------------------------------------

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getTechnicien(): ?Utilisateur
    {
        return $this->technicien;
    }

    public function setTechnicien(?Utilisateur $technicien): static
    {
        $this->technicien = $technicien;

        return $this;
    }

    /**
     * @return Collection<int, Controler>
     */
    public function getControles(): Collection
    {
        return $this->controles;
    }

    public function addControle(Controler $controle): static
    {
        if (!$this->controles->contains($controle)) {
            $this->controles->add($controle);
            $controle->setIntervention($this);
        }

        return $this;
    }

    public function removeControle(Controler $controle): static
    {
        if ($this->controles->removeElement($controle)) {
            // set the owning side to null (unless already changed)
            if ($controle->getIntervention() === $this) {
                $controle->setIntervention(null);
            }
        }

        return $this;
    }
}