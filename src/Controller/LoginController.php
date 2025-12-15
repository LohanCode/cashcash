<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
{
    // Si l'utilisateur est déjà connecté, on le déconnecte
    if ($this->getUser() && $request->query->get('action') === 'logout') {
        $this->get('security.token_storage')->setToken(null);
        $request->getSession()->invalidate();
        return $this->redirectToRoute('app_login');
    }

    // Gestion de la connexion normale
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('login.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
}
}