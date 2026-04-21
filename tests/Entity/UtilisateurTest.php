<?php

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires de l'entité Utilisateur.
 *
 * Ces tests vérifient la logique métier de l'utilisateur :
 * types de comptes, rôles, méthodes de vérification.
 */
class UtilisateurTest extends TestCase
{
    // ─────────────────────────────────────────────
    // 1. TYPE D'UTILISATEUR
    // ─────────────────────────────────────────────

    public function testTypeParDefautEstEmploye(): void
    {
        $user = new Utilisateur();

        $this->assertSame(
            'employe',
            $user->getTypeUtilisateur(),
            "Le type par défaut doit être 'employe'."
        );
    }

    public function testDefinirTypeTechnicien(): void
    {
        $user = new Utilisateur();
        $user->setTypeUtilisateur('technicien');

        $this->assertSame('technicien', $user->getTypeUtilisateur());
        $this->assertTrue($user->isTechnicien(), "isTechnicien() doit retourner true.");
        $this->assertFalse($user->isAdmin(), "isAdmin() doit retourner false.");
        $this->assertFalse($user->isEmploye(), "isEmploye() doit retourner false.");
    }

    public function testDefinirTypeGestionnaire(): void
    {
        $user = new Utilisateur();
        $user->setTypeUtilisateur('gestionnaire');

        $this->assertSame('gestionnaire', $user->getTypeUtilisateur());
        $this->assertFalse($user->isTechnicien());
        $this->assertFalse($user->isAdmin());
    }

    public function testDefinirTypeAdmin(): void
    {
        $user = new Utilisateur();
        $user->setTypeUtilisateur('admin');

        $this->assertTrue($user->isAdmin(), "isAdmin() doit retourner true pour un admin.");
        $this->assertFalse($user->isTechnicien());
    }

    // ─────────────────────────────────────────────
    // 2. RÔLES SYMFONY
    // ─────────────────────────────────────────────

    public function testGetRolesContientToujoursRoleUser(): void
    {
        $user = new Utilisateur();

        $roles = $user->getRoles();

        $this->assertContains(
            'ROLE_USER',
            $roles,
            "Tout utilisateur doit toujours avoir le rôle ROLE_USER."
        );
    }

    public function testGetRolesSansDoublons(): void
    {
        $user = new Utilisateur();
        $user->setRoles(['ROLE_USER', 'ROLE_USER', 'ROLE_ADMIN']);

        $roles = $user->getRoles();

        // array_unique est appliqué dans getRoles()
        $this->assertSame(
            count($roles),
            count(array_unique($roles)),
            "Les rôles ne doivent pas contenir de doublons."
        );
    }

    // ─────────────────────────────────────────────
    // 3. IDENTIFIANT DE CONNEXION
    // ─────────────────────────────────────────────

    public function testGetUserIdentifierRetourneLEmail(): void
    {
        $user = new Utilisateur();
        $user->setEmail('jean.dupont@cashcash.fr');

        $this->assertSame(
            'jean.dupont@cashcash.fr',
            $user->getUserIdentifier(),
            "L'identifiant Symfony doit être l'email de l'utilisateur."
        );
    }

    // ─────────────────────────────────────────────
    // 4. DONNÉES PERSONNELLES
    // ─────────────────────────────────────────────

    public function testSetterEtGetterNomPrenom(): void
    {
        $user = new Utilisateur();
        $user->setNom('Martin');
        $user->setPrenom('Sophie');

        $this->assertSame('Martin', $user->getNom());
        $this->assertSame('Sophie', $user->getPrenom());
    }

    public function testSetterEtGetterMatricule(): void
    {
        $user = new Utilisateur();
        $user->setMatricule('TECH-042');

        $this->assertSame('TECH-042', $user->getMatricule());
    }

    public function testSetterEtGetterEmail(): void
    {
        $user = new Utilisateur();
        $user->setEmail('test@exemple.com');

        $this->assertSame('test@exemple.com', $user->getEmail());
    }

    // ─────────────────────────────────────────────
    // 5. GESTION DES INTERVENTIONS AFFECTÉES
    // ─────────────────────────────────────────────

    public function testCollectionInterventionsVideParDefaut(): void
    {
        $user = new Utilisateur();

        $this->assertCount(
            0,
            $user->getInterventionsAffectees(),
            "Un nouvel utilisateur ne doit avoir aucune intervention affectée."
        );
    }
}
