<?php

namespace App\DataFixtures;

use App\Entity\Agence;
use App\Entity\Client;
use App\Entity\Intervention;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Créer une agence
        $agence = new Agence();
        $agence->setNumAgence('AG001');
        $agence->setNomAgence('Agence Paris Centre');
        $agence->setAdresseAgence('15 Rue de la République, 75001 Paris');
        $manager->persist($agence);

        $agence2 = new Agence();
        $agence2->setNumAgence('AG002');
        $agence2->setNomAgence('Agence Lyon');
        $agence2->setAdresseAgence('8 Place Bellecour, 69002 Lyon');
        $manager->persist($agence2);

        // Créer des techniciens
        $techniciens = [];
        $techData = [
            ['Martin', 'Pierre', 'pierre.martin@cashcash.fr'],
            ['Dubois', 'Marie', 'marie.dubois@cashcash.fr'],
            ['Bernard', 'Lucas', 'lucas.bernard@cashcash.fr'],
            ['Petit', 'Emma', 'emma.petit@cashcash.fr'],
        ];

        foreach ($techData as $i => $data) {
            $tech = new Utilisateur();
            $tech->setMatricule(sprintf('TECH%03d', $i + 1));
            $tech->setNom($data[0]);
            $tech->setPrenom($data[1]);
            $tech->setEmail($data[2]);
            $tech->setTypeUtilisateur('technicien');
            $tech->setPassword($this->passwordHasher->hashPassword($tech, 'password123'));
            $tech->setRoles(['ROLE_TECH']);
            $tech->setAgence($i < 2 ? $agence : $agence2);
            $manager->persist($tech);
            $techniciens[] = $tech;
        }

        // Créer un gestionnaire
        $gerant = new Utilisateur();
        $gerant->setMatricule('GEST001');
        $gerant->setNom('Dupont');
        $gerant->setPrenom('Jean');
        $gerant->setEmail('jean.dupont@cashcash.fr');
        $gerant->setTypeUtilisateur('gestionnaire');
        $gerant->setPassword($this->passwordHasher->hashPassword($gerant, 'admin123'));
        $gerant->setRoles(['ROLE_ADMIN']);
        $gerant->setAgence($agence);
        $manager->persist($gerant);

        // Créer des clients
        $clients = [];
        $clientData = [
            ['CLI001', 'Boulangerie Martin', '12345678901234', 1071, '23 Rue du Pain, 75003 Paris', 145678901, 'contact@boulangerie-martin.fr', '25', '8.5'],
            ['CLI002', 'Pharmacie Centrale', '98765432109876', 4773, '45 Avenue de la Santé, 75014 Paris', 156789012, 'info@pharmacie-centrale.fr', '15', '5.2'],
            ['CLI003', 'Restaurant Le Gourmet', '45678901234567', 5610, '8 Place du Marché, 69001 Lyon', 478901234, 'reservation@legourmet.fr', '40', '15.0'],
            ['CLI004', 'Garage Dupuis', '11223344556677', 4520, '112 Route Nationale, 69003 Lyon', 478112233, 'contact@garage-dupuis.fr', '30', '12.3'],
            ['CLI005', 'Librairie Pages', '99887766554433', 4761, '5 Rue des Livres, 75005 Paris', 143556677, 'librairie@pages.fr', '20', '6.8'],
        ];

        foreach ($clientData as $index => $data) {
            $client = new Client();
            $client->setNumClient($data[0]);
            $client->setRaisSociale($data[1]);
            $client->setSiren($data[2]);
            $client->setCodeApe($data[3]);
            $client->setAdresseClient($data[4]);
            $client->setTelephoneClient($data[5]);
            $client->setEmailClient($data[6]);
            $client->setDureeDeplacement($data[7]);
            $client->setDistanceKm($data[8]);
            $client->setAgence($index < 2 ? $agence : $agence2);
            $manager->persist($client);
            $clients[] = $client;
        }

        // Créer des interventions
        $interventionData = [
            ['2026-03-05', '09:00', 0, 0],
            ['2026-03-05', '14:30', 1, 1],
            ['2026-03-06', '10:00', 2, 2],
            ['2026-03-07', '11:00', 0, 3],
            ['2026-03-07', '15:00', 3, 4],
            ['2026-03-08', '09:30', 1, 0],
            ['2026-03-10', '08:00', 2, 1],
            ['2026-03-10', '16:00', 3, 2],
            ['2026-03-11', '10:30', 0, 3],
        ];

        foreach ($interventionData as $data) {
            $intervention = new Intervention();
            $intervention->setDateVisite(new \DateTime($data[0]));
            $intervention->setHeureVisite(new \DateTime($data[1]));
            $intervention->setTechnicien($techniciens[$data[2]]);
            $intervention->setClient($clients[$data[3]]);
            $manager->persist($intervention);
        }

        $manager->flush();
    }
}
