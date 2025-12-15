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
    public function statistiques(): Response
    {
        return $this->render('gerant/statistiques.html.twig', [
            'stats' => [],
        ]);
    }
}