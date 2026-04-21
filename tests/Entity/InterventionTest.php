<?php

namespace App\Tests\Entity;

use App\Entity\Intervention;
use App\Entity\Utilisateur;
use App\Entity\Client;
use PHPUnit\Framework\TestCase;

/**
 * Tests unitaires de l'entité Intervention.
 *
 * Ces tests vérifient que la logique de l'entité (getters/setters,
 * valeurs par défaut, cycle de vie du statut) fonctionne correctement,
 * SANS toucher à la base de données.
 */
class InterventionTest extends TestCase
{
    // ─────────────────────────────────────────────
    // 1. VALEURS PAR DÉFAUT
    // ─────────────────────────────────────────────

    public function testStatutParDefautEstOuverte(): void
    {
        $intervention = new Intervention();

        $this->assertSame(
            'ouverte',
            $intervention->getStatut(),
            "Une nouvelle intervention doit avoir le statut 'ouverte' par défaut."
        );
    }

    public function testGraviteParDefautEstMoyenne(): void
    {
        $intervention = new Intervention();

        $this->assertSame(
            'moyenne',
            $intervention->getGravite(),
            "Une nouvelle intervention doit avoir la gravité 'moyenne' par défaut."
        );
    }

    public function testTitreEstNullParDefaut(): void
    {
        $intervention = new Intervention();

        $this->assertNull(
            $intervention->getTitre(),
            "Une nouvelle intervention ne doit pas avoir de titre par défaut."
        );
    }

    // ─────────────────────────────────────────────
    // 2. SETTERS & GETTERS (données simples)
    // ─────────────────────────────────────────────

    public function testSetterEtGetterTitre(): void
    {
        $intervention = new Intervention();
        $intervention->setTitre('Panne caisse enregistreuse');

        $this->assertSame('Panne caisse enregistreuse', $intervention->getTitre());
    }

    public function testSetterEtGetterDescription(): void
    {
        $intervention = new Intervention();
        $intervention->setDescription('Écran bloqué, touches ne répondent plus.');

        $this->assertSame('Écran bloqué, touches ne répondent plus.', $intervention->getDescription());
    }

    public function testSetterEtGetterStatut(): void
    {
        $intervention = new Intervention();
        $intervention->setStatut('en_cours');

        $this->assertSame('en_cours', $intervention->getStatut());
    }

    public function testSetterEtGetterGravite(): void
    {
        $intervention = new Intervention();
        $intervention->setGravite('critique');

        $this->assertSame('critique', $intervention->getGravite());
    }

    public function testSetterEtGetterDateVisite(): void
    {
        $intervention = new Intervention();
        $date = new \DateTime('2026-05-15');
        $intervention->setDateVisite($date);

        $this->assertEquals($date, $intervention->getDateVisite());
    }

    public function testSetterEtGetterHeureVisite(): void
    {
        $intervention = new Intervention();
        $heure = new \DateTime('09:30');
        $intervention->setHeureVisite($heure);

        $this->assertEquals($heure, $intervention->getHeureVisite());
    }

    // ─────────────────────────────────────────────
    // 3. CYCLE DE VIE DU STATUT
    // ─────────────────────────────────────────────

    public function testCycleVieStatutComplet(): void
    {
        $intervention = new Intervention();

        // Étape 1 : l'intervention commence à l'état "ouverte"
        $this->assertSame('ouverte', $intervention->getStatut());

        // Étape 2 : elle passe "en cours"
        $intervention->setStatut('en_cours');
        $this->assertSame('en_cours', $intervention->getStatut());

        // Étape 3 : elle se termine
        $intervention->setStatut('terminee');
        $this->assertSame('terminee', $intervention->getStatut());
    }

    public function testLesStatutsValidesSontAcceptes(): void
    {
        $intervention = new Intervention();
        $statutsValides = ['ouverte', 'en_cours', 'terminee'];

        foreach ($statutsValides as $statut) {
            $intervention->setStatut($statut);
            $this->assertSame($statut, $intervention->getStatut(), "Le statut '$statut' devrait être accepté.");
        }
    }

    // ─────────────────────────────────────────────
    // 4. RELATIONS (Technicien & Client)
    // ─────────────────────────────────────────────

    public function testAssignerUnTechnicien(): void
    {
        $intervention = new Intervention();
        $technicien = new Utilisateur();
        $technicien->setNom('Dupont');
        $technicien->setPrenom('Jean');

        $intervention->setTechnicien($technicien);

        $this->assertSame($technicien, $intervention->getTechnicien());
        $this->assertSame('Dupont', $intervention->getTechnicien()->getNom());
    }

    public function testAssignerUnClient(): void
    {
        $intervention = new Intervention();
        $client = new Client();
        $client->setRaisSociale('Restaurant Le Gourmet');

        $intervention->setClient($client);

        $this->assertSame($client, $intervention->getClient());
        $this->assertSame('Restaurant Le Gourmet', $intervention->getClient()->getRaisSociale());
    }

    public function testDesassignerUnTechnicien(): void
    {
        $intervention = new Intervention();
        $technicien = new Utilisateur();
        $intervention->setTechnicien($technicien);

        // On retire le technicien
        $intervention->setTechnicien(null);

        $this->assertNull($intervention->getTechnicien());
    }
}
