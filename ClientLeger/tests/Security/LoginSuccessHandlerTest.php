<?php

namespace App\Tests\Security;

use App\Entity\Utilisateur;
use App\Security\LoginSuccessHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Tests unitaires du LoginSuccessHandler.
 *
 * Ce composant est CRITIQUE : il décide où rediriger l'utilisateur
 * après une connexion réussie. Un bug ici = mauvais accès aux interfaces.
 *
 * On utilise des "Mocks" (faux objets) pour simuler le Router et le Token
 * de Symfony sans avoir besoin de lancer tout le framework.
 */
class LoginSuccessHandlerTest extends TestCase
{
    private RouterInterface $router;
    private TokenInterface $token;
    private Request $request;

    /**
     * setUp() est appelée automatiquement avant CHAQUE test.
     * On y prépare les objets réutilisables (mocks).
     */
    protected function setUp(): void
    {
        // On crée un "faux" Router qui simule la génération d'URLs
        $this->router = $this->createMock(RouterInterface::class);

        // On crée un "faux" Token d'authentification Symfony
        $this->token = $this->createMock(TokenInterface::class);

        // Une requête HTTP vide (on n'en a pas besoin pour ces tests)
        $this->request = new Request();
    }

    // ─────────────────────────────────────────────
    // 1. REDIRECTION D'UN TECHNICIEN
    // ─────────────────────────────────────────────

    public function testTechnicienEstRedirigéVersEspaceTech(): void
    {
        $user = new Utilisateur();
        $user->setTypeUtilisateur('technicien');

        // On dit au faux Token : quand on demande l'utilisateur, renvoie notre technicien
        $this->token->method('getUser')->willReturn($user);

        // On dit au faux Router : quand on génère 'tech_home', renvoie cette URL
        $this->router->method('generate')
            ->with('tech_home')
            ->willReturn('/tech');

        $handler = new LoginSuccessHandler($this->router);
        $response = $handler->onAuthenticationSuccess($this->request, $this->token);

        // Vérification : la réponse est bien une redirection
        $this->assertInstanceOf(RedirectResponse::class, $response);
        // Vérification : l'URL cible est bien /tech
        $this->assertSame('/tech', $response->getTargetUrl());
    }

    // ─────────────────────────────────────────────
    // 2. REDIRECTION D'UN GESTIONNAIRE
    // ─────────────────────────────────────────────

    public function testGestionnaireEstRedirigéVersEspaceGerant(): void
    {
        $user = new Utilisateur();
        $user->setTypeUtilisateur('gestionnaire');

        $this->token->method('getUser')->willReturn($user);

        $this->router->method('generate')
            ->with('app_gerant_accueil')
            ->willReturn('/gerant');

        $handler = new LoginSuccessHandler($this->router);
        $response = $handler->onAuthenticationSuccess($this->request, $this->token);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/gerant', $response->getTargetUrl());
    }

    // ─────────────────────────────────────────────
    // 3. REDIRECTION D'UN ADMIN
    // ─────────────────────────────────────────────

    public function testAdminEstRedirigéVersPageChoix(): void
    {
        $user = new Utilisateur();
        $user->setTypeUtilisateur('admin');

        $this->token->method('getUser')->willReturn($user);

        $this->router->method('generate')
            ->with('app_admin_choix')
            ->willReturn('/admin/choix');

        $handler = new LoginSuccessHandler($this->router);
        $response = $handler->onAuthenticationSuccess($this->request, $this->token);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/admin/choix', $response->getTargetUrl());
    }

    // ─────────────────────────────────────────────
    // 4. CAS LIMITE : token sans utilisateur valide
    // ─────────────────────────────────────────────

    public function testSansUtilisateurValideRedirectVersLogin(): void
    {
        // Le token renvoie un objet qui N'EST PAS un Utilisateur
        $this->token->method('getUser')->willReturn(null);

        $this->router->method('generate')
            ->with('app_login')
            ->willReturn('/login');

        $handler = new LoginSuccessHandler($this->router);
        $response = $handler->onAuthenticationSuccess($this->request, $this->token);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertSame('/login', $response->getTargetUrl());
    }
}
