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

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Employe::class)]
    private Collection $employes;

    #[ORM\OneToMany(mappedBy: 'agence', targetEntity: Client::class)]
    private Collection $clients;
    
    public function __construct()
    {
        $this->employes = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }
    
    // Vous devez ajouter les Getters/Setters pour numAgence, nomAgence, adresseAgence, telAgence

    // ... [Reste des Getters/Setters pour les relations] ...
}