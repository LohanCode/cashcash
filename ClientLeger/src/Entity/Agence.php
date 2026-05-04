<?php

namespace App\Entity;

use App\Repository\AgenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgenceRepository::class)]
class Agence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // AJOUT DES ATTRIBUTS MANQUANTS ICI
    #[ORM\Column(length: 255)]
    private ?string $numAgence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomAgence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseAgence = null;

    #[ORM\Column(nullable: true)]
    private ?int $telAgence = null;
    // FIN DES AJOUTS

    // Relation avec les utilisateurs (techniciens et employés administratifs)
    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Utilisateur::class, orphanRemoval: true)]
    private Collection $utilisateurs;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Client::class)]
    private Collection $clients;
    
    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }
    
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

    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): static
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->setAgence($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): static
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            if ($utilisateur->getAgence() === $this) {
                $utilisateur->setAgence(null);
            }
        }

        return $this;
    }

    // Méthodes de compatibilité pour la rétrocompatibilité
    public function getEmployes(): Collection
    {
        return $this->utilisateurs;
    }

    public function addEmploye(Utilisateur $employe): static
    {
        return $this->addUtilisateur($employe);
    }

    public function removeEmploye(Utilisateur $employe): static
    {
        return $this->removeUtilisateur($employe);
    }

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setAgence($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            if ($client->getAgence() === $this) {
                $client->setAgence(null);
            }
        }

        return $this;
    }
}