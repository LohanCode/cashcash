<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    
    // 1. Relation ManyToOne avec Agence
    #[ORM\ManyToOne(inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)] // Un client DOIT être rattaché à une agence
    private ?Agence $agence = null;

    // 2. Relation OneToMany avec Materiel
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Materiel::class)]
    private Collection $materiels;

    // 3. Relation avec ContratMaintenance (non mappée par Doctrine car l'inverse n'existe pas)
    private Collection $contrats;

    // 4. Relation OneToMany avec Intervention
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Intervention::class)]
    private Collection $interventions;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
        $this->contrats = new ArrayCollection();
        $this->interventions = new ArrayCollection();
    }
    
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

    // -----------------------------------------------------------
    // Getters/Setters pour les Relations
    // -----------------------------------------------------------

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
     * @return Collection<int, Materiel>
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): static
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels->add($materiel);
            $materiel->setClient($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getClient() === $this) {
                $materiel->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ContratMaintenance>
     */
    public function getContrats(): Collection
    {
        return $this->contrats;
    }

    public function addContrat(ContratMaintenance $contrat): static
    {
        if (!$this->contrats->contains($contrat)) {
            $this->contrats->add($contrat);
            $contrat->setClient($this);
        }

        return $this;
    }

    public function removeContrat(ContratMaintenance $contrat): static
    {
        if ($this->contrats->removeElement($contrat)) {
            // set the owning side to null (unless already changed)
            if ($contrat->getClient() === $this) {
                $contrat->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Intervention>
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): static
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions->add($intervention);
            $intervention->setClient($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): static
    {
        if ($this->interventions->removeElement($intervention)) {
            // set the owning side to null (unless already changed)
            if ($intervention->getClient() === $this) {
                $intervention->setClient(null);
            }
        }

        return $this;
    }
}