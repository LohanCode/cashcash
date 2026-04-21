<?php

namespace App\Controller;

use App\Entity\Intervention;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    #[Route('/intervention/{id}/pdf', name: 'app_intervention_pdf')]
    public function gererPdf(Intervention $intervention): Response
    {
        // 1. Configurer Dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isRemoteEnabled', true); // Pour gérer les images externes si besoin

        $dompdf = new Dompdf($pdfOptions);

        // 2. Générer le HTML depuis un template Twig
        $html = $this->renderView('intervention/fiche_pdf.html.twig', [
            'intervention' => $intervention,
        ]);

        // 3. Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);

        // 4. Définir la taille du papier (A4, portrait)
        $dompdf->setPaper('A4', 'portrait');

        // 5. Rendre le PDF (générer)
        $dompdf->render();

        // 6. Générer le contenu du pdf
        $pdfOutput = $dompdf->output();

        // Renvoyer le fichier PDF proprement dans une réponse de Symfony
        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Fiche_Intervention_' . $intervention->getId() . '.pdf"',
        ]);
    }
}
