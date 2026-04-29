package com.cashcash.web;

import com.cashcash.services.XmlGenerator;
import com.cashcash.services.PdfGenerator;
import java.util.logging.Logger;
import java.util.logging.Level;

/**
 * Bridge pour la communication bidirectionnelle entre Java et JavaScript.
 * Permet à l'interface web d'accéder aux données du ClientLourd et générer des fichiers.
 */
public class JavaScriptBridge {

    private static final Logger LOGGER = Logger.getLogger(JavaScriptBridge.class.getName());

    /**
     * Récupère les données d'un client depuis la BD.
     * @param numClient le numéro du client
     * @return JSON contenant les données du client
     */
    public String getClientData(String numClient) {
        try {
            if (numClient == null || numClient.trim().isEmpty()) {
                LOGGER.warning("NumClient vide fourni à getClientData");
                return "{\"error\": \"NumClient invalide\"}";
            }

            // Récupérer les données via ClientDao
            com.cashcash.database.ClientDao clientDao = new com.cashcash.database.ClientDao();
            com.cashcash.models.Client client = clientDao.getClientByNum(numClient);

            if (client == null) {
                LOGGER.warning("Client non trouvé : " + numClient);
                return "{\"error\": \"Client introuvable\"}";
            }

            // Convertir en JSON (sans dépendance externe, format manuel)
            String json = "{" +
                    "\"id\": " + client.getId() + "," +
                    "\"numClient\": \"" + client.getNumClient() + "\"," +
                    "\"raisSociale\": \"" + client.getRaisSociale() + "\"," +
                    "\"siren\": \"" + client.getSiren() + "\"," +
                    "\"codeApe\": " + client.getCodeApe() + "," +
                    "\"adresseClient\": \"" + client.getAdresseClient() + "\"," +
                    "\"telephoneClient\": \"" + client.getTelephoneClient() + "\"," +
                    "\"emailClient\": \"" + client.getEmailClient() + "\"" +
                    "}";

            LOGGER.info("Données client retournées pour : " + numClient);
            return json;

        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors de la récupération des données du client", e);
            return "{\"error\": \"Erreur serveur\"}";
        }
    }

    /**
     * Teste la connexion à la base de données.
     * @return "connected" si OK, "disconnected" sinon
     */
    public String testDatabaseConnection() {
        try {
            java.sql.Connection conn = com.cashcash.database.DatabaseConnection.getInstance();
            if (conn != null && !conn.isClosed()) {
                LOGGER.info("Connexion BD vérifiée : OK");
                return "connected";
            }
        } catch (Exception e) {
            LOGGER.log(Level.WARNING, "Erreur lors du test de connexion BD", e);
        }
        return "disconnected";
    }

    /**
     * Récupère la version de l'application.
     */
    public String getAppVersion() {
        return "1.0.0";
    }

    /**
     * Génère un fichier XML pour un client.
     * @param numClient le numéro du client
     * @param filename le nom du fichier de sortie
     * @return "success" si OK, sinon un message d'erreur JSON
     */
    public String generateClientXml(String numClient, String filename) {
        try {
            if (numClient == null || numClient.trim().isEmpty()) {
                LOGGER.warning("NumClient vide fourni à generateClientXml");
                return "{\"error\": \"NumClient invalide\"}";
            }

            // Récupérer les données du client
            com.cashcash.database.ClientDao clientDao = new com.cashcash.database.ClientDao();
            com.cashcash.models.Client client = clientDao.getClientByNum(numClient);

            if (client == null) {
                LOGGER.warning("Client non trouvé pour XML : " + numClient);
                return "{\"error\": \"Client introuvable\"}";
            }

            // Valider le nom de fichier
            String safeFilename = filename.replaceAll("[^a-zA-Z0-9._-]", "_");
            if (!safeFilename.endsWith(".xml")) {
                safeFilename += ".xml";
            }

            // Générer le XML
            XmlGenerator.generateXml(client, safeFilename);
            LOGGER.info("Fichier XML généré avec succès : " + safeFilename);
            return "{\"success\": true, \"filename\": \"" + safeFilename + "\", \"message\": \"XML généré avec succès\"}";

        } catch (javax.xml.bind.JAXBException e) {
            LOGGER.log(Level.SEVERE, "Erreur JAXB lors de la génération du XML", e);
            return "{\"error\": \"Erreur lors de la sérialisation XML\"}";
        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors de la génération du XML", e);
            return "{\"error\": \"Erreur serveur lors de la génération du XML\"}";
        }
    }

    /**
     * Génère un fichier PDF de relance pour un client.
     * @param numClient le numéro du client
     * @param filename le nom du fichier de sortie
     * @return "success" si OK, sinon un message d'erreur JSON
     */
    public String generateClientPdf(String numClient, String filename) {
        try {
            if (numClient == null || numClient.trim().isEmpty()) {
                LOGGER.warning("NumClient vide fourni à generateClientPdf");
                return "{\"error\": \"NumClient invalide\"}";
            }

            // Récupérer les données du client
            com.cashcash.database.ClientDao clientDao = new com.cashcash.database.ClientDao();
            com.cashcash.models.Client client = clientDao.getClientByNum(numClient);

            if (client == null) {
                LOGGER.warning("Client non trouvé pour PDF : " + numClient);
                return "{\"error\": \"Client introuvable\"}";
            }

            // Valider le nom de fichier
            String safeFilename = filename.replaceAll("[^a-zA-Z0-9._-]", "_");
            if (!safeFilename.endsWith(".pdf")) {
                safeFilename += ".pdf";
            }

            // Générer le PDF
            PdfGenerator.generatePdf(client, safeFilename);
            LOGGER.info("Fichier PDF généré avec succès : " + safeFilename);
            return "{\"success\": true, \"filename\": \"" + safeFilename + "\", \"message\": \"PDF généré avec succès\"}";

        } catch (com.itextpdf.text.DocumentException e) {
            LOGGER.log(Level.SEVERE, "Erreur iText lors de la génération du PDF", e);
            return "{\"error\": \"Erreur lors de la génération du document PDF\"}";
        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors de la génération du PDF", e);
            return "{\"error\": \"Erreur serveur lors de la génération du PDF\"}";
        }
    }
}