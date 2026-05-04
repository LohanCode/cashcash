<?php

namespace App\Entity;

use App\Repository\TypeMaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeMaterielRepository::class)]
class TypeMateriel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $refInterne = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleTypeMateriel = null; // Ajout du libellé

    // 1. Relation ManyToOne avec Famille
    #[ORM\ManyToOne(inversedBy: 'typeMateriels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Famille $famille = null;

    // 2. Relation OneToMany avec Materiel
    #[ORM\OneToMany(mappedBy: 'typeMateriel', targetEntity: Materiel::class)]
    private Collection $materiels;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefInterne(): ?string
    {
        return $this->refInterne;
    }

    public function setRefInterne(string $refInterne): static
    {
        $this->refInterne = $refInterne;

        return $this;
    }

    public function getLibelleTypeMateriel(): ?string
    {
        return $this->libelleTypeMateriel;
    }

    public function setLibelleTypeMateriel(string $libelleTypeMateriel): static
    {
        $this->libelleTypeMateriel = $libelleTypeMateriel;

        return $this;
    }

    // -----------------------------------------------------------
    // Getters/Setters pour les Relations
    // -----------------------------------------------------------

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): static
    {
        $this->famille = $famille;

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
            $materiel->setTypeMateriel($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): static
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getTypeMateriel() === $this) {
                $materiel->setTypeMateriel(null);
            }
        }

        return $this;
    }
}