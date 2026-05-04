<?php

namespace App\Controller;

use App\Repository\InterventionRepository;
use App\Repository\ClientRepository;
use App\Entity\Intervention;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function techHome(InterventionRepository $interventionRepository): Response
    {
        $user = $this->getUser();
        
        // Récupérer les interventions en cours du technicien connecté
        $interventions = $interventionRepository->createQueryBuilder('i')
            ->where('i.technicien = :user')
            ->andWhere('i.statut IN (:statuts)')
            ->setParameter('user', $user)
            ->setParameter('statuts', ['ouverte', 'en_cours'])
            ->orderBy('i.dateVisite', 'ASC')
            ->getQuery()
            ->getResult();
        
        // Statistiques pour le dashboard
        $stats = [
            'ouvertes' => count(array_filter($interventions, fn($i) => $i->getStatut() === 'ouverte')),
            'en_cours' => count(array_filter($interventions, fn($i) => $i->getStatut() === 'en_cours')),
            'total' => count($interventions),
        ];
        
        return $this->render('tech/index.html.twig', [
            'interventions' => $interventions,
            'stats' => $stats,
            'readonly' => false,
        ]);
    }

    #[Route('/tech/interventions', name: 'intervention_mes')]
    public function mesInterventions(InterventionRepository $interventionRepository): Response
    {
        $user = $this->getUser();
        
        // Toutes les interventions actives du technicien
        $interventions = $interventionRepository->findBy(
            ['technicien' => $user],
            ['dateVisite' => 'DESC']
        );
        
        // Filtrer par statut
        $actives = array_filter($interventions, fn($i) => in_array($i->getStatut(), ['ouverte', 'en_cours']));
        
        return $this->render('tech/mes_interventions.html.twig', [
            'interventions' => $actives,
            'readonly' => false,
        ]);
    }

    #[Route('/tech/intervention/{id}/modifier-statut', name: 'intervention_modifier_statut', methods: ['POST'])]
    public function modifierStatut(
        Intervention $intervention, 
        Request $request, 
        EntityManagerInterface $em
    ): Response
    {
        // Vérifier que c'est bien l'intervention du technicien connecté
        if ($intervention->getTechnicien() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette intervention.');
        }
        
        $nouveauStatut = $request->request->get('statut');
        if (in_array($nouveauStatut, ['ouverte', 'en_cours', 'terminee'])) {
            $intervention->setStatut($nouveauStatut);
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour avec succès.');
        }
        
        return $this->redirectToRoute('intervention_mes');
    }

    #[Route('/tech/interventions/historique', name: 'intervention_historique')]
    public function historique(InterventionRepository $interventionRepository): Response
    {
        $user = $this->getUser();
        
        // Interventions terminées du technicien
        $interventions = $interventionRepository->findBy(
            ['technicien' => $user, 'statut' => 'terminee'],
            ['dateVisite' => 'DESC']
        );
        
        return $this->render('tech/historique.html.twig', [
            'interventions' => $interventions,
            'readonly' => true,
        ]);
    }

    #[Route('/tech/recherche-client', name: 'intervention_recherche_client')]
    public function rechercheClient(Request $request, ClientRepository $clientRepository): Response
    {
        $query = $request->query->get('q', '');
        $clients = [];
        
        if ($query !== '') {
            $clients = $clientRepository->createQueryBuilder('c')
                ->where('c.raisSociale LIKE :query')
                ->orWhere('c.numClient LIKE :query')
                ->orWhere('c.adresseClient LIKE :query')
                ->setParameter('query', '%' . $query . '%')
                ->setMaxResults(20)
                ->getQuery()
                ->getResult();
        }
        
        return $this->render('tech/recherche_client.html.twig', [
            'clients' => $clients,
            'query' => $query,
        ]);
    }
}