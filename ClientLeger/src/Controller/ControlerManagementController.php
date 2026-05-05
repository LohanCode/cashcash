<?php

namespace App\Controller;

use App\Entity\Intervention;
use App\Entity\Controler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/gestionnaire/controler')]
class ControlerManagementController extends AbstractController
{
    #[Route('/saisie/{id}', name: 'app_controler_saisie', methods: ['GET', 'POST'])]
    public function saisie(
        Intervention $intervention,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $client    = $intervention->getClient();
        $materiels = $client->getMateriels();

        if ($request->isMethod('POST')) {
            // Récupération sécurisée des données POST
            $allPost = $request->request->all();
            $data    = (isset($allPost['controles']) && is_array($allPost['controles']))
                ? $allPost['controles']
                : [];

            foreach ($materiels as $materiel) {
                $numSerie = $materiel->getNumSerie();

                // Valeurs soumises (vides par défaut si non saisies)
                $tempsPasse  = isset($data[$numSerie]['tempsPasse'])  ? trim($data[$numSerie]['tempsPasse'])  : null;
                $commentaire = isset($data[$numSerie]['commentaire']) ? trim($data[$numSerie]['commentaire']) : null;

                // Chercher ou créer l'enregistrement via SQL natif pour éviter les problèmes Doctrine
                $conn = $entityManager->getConnection();
                $existing = $conn->fetchAssociative(
                    'SELECT id FROM controler WHERE num_serie = ? AND num_intervenant = ?',
                    [$numSerie, $intervention->getId()]
                );

                if ($existing) {
                    // Mise à jour
                    $conn->executeStatement(
                        'UPDATE controler SET temps_passe = ?, commentaire = ? WHERE id = ?',
                        [$tempsPasse, $commentaire, $existing['id']]
                    );
                } else {
                    // Insertion
                    $conn->executeStatement(
                        'INSERT INTO controler (num_serie, num_intervenant, temps_passe, commentaire) VALUES (?, ?, ?, ?)',
                        [$numSerie, $intervention->getId(), $tempsPasse, $commentaire]
                    );
                }
            }

            $this->addFlash('success', 'Comptes-rendus de contrôle enregistrés avec succès !');
            return $this->redirectToRoute('app_gerant_affectation');
        }

        // Pré-charger les controles existants pour les afficher dans le formulaire
        $controles = [];
        $conn = $entityManager->getConnection();
        foreach ($materiels as $materiel) {
            $numSerie = $materiel->getNumSerie();
            $row = $conn->fetchAssociative(
                'SELECT temps_passe, commentaire FROM controler WHERE num_serie = ? AND num_intervenant = ?',
                [$numSerie, $intervention->getId()]
            );
            $controles[$numSerie] = $row ?: ['temps_passe' => '', 'commentaire' => ''];
        }

        return $this->render('gestionnaire/controler_saisie.html.twig', [
            'intervention' => $intervention,
            'client'       => $client,
            'materiels'    => $materiels,
            'controles'    => $controles,
        ]);
    }
}
