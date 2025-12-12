<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use App\Repository\EmployeRepository;
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
        EmployeRepository $employeRepository,
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
                $technicien   = $employeRepository->find($technicienId);
                $client       = $clientId ? $clientRepository->find($clientId) : null;

                if ($intervention && $technicien) {
                    $intervention->setTechnicien($technicien);
                    if ($client) {
                        $intervention->setClient($client);
                    }

                    if (!empty($dateVisiteStr)) {
                        try {
                            $dateVisite = new \DateTime($dateVisiteStr);
                            $intervention->setDateVisite($dateVisite);
                        } catch (\Exception $e) {
                            $this->addFlash('danger', 'Format de date invalide.');
                        }
                    }

                    if (!empty($heureVisiteStr)) {
                        try {
                            $heureVisite = new \DateTime($heureVisiteStr);
                            $intervention->setHeureVisite($heureVisite);
                        } catch (\Exception $e) {
                            $this->addFlash('danger', 'Format d\'heure invalide.');
                        }
                    }
                    $em->flush();
                    $this->addFlash('success', 'Intervention affectée au technicien.');

                } else {
                    $this->addFlash('danger', "Intervention ou technicien introuvable.");
                }
            } else {
                $this->addFlash('danger', 'Données d\'affectation incomplètes.');
            }

            return $this->redirectToRoute('app_gerant_affectation');
        }

        $interventions = $interventionRepository->findAll();
        $techniciens   = $employeRepository->findAll();
        $clients       = $clientRepository->findAll();

        return $this->render('gestionnaire/affectation.html.twig', [
            'interventions' => $interventions,
            'techniciens'   => $techniciens,
            'clients'       => $clients,
        ]);
    }

    #[Route('/gestionnaire/statistiques-dev', name: 'app_gerant_statistiques')]
    public function statistiques(): Response
    {
        return $this->render('gestionnaire/statistiques.html.twig', [
            'stats' => [],
        ]);
    }
}