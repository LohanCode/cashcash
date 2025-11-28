<?php

namespace App\Entity;

use App\Repository\TypeMaterielRepository;
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
}
