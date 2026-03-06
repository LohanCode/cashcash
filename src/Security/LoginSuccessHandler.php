<?php

namespace App\Security;

use App\Entity\Utilisateur;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private RouterInterface $router)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();
        
        if (!$user instanceof Utilisateur) {
            return new RedirectResponse($this->router->generate('app_login'));
        }
        
        $type = $user->getTypeUtilisateur();
        
        // Redirection selon le type d'utilisateur
        return match ($type) {
            'technicien' => new RedirectResponse($this->router->generate('tech_home')),
            'gestionnaire' => new RedirectResponse($this->router->generate('app_gerant_accueil')),
            'admin' => new RedirectResponse($this->router->generate('app_admin_choix')),
            default => new RedirectResponse($this->router->generate('app_gerant_accueil')),
        };
    }
}
