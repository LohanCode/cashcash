<?php

namespace App\Entity;

use App\Repository\TypeContratRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeContratRepository::class)]
class TypeContrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $RefTypeContrat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $DelaiIntervention = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tauxApplicable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefTypeContrat(): ?string
    {
        return $this->RefTypeContrat;
    }

    public function setRefTypeContrat(string $RefTypeContrat): static
    {
        $this->RefTypeContrat = $RefTypeContrat;

        return $this;
    }

    public function getDelaiIntervention(): ?string
    {
        return $this->DelaiIntervention;
    }

    public function setDelaiIntervention(?string $DelaiIntervention): static
    {
        $this->DelaiIntervention = $DelaiIntervention;

        return $this;
    }

    public function getTauxApplicable(): ?string
    {
        return $this->tauxApplicable;
    }

    public function setTauxApplicable(?string $tauxApplicable): static
    {
        $this->tauxApplicable = $tauxApplicable;

        return $this;
    }
}
