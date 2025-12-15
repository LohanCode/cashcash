<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/tech', name: 'tech_home')]
    public function techHome(): Response
    {
        // Page d'accueil technicien : lecture seule
        return $this->render('tech/index.html.twig', [
            'readonly' => true,
        ]);
    }

    #[Route('/tech/interventions', name: 'intervention_mes')]
    public function mesInterventions(): Response
    {
        // Page gérant : actions possibles
        return $this->render('tech/interventions.html.twig');
    }

    #[Route('/tech/interventions/historique', name: 'intervention_historique')]
    public function historique(): Response
    {
        return $this->render('tech/historique.html.twig', [
            'readonly' => false,
        ]);
    }

    #[Route('/tech/recherche-client', name: 'intervention_recherche_client')]
    public function rechercheClient(): Response
    {
        return $this->render('tech/recherche_client.html.twig');
    }
}