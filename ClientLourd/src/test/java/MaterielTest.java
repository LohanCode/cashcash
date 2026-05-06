import java.util.ArrayList;
import java.util.List;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertTrue;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import com.cashcash.models.ContratMaintenance;
import com.cashcash.models.Materiel;

/**
 * Classe de test JUnit pour la classe Materiel
 * Les tests vérifient les getters, setters, la liste des contrats et addContrat()
 */
public class MaterielTest {

    // Variable utilisée dans les tests
    private Materiel materiel;

    /**
     * Méthode exécutée avant chaque test pour créer un nouveau matériel
     */
    @BeforeEach
    void setUp() {
        materiel = new Materiel(); // On crée un matériel vide pour chaque test
    }

    // ========== TESTS DES GETTERS ET SETTERS ==========
    // On teste que les valeurs sont bien enregistrées et récupérées

    @Test
    void testSetGetId() {
        // On change l'id
        materiel.setId(10);

        // On vérifie que getId() retourne bien la valeur donnée
        assertEquals(10, materiel.getId());
    }

    @Test
    void testSetGetNumSerie() {
        // On change le numéro de série
        materiel.setNumSerie("SN-12345");

        // On vérifie que le numéro de série est bien enregistré
        assertEquals("SN-12345", materiel.getNumSerie());
    }

    @Test
    void testSetGetDateVente() {
        // On change la date de vente
        materiel.setDateVente("2024-04-01");

        // On vérifie que la date de vente est bien retournée
        assertEquals("2024-04-01", materiel.getDateVente());
    }

    @Test
    void testSetGetDateInstallation() {
        // On change la date d'installation
        materiel.setDateInstallation("2024-04-10");

        // On vérifie que la date d'installation est bien retournée
        assertEquals("2024-04-10", materiel.getDateInstallation());
    }

    @Test
    void testSetGetPrixVente() {
        // On change le prix de vente
        materiel.setPrixVente(999.99);

        // On vérifie que le prix est bien retourné
        assertEquals(999.99, materiel.getPrixVente());
    }

    @Test
    void testSetGetEmplacement() {
        // On change l'emplacement du matériel
        materiel.setEmplacement("Bureau 101");

        // On vérifie que l'emplacement est bien retourné
        assertEquals("Bureau 101", materiel.getEmplacement());
    }

    // ========== TESTS DE LA LISTE DES CONTRATS ==========
    // Le matériel contient une liste de contrats de maintenance

    @Test
    void testGetContrats_initialementVide() {
        // GIVEN : Un nouveau matériel

        // WHEN : On récupère la liste des contrats
        List<ContratMaintenance> contrats = materiel.getContrats();

        // THEN : La liste doit être vide au démarrage
        assertEquals(0, contrats.size());
    }

    @Test
    void testSetContrats() {
        // GIVEN : On crée une nouvelle liste de contrats
        List<ContratMaintenance> nouvelleListe = new ArrayList<>();
        nouvelleListe.add(new ContratMaintenance()); // On ajoute un contrat vide

        // WHEN : On met cette liste dans le matériel
        materiel.setContrats(nouvelleListe);

        // THEN : La liste du matériel doit contenir 1 contrat
        assertEquals(1, materiel.getContrats().size());
    }

    @Test
    void testAddContrat() {
        // GIVEN : Un matériel sans contrat

        // WHEN : On ajoute un contrat à la liste
        ContratMaintenance contrat = new ContratMaintenance();
        materiel.addContrat(contrat);

        // THEN : La liste doit contenir 1 contrat
        assertEquals(1, materiel.getContrats().size());
        assertTrue(materiel.getContrats().contains(contrat));
    }

    @Test
    void testAddContrat_plusieurs() {
        // GIVEN : Un matériel sans contrat

        // WHEN : On ajoute deux contrats différents
        ContratMaintenance contrat1 = new ContratMaintenance();
        ContratMaintenance contrat2 = new ContratMaintenance();
        materiel.addContrat(contrat1);
        materiel.addContrat(contrat2);

        // THEN : La liste doit contenir 2 contrats
        assertEquals(2, materiel.getContrats().size());
    }

    // ========== TEST D'INTÉGRATION COMPLET ==========
    // On teste plusieurs attributs ensemble comme dans une utilisation réelle

    @Test
    void testMaterielComplet() {
        // GIVEN : On remplit tous les champs du matériel
        materiel.setId(20);
        materiel.setNumSerie("SN-98765");
        materiel.setDateVente("2024-03-15");
        materiel.setDateInstallation("2024-03-20");
        materiel.setPrixVente(1500.0);
        materiel.setEmplacement("Salle serveurs");

        // On ajoute deux contrats à la liste pour simuler un matériel complet
        ContratMaintenance contratA = new ContratMaintenance();
        ContratMaintenance contratB = new ContratMaintenance();
        materiel.addContrat(contratA);
        materiel.addContrat(contratB);

        // WHEN & THEN : On vérifie les valeurs et la liste de contrats
        assertEquals(20, materiel.getId());
        assertEquals("SN-98765", materiel.getNumSerie());
        assertEquals("2024-03-15", materiel.getDateVente());
        assertEquals("2024-03-20", materiel.getDateInstallation());
        assertEquals(1500.0, materiel.getPrixVente());
        assertEquals("Salle serveurs", materiel.getEmplacement());
        assertEquals(2, materiel.getContrats().size());
    }
}
