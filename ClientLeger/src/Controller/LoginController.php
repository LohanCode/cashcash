<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $type = $user->getTypeUtilisateur();
            
            return match ($type) {
                'technicien' => $this->redirectToRoute('tech_home'),
                'gestionnaire' => $this->redirectToRoute('app_gerant_accueil'),
                'admin' => $this->redirectToRoute('app_admin_choix'),
                default => $this->redirectToRoute('app_gerant_accueil'),
            };
        }
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Ce code ne sera jamais exécuté, Symfony intercepte la route
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/admin/choix', name: 'app_admin_choix')]
    public function adminChoix(): Response
    {
        // Vérifier que l'utilisateur est admin
        $user = $this->getUser();
        if (!$user || $user->getTypeUtilisateur() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('admin/choix.html.twig');
    }
}