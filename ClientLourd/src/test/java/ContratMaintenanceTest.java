import java.time.LocalDate;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertFalse;
import static org.junit.jupiter.api.Assertions.assertTrue;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import com.cashcash.models.ContratMaintenance;

/**
 * Classe de test JUnit pour la classe ContratMaintenance
 * Elle teste toutes les méthodes : les getters, setters, isActive() et isExpired()
 */
public class ContratMaintenanceTest {

    // Variables utilisées dans les tests
    private ContratMaintenance contrat;
    private LocalDate aujourd_hui;

    /**
     * Méthode d'initialisation qui s'exécute AVANT chaque test
     * Elle crée une nouvelle instance de ContratMaintenance
     */
    @BeforeEach
    void setUp() {
        contrat = new ContratMaintenance(); // Crée un contrat vide pour chaque test
        aujourd_hui = LocalDate.now(); // Récupère la date d'aujourd'hui
    }

    // ========== TESTS DES GETTERS ET SETTERS ==========
    // On teste que les méthodes set() et get() fonctionnent correctement
    
    @Test
    void testSetGetId() {
        // On change l'id et on vérifie qu'il a bien été changé
        contrat.setId(1);
        assertEquals(1, contrat.getId()); // Assertion : on vérifie que getId() retourne 1
    }

    @Test
    void testSetGetNumContrat() {
        // On change le numéro de contrat et on vérifie qu'il a bien été changé
        contrat.setNumContrat("CONT-001");
        assertEquals("CONT-001", contrat.getNumContrat());
    }

    @Test
    void testSetGetDateSignature() {
        // On crée une date et on la set comme date de signature
        LocalDate date = LocalDate.of(2024, 1, 15); // 15 janvier 2024
        contrat.setDateSignature(date);
        // On vérifie que la date a été bien enregistrée
        assertEquals(date, contrat.getDateSignature());
    }

    @Test
    void testSetGetDateEcheance() {
        // Même chose pour la date d'échéance
        LocalDate date = LocalDate.of(2025, 1, 15); // 15 janvier 2025
        contrat.setDateEcheance(date);
        assertEquals(date, contrat.getDateEcheance());
    }

    @Test
    void testSetGetTypeContrat() {
        // On change le type de contrat et on vérifie
        contrat.setTypeContrat("Maintenance standard");
        assertEquals("Maintenance standard", contrat.getTypeContrat());
    }

    // ========== TESTS DU CONSTRUCTEUR ==========
    // On teste que le constructeur avec paramètres crée bien l'objet
    
    @Test
    void testConstructeurAvecParametres() {
        // On crée les dates
        LocalDate dateSignature = LocalDate.of(2024, 1, 1);
        LocalDate dateEcheance = LocalDate.of(2025, 1, 1);
        
        // On crée un contrat en passant tous les paramètres au constructeur
        ContratMaintenance c = new ContratMaintenance(
            1, 
            "CONT-001", 
            dateSignature, 
            dateEcheance, 
            "Maintenance"
        );
        
        // On vérifie que tous les attributs ont bien été initalisés
        assertEquals(1, c.getId());
        assertEquals("CONT-001", c.getNumContrat());
        assertEquals(dateSignature, c.getDateSignature());
        assertEquals(dateEcheance, c.getDateEcheance());
        assertEquals("Maintenance", c.getTypeContrat());
    }

    // ========== TESTS DE LA MÉTHODE isActive() ==========
    // Cette méthode vérifie si un contrat est actif (entre la date de signature et d'échéance)
    
    @Test
    void testIsActive_contratEnCours() {
        // GIVEN : On crée un contrat qui a commencé il y a 5 jours et finit dans 5 jours
        contrat.setDateSignature(aujourd_hui.minusDays(5)); // Il y a 5 jours
        contrat.setDateEcheance(aujourd_hui.plusDays(5));   // Dans 5 jours

        // WHEN : On appelle la méthode isActive()
        boolean result = contrat.isActive();

        // THEN : On vérifie que le résultat est true (le contrat est actif)
        assertTrue(result); // assertTrue = on s'attend à true
    }

    @Test
    void testIsActive_contratPasCommence() {
        // GIVEN : Un contrat qui n'a pas encore commencé
        contrat.setDateSignature(aujourd_hui.plusDays(5));   // Commence dans 5 jours
        contrat.setDateEcheance(aujourd_hui.plusDays(10));   // Finit dans 10 jours

        // WHEN
        boolean result = contrat.isActive();

        // THEN : On s'attend à false (le contrat n'est pas actif car il n'a pas commencé)
        assertFalse(result); // assertFalse = on s'attend à false
    }

    @Test
    void testIsActive_contratExpire() {
        // GIVEN : Un contrat qui a expiré
        contrat.setDateSignature(aujourd_hui.minusDays(10)); // Commencé il y a 10 jours
        contrat.setDateEcheance(aujourd_hui.minusDays(5));   // Expiré il y a 5 jours

        // WHEN
        boolean result = contrat.isActive();

        // THEN : On s'attend à false (le contrat est expiré donc pas actif)
        assertFalse(result);
    }

    // ========== TESTS DE LA MÉTHODE isExpired() ==========
    // Cette méthode vérifie si un contrat a dépassé sa date d'échéance
    
    @Test
    void testIsExpired_contratActif() {
        // GIVEN : Un contrat qui finit dans 10 jours
        contrat.setDateEcheance(aujourd_hui.plusDays(10));

        // WHEN
        boolean result = contrat.isExpired();

        // THEN : On s'attend à false (le contrat n'est pas expiré)
        assertFalse(result);
    }

    @Test
    void testIsExpired_contratExpire() {
        // GIVEN : Un contrat qui a dépassé sa date d'échéance
        contrat.setDateEcheance(aujourd_hui.minusDays(1)); // Expiré hier

        // WHEN
        boolean result = contrat.isExpired();

        // THEN : On s'attend à true (le contrat est expiré)
        assertTrue(result);
    }

    @Test
    void testIsExpired_dateEcheanceAujourdhui() {
        // GIVEN : Un contrat dont la date d'échéance est aujourd'hui
        contrat.setDateEcheance(aujourd_hui);

        // WHEN
        boolean result = contrat.isExpired();

        // THEN : On s'attend à false (le contrat n'est pas expiré le jour de l'échéance)
        assertFalse(result);
    }

    // ========== TEST D'INTÉGRATION ==========
    // On teste plusieurs méthodes ensemble pour vérifier qu'elles fonctionnent bien ensemble
    
    @Test
    void testContratComplet() {
        // GIVEN : On crée un contrat complet avec tous les attributs
        contrat.setId(5);
        contrat.setNumContrat("CONT-2024-005");
        // Date de signature : il y a 3 mois
        contrat.setDateSignature(aujourd_hui.minusMonths(3));
        // Date d'échéance : dans 9 mois
        contrat.setDateEcheance(aujourd_hui.plusMonths(9));
        contrat.setTypeContrat("Maintenance premium");

        // WHEN & THEN : On vérifie tous les attributs et les méthodes en même temps
        // On vérifie que tous les getters retournent les bonnes valeurs
        assertEquals(5, contrat.getId());
        assertEquals("CONT-2024-005", contrat.getNumContrat());
        // Ce contrat a commencé il y a 3 mois et finit dans 9 mois, donc il est actif
        assertTrue(contrat.isActive());
        // Ce contrat n'a pas dépassé sa date d'échéance, donc il n'est pas expiré
        assertFalse(contrat.isExpired());
    }
}
