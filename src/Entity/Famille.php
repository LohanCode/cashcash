<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilleRepository::class)]
class Famille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $codeFamille = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleFamille = null;

    #[ORM\OneToMany(mappedBy: 'famille', targetEntity: TypeMateriel::class)]
    private Collection $typeMateriels;

    public function __construct()
    {
        $this->typeMateriels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeFamille(): ?string
    {
        return $this->codeFamille;
    }

    public function setCodeFamille(string $codeFamille): static
    {
        $this->codeFamille = $codeFamille;

        return $this;
    }

    public function getLibelleFamille(): ?string
    {
        return $this->libelleFamille;
    }

    public function setLibelleFamille(string $libelleFamille): static
    {
        $this->libelleFamille = $libelleFamille;

        return $this;
    }

    /**
     * @return Collection<int, TypeMateriel>
     */
    public function getTypeMateriels(): Collection
    {
        return $this->typeMateriels;
    }

    public function addTypeMateriel(TypeMateriel $typeMateriel): static
    {
        if (!$this->typeMateriels->contains($typeMateriel)) {
            $this->typeMateriels->add($typeMateriel);
            $typeMateriel->setFamille($this);
        }

        return $this;
    }

    public function removeTypeMateriel(TypeMateriel $typeMateriel): static
    {
        if ($this->typeMateriels->removeElement($typeMateriel)) {
            if ($typeMateriel->getFamille() === $this) {
                $typeMateriel->setFamille(null);
            }
        }

        return $this;
    }
}