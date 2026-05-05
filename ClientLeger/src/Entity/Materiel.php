<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numSerie = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateVente = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $dateInstallation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixVente = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emplacement = null;

    // 1. Relation ManyToOne avec Client
    #[ORM\ManyToOne(inversedBy: 'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;

    // 2. Relation ManyToOne avec TypeMateriel
    #[ORM\ManyToOne(inversedBy: 'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeMateriel $typeMateriel = null;

    // 3. Relation ManyToOne avec ContratMaintenance
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContratMaintenance $contratMaintenance = null;

    // 4. Relation avec la table de jointure Controler non mappée par Doctrine car l'inverse n'existe pas
    private Collection $controlers;

    public function __construct()
    {
        $this->controlers = new ArrayCollection();
    }
    
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

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(?string $emplacement): static
    {
        $this->emplacement = $emplacement;
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

    public function getTypeMateriel(): ?TypeMateriel
    {
        return $this->typeMateriel;
    }

    public function setTypeMateriel(?TypeMateriel $typeMateriel): static
    {
        $this->typeMateriel = $typeMateriel;

        return $this;
    }

    public function getContratMaintenance(): ?ContratMaintenance
    {
        return $this->contratMaintenance;
    }

    public function setContratMaintenance(?ContratMaintenance $contratMaintenance): static
    {
        $this->contratMaintenance = $contratMaintenance;

        return $this;
    }

    /**
     * @return Collection<int, Controler>
     */
    public function getControlers(): Collection
    {
        return $this->controlers;
    }

    public function addControler(Controler $controler): static
    {
        if (!$this->controlers->contains($controler)) {
            $this->controlers->add($controler);
            $controler->setMateriel($this);
        }

        return $this;
    }

    public function removeControler(Controler $controler): static
    {
        if ($this->controlers->removeElement($controler)) {
            // set the owning side to null (unless already changed)
            if ($controler->getMateriel() === $this) {
                $controler->setMateriel(null);
            }
        }

        return $this;
    }
}