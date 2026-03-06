<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\ClientRepository;
use App\Repository\AgenceRepository;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class GestionnaireController extends AbstractController
{
    #[Route('/gerant', name: 'app_gerant_accueil')]
    public function index(): Response
    {
        return $this->render('gestionnaire/index.html.twig', [
            'controller_name' => 'GestionnaireController',
        ]);
    }
    
    #[Route('/gestionnaire/affectation-dev', name: 'app_gerant_affectation', methods: ['GET', 'POST'])]
    public function affectation(
        Request $request,
        InterventionRepository $interventionRepository,
        UtilisateurRepository $utilisateurRepository,
        ClientRepository $clientRepository,
        EntityManagerInterface $em
    ): Response {
if ($request->isMethod('POST')) {
    $interventionId = $request->request->get('intervention_id');
    $technicienId   = $request->request->get('technicien_id');
    $clientId       = $request->request->get('client_id');
    $dateVisiteStr  = $request->request->get('date_visite');
    $heureVisiteStr = $request->request->get('heure_visite');

    if ($interventionId && $technicienId) {
        $intervention = $interventionRepository->find($interventionId);
        // Vérification que le technicien existe ET est bien de type technicien
        $technicien = $utilisateurRepository->findOneBy([
            'id' => $technicienId,
            'type_utilisateur' => 'technicien'
        ]);
        
        if (!$intervention) {
            $this->addFlash('danger', 'Intervention introuvable.');
            return $this->redirectToRoute('app_gerant_affectation');
        }

        if (!$technicien) {
            $this->addFlash('danger', 'Technicien non trouvé ou non autorisé.');
            return $this->redirectToRoute('app_gerant_affectation');
        }

        // Si on arrive ici, intervention et technicien sont valides
        $intervention->setTechnicien($technicien);
        
        // Gestion du client
        if ($clientId) {
            $client = $clientRepository->find($clientId);
            if ($client) {
                $intervention->setClient($client);
            } else {
                $this->addFlash('warning', 'Client non trouvé, l\'intervention a été mise à jour sans modification du client.');
            }
        }

        // Gestion de la date de visite
        if (!empty($dateVisiteStr)) {
            try {
                $dateVisite = \DateTime::createFromFormat('Y-m-d', $dateVisiteStr);
                if ($dateVisite === false) {
                    throw new \Exception('Format invalide');
                }
                $intervention->setDateVisite($dateVisite);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Format de date invalide. Utilisez le format AAAA-MM-JJ.');
            }
        }

        // Gestion de l'heure de visite
        if (!empty($heureVisiteStr)) {
            try {
                $heureVisite = \DateTime::createFromFormat('H:i', $heureVisiteStr);
                if ($heureVisite === false) {
                    throw new \Exception('Format invalide');
                }
                $intervention->setHeureVisite($heureVisite);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Format d\'heure invalide. Utilisez le format HH:MM.');
            }
        }

        try {
            $em->flush();
            $this->addFlash('success', 'Intervention affectée au technicien avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Une erreur est survenue lors de la mise à jour de l\'intervention.');
        }

    } else {
        $this->addFlash('danger', 'Données d\'affectation incomplètes. Veuillez sélectionner une intervention et un technicien.');
    }

    return $this->redirectToRoute('app_gerant_affectation');
}

        $interventions = $interventionRepository->findAll();
        $techniciens = $utilisateurRepository->findBy(['type_utilisateur' => 'technicien']);
        $clients       = $clientRepository->findAll();

        return $this->render('gestionnaire/affectation.html.twig', [
            'interventions' => $interventions,
            'techniciens'   => $techniciens,
            'clients'       => $clients,
        ]);
    }

    #[Route('/gerant/statistique', name: 'app_gerant_statistiques')]
    public function statistiques(
        InterventionRepository $interventionRepository,
        UtilisateurRepository $utilisateurRepository,
        ClientRepository $clientRepository
    ): Response
    {
        $interventions = $interventionRepository->findAll();
        $techniciens = $utilisateurRepository->findBy(['type_utilisateur' => 'technicien']);
        $clients = $clientRepository->findAll();
        
        return $this->render('gestionnaire/statistiques.html.twig', [
            'total_interventions' => count($interventions),
            'total_techniciens' => count($techniciens),
            'total_clients' => count($clients),
            'interventions' => $interventions,
            'techniciens' => $techniciens,
        ]);
    }

    // ========== GESTION DU PERSONNEL ==========

    #[Route('/gerant/personnel', name: 'app_gerant_personnel')]
    public function personnel(
        UtilisateurRepository $utilisateurRepository,
        AgenceRepository $agenceRepository,
        Request $request
    ): Response {
        $typeFiltre = $request->query->get('type', 'tous');
        
        if ($typeFiltre === 'tous') {
            $utilisateurs = $utilisateurRepository->findAll();
        } else {
            $utilisateurs = $utilisateurRepository->findBy(['type_utilisateur' => $typeFiltre]);
        }
        
        $agences = $agenceRepository->findAll();
        
        return $this->render('gestionnaire/personnel.html.twig', [
            'utilisateurs' => $utilisateurs,
            'agences' => $agences,
            'typeFiltre' => $typeFiltre,
        ]);
    }

    #[Route('/gerant/personnel/nouveau', name: 'app_gerant_personnel_nouveau', methods: ['POST'])]
    public function personnelNouveau(
        Request $request,
        EntityManagerInterface $em,
        AgenceRepository $agenceRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $matricule = $request->request->get('matricule');
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $type = $request->request->get('type_utilisateur');
        $agenceId = $request->request->get('agence_id');
        
        if (!$matricule || !$nom || !$prenom || !$email || !$password || !$type || !$agenceId) {
            $this->addFlash('danger', 'Tous les champs sont obligatoires.');
            return $this->redirectToRoute('app_gerant_personnel');
        }
        
        $agence = $agenceRepository->find($agenceId);
        if (!$agence) {
            $this->addFlash('danger', 'Agence introuvable.');
            return $this->redirectToRoute('app_gerant_personnel');
        }
        
        $utilisateur = new Utilisateur();
        $utilisateur->setMatricule($matricule);
        $utilisateur->setNom($nom);
        $utilisateur->setPrenom($prenom);
        $utilisateur->setEmail($email);
        $utilisateur->setTypeUtilisateur($type);
        $utilisateur->setAgence($agence);
        
        // Définir les rôles selon le type
        $roles = ['ROLE_USER'];
        if ($type === 'admin') {
            $roles[] = 'ROLE_ADMIN';
        } elseif ($type === 'technicien') {
            $roles[] = 'ROLE_TECH';
        } elseif ($type === 'gestionnaire') {
            $roles[] = 'ROLE_GERANT';
        }
        $utilisateur->setRoles($roles);
        
        // Hasher le mot de passe
        $hashedPassword = $passwordHasher->hashPassword($utilisateur, $password);
        $utilisateur->setPassword($hashedPassword);
        
        try {
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de la création : email déjà utilisé ?');
        }
        
        return $this->redirectToRoute('app_gerant_personnel');
    }

    #[Route('/gerant/personnel/{id}/modifier', name: 'app_gerant_personnel_modifier', methods: ['POST'])]
    public function personnelModifier(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UtilisateurRepository $utilisateurRepository,
        AgenceRepository $agenceRepository,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $utilisateur = $utilisateurRepository->find($id);
        
        if (!$utilisateur) {
            $this->addFlash('danger', 'Utilisateur introuvable.');
            return $this->redirectToRoute('app_gerant_personnel');
        }
        
        $matricule = $request->request->get('matricule');
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $type = $request->request->get('type_utilisateur');
        $agenceId = $request->request->get('agence_id');
        
        if ($matricule) $utilisateur->setMatricule($matricule);
        if ($nom) $utilisateur->setNom($nom);
        if ($prenom) $utilisateur->setPrenom($prenom);
        if ($email) $utilisateur->setEmail($email);
        
        if ($type) {
            $utilisateur->setTypeUtilisateur($type);
            // Mettre à jour les rôles
            $roles = ['ROLE_USER'];
            if ($type === 'admin') {
                $roles[] = 'ROLE_ADMIN';
            } elseif ($type === 'technicien') {
                $roles[] = 'ROLE_TECH';
            } elseif ($type === 'gestionnaire') {
                $roles[] = 'ROLE_GERANT';
            }
            $utilisateur->setRoles($roles);
        }
        
        if ($agenceId) {
            $agence = $agenceRepository->find($agenceId);
            if ($agence) {
                $utilisateur->setAgence($agence);
            }
        }
        
        // Seulement si un nouveau mot de passe est fourni
        if ($password && strlen($password) > 0) {
            $hashedPassword = $passwordHasher->hashPassword($utilisateur, $password);
            $utilisateur->setPassword($hashedPassword);
        }
        
        try {
            $em->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Erreur lors de la modification.');
        }
        
        return $this->redirectToRoute('app_gerant_personnel');
    }

    #[Route('/gerant/personnel/{id}/supprimer', name: 'app_gerant_personnel_supprimer', methods: ['POST'])]
    public function personnelSupprimer(
        int $id,
        EntityManagerInterface $em,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $utilisateur = $utilisateurRepository->find($id);
        
        if (!$utilisateur) {
            $this->addFlash('danger', 'Utilisateur introuvable.');
            return $this->redirectToRoute('app_gerant_personnel');
        }
        
        try {
            $em->remove($utilisateur);
            $em->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Impossible de supprimer cet utilisateur (interventions liées ?).');
        }
        
        return $this->redirectToRoute('app_gerant_personnel');
    }
}