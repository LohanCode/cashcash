import java.util.List;

import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertTrue;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

import com.cashcash.models.Client;
import com.cashcash.models.Materiel;

/**
 * Classe de test JUnit pour la classe Client
 * Elle teste toutes les méthodes : les getters, setters, addMateriel() et toString()
 */
public class ClientTest {

    // Variables utilisées dans les tests
    private Client client;

    /**
     * Méthode d'initialisation qui s'exécute AVANT chaque test
     * Elle crée une nouvelle instance de Client
     */
    @BeforeEach
    void setUp() {
        client = new Client(); // Crée un client vide pour chaque test
    }

    // ========== TESTS DES GETTERS ET SETTERS ==========
    // On teste que les méthodes set() et get() fonctionnent correctement pour tous les attributs

    @Test
    void testSetGetId() {
        // On change l'id et on vérifie qu'il a bien été changé
        client.setId(1);
        assertEquals(1, client.getId()); // Assertion : on vérifie que getId() retourne 1
    }

    @Test
    void testSetGetNumClient() {
        // On change le numéro de client et on vérifie qu'il a bien été changé
        client.setNumClient("CLI-001");
        assertEquals("CLI-001", client.getNumClient());
    }

    @Test
    void testSetGetRaisSociale() {
        // On change la raison sociale et on vérifie qu'elle a bien été changée
        client.setRaisSociale("Entreprise ABC");
        assertEquals("Entreprise ABC", client.getRaisSociale());
    }

    @Test
    void testSetGetSiren() {
        // On change le numéro SIREN et on vérifie qu'il a bien été changé
        client.setSiren("123456789");
        assertEquals("123456789", client.getSiren());
    }

    @Test
    void testSetGetCodeApe() {
        // On change le code APE et on vérifie qu'il a bien été changé
        client.setCodeApe(6201);
        assertEquals(6201, client.getCodeApe());
    }

    @Test
    void testSetGetAdresseClient() {
        // On change l'adresse du client et on vérifie qu'elle a bien été changée
        client.setAdresseClient("123 Rue de la Paix, 75000 Paris");
        assertEquals("123 Rue de la Paix, 75000 Paris", client.getAdresseClient());
    }

    @Test
    void testSetGetTelephoneClient() {
        // On change le numéro de téléphone et on vérifie qu'il a bien été changé
        client.setTelephoneClient("0123456789");
        assertEquals("0123456789", client.getTelephoneClient());
    }

    @Test
    void testSetGetEmailClient() {
        // On change l'adresse email et on vérifie qu'elle a bien été changée
        client.setEmailClient("contact@entreprise.com");
        assertEquals("contact@entreprise.com", client.getEmailClient());
    }

    @Test
    void testSetGetDureeDeplacement() {
        // On change la durée de déplacement et on vérifie qu'elle a bien été changée
        client.setDureeDeplacement("2h30");
        assertEquals("2h30", client.getDureeDeplacement());
    }

    @Test
    void testSetGetDistanceKm() {
        // On change la distance en km et on vérifie qu'elle a bien été changée
        client.setDistanceKm(45.5);
        assertEquals(45.5, client.getDistanceKm());
    }

    // ========== TESTS DE LA GESTION DES MATÉRIELS ==========
    // La classe Client contient une liste de matériels, on teste les méthodes qui la gèrent

    @Test
    void testGetMateriels_initialementVide() {
        // GIVEN : Un nouveau client (qui vient d'être créé)

        // WHEN : On récupère la liste des matériels
        List<Materiel> materiels = client.getMateriels();

        // THEN : La liste doit être vide au départ (pas de matériels)
        assertEquals(0, materiels.size()); // assertEquals = on vérifie que la taille est 0
    }

    @Test
    void testSetMateriels() {
        // GIVEN : On crée une nouvelle liste de matériels vide
        List<Materiel> nouvelleListe = new java.util.ArrayList<>();
        // On ajoute un matériel fictif dans cette liste
        nouvelleListe.add(new Materiel()); // On suppose que Materiel a un constructeur simple

        // WHEN : On remplace la liste de matériels du client par notre nouvelle liste
        client.setMateriels(nouvelleListe);

        // THEN : La liste du client doit maintenant contenir 1 élément
        assertEquals(1, client.getMateriels().size());
    }

    @Test
    void testAddMateriel() {
        // GIVEN : Un client vide (sans matériels)

        // WHEN : On ajoute un matériel au client
        Materiel materiel = new Materiel(); // On crée un matériel fictif
        client.addMateriel(materiel); // On l'ajoute à la liste du client

        // THEN : La liste doit contenir 1 élément
        assertEquals(1, client.getMateriels().size());
        // Et le matériel ajouté doit bien être dans la liste
        assertTrue(client.getMateriels().contains(materiel)); // assertTrue = on vérifie que c'est vrai
    }

    @Test
    void testAddMateriel_plusieurs() {
        // GIVEN : Un client vide

        // WHEN : On ajoute plusieurs matériels un par un
        Materiel mat1 = new Materiel(); // Premier matériel
        Materiel mat2 = new Materiel(); // Deuxième matériel
        client.addMateriel(mat1); // On ajoute le premier
        client.addMateriel(mat2); // On ajoute le deuxième

        // THEN : La liste doit contenir 2 éléments
        assertEquals(2, client.getMateriels().size());
    }

    // ========== TESTS DE LA MÉTHODE toString() ==========
    // Cette méthode retourne une représentation textuelle du client

    @Test
    void testToString_avecRaisonSociale() {
        // GIVEN : Un client qui a une raison sociale définie
        client.setRaisSociale("Société Dupont");

        // WHEN : On appelle la méthode toString()
        String result = client.toString();

        // THEN : Elle doit retourner la raison sociale
        assertEquals("Société Dupont", result);
    }

    @Test
    void testToString_sansRaisonSociale() {
        // GIVEN : Un client qui n'a pas de raison sociale (elle est null)

        // WHEN : On appelle toString()
        String result = client.toString();

        // THEN : Elle doit retourner le message par défaut
        assertEquals("Client sans nom", result);
    }

    // ========== TEST D'INTÉGRATION COMPLET ==========
    // On teste plusieurs méthodes ensemble pour vérifier qu'elles fonctionnent bien ensemble

    @Test
    void testClientComplet() {
        // GIVEN : On crée un client complet avec TOUS les attributs
        client.setId(10);
        client.setNumClient("CLI-2024-010");
        client.setRaisSociale("Tech Solutions SARL");
        client.setSiren("987654321");
        client.setCodeApe(6202);
        client.setAdresseClient("456 Avenue des Technologies, 69000 Lyon");
        client.setTelephoneClient("0478123456");
        client.setEmailClient("info@techsolutions.fr");
        client.setDureeDeplacement("1h45");
        client.setDistanceKm(120.5);

        // On ajoute quelques matériels pour tester la liste
        Materiel mat1 = new Materiel(); // Premier matériel
        Materiel mat2 = new Materiel(); // Deuxième matériel
        client.addMateriel(mat1); // On ajoute le premier
        client.addMateriel(mat2); // On ajoute le deuxième

        // WHEN & THEN : On vérifie TOUS les attributs et méthodes en même temps
        // On vérifie que tous les getters retournent les bonnes valeurs
        assertEquals(10, client.getId());
        assertEquals("CLI-2024-010", client.getNumClient());
        assertEquals("Tech Solutions SARL", client.getRaisSociale());
        assertEquals("987654321", client.getSiren());
        assertEquals(6202, client.getCodeApe());
        assertEquals("456 Avenue des Technologies, 69000 Lyon", client.getAdresseClient());
        assertEquals("0478123456", client.getTelephoneClient());
        assertEquals("info@techsolutions.fr", client.getEmailClient());
        assertEquals("1h45", client.getDureeDeplacement());
        assertEquals(120.5, client.getDistanceKm());

        // On vérifie que la liste des matériels contient bien 2 éléments
        assertEquals(2, client.getMateriels().size());

        // On vérifie que toString() retourne la raison sociale
        assertEquals("Tech Solutions SARL", client.toString());
    }
}
