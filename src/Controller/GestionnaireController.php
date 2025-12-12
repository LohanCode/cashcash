<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\InterventionRepository;
use App\Repository\EmployeRepository;

class GestionnaireController extends AbstractController
{
    #[Route('/gerant', name: 'app_gerant_accueil')]
    public function index(): Response
    {
        return $this->render('gestionnaire/index.html.twig', [
            'controller_name' => 'GestionnaireController',
        ]);
    }
    
    #[Route('/gestionnaire/affectation-dev', name: 'app_gerant_affectation')]
    public function affectation(InterventionRepository $interventionRepository, EmployeRepository $employeRepository): Response
    {
        return $this->render('gestionnaire/affectation.html.twig', [
             'interventions' => [],
             'techniciens' => [],
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