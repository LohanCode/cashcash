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
     * Génère un fichier XML pour un client (EN MÉMOIRE).
     * @param numClient le numéro du client
     * @param filename le nom du fichier de sortie
     * @return le contenu XML en String
     */
    public String generateClientXmlData(String numClient) {
        try {
            if (numClient == null || numClient.trim().isEmpty()) {
                LOGGER.warning("NumClient vide fourni à generateClientXmlData");
                return null;
            }

            com.cashcash.database.ClientDao clientDao = new com.cashcash.database.ClientDao();
            com.cashcash.models.Client client = clientDao.getClientByNum(numClient);

            if (client == null) {
                LOGGER.warning("Client non trouvé pour XML : " + numClient);
                return null;
            }

            // Générer XML en ByteArrayOutputStream (ne pas sauvegarder)
            java.io.ByteArrayOutputStream baos = new java.io.ByteArrayOutputStream();
            javax.xml.bind.JAXBContext context = javax.xml.bind.JAXBContext.newInstance(com.cashcash.models.Client.class);
            javax.xml.bind.Marshaller marshaller = context.createMarshaller();
            marshaller.setProperty(javax.xml.bind.Marshaller.JAXB_FORMATTED_OUTPUT, Boolean.TRUE);
            marshaller.marshal(client, baos);

            LOGGER.info("XML généré en mémoire pour : " + numClient);
            return baos.toString("UTF-8");

        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors de la génération du XML", e);
            return null;
        }
    }

    /**
     * Génère un fichier PDF pour un client (EN MÉMOIRE).
     * @param numClient le numéro du client
     * @return le contenu PDF en ByteArray
     */
    public byte[] generateClientPdfData(String numClient) {
        try {
            if (numClient == null || numClient.trim().isEmpty()) {
                LOGGER.warning("NumClient vide fourni à generateClientPdfData");
                return null;
            }

            com.cashcash.database.ClientDao clientDao = new com.cashcash.database.ClientDao();
            com.cashcash.models.Client client = clientDao.getClientByNum(numClient);

            if (client == null) {
                LOGGER.warning("Client non trouvé pour PDF : " + numClient);
                return null;
            }

            // Générer PDF en ByteArrayOutputStream (ne pas sauvegarder)
            java.io.ByteArrayOutputStream baos = new java.io.ByteArrayOutputStream();
            com.itextpdf.text.Document document = new com.itextpdf.text.Document();
            com.itextpdf.text.pdf.PdfWriter.getInstance(document, baos);
            document.open();

            com.itextpdf.text.Font titleFont = new com.itextpdf.text.Font(com.itextpdf.text.Font.FontFamily.HELVETICA, 18, com.itextpdf.text.Font.BOLD);
            com.itextpdf.text.Font regularFont = new com.itextpdf.text.Font(com.itextpdf.text.Font.FontFamily.HELVETICA, 12, com.itextpdf.text.Font.NORMAL);

            com.itextpdf.text.Paragraph titre = new com.itextpdf.text.Paragraph("Lettre de relance de maintenance", titleFont);
            titre.setAlignment(com.itextpdf.text.Paragraph.ALIGN_CENTER);
            document.add(titre);
            document.add(new com.itextpdf.text.Paragraph(" "));

            document.add(new com.itextpdf.text.Paragraph("À l'attention de : " + client.getRaisSociale(), regularFont));
            document.add(new com.itextpdf.text.Paragraph("Adresse : " + client.getAdresseClient(), regularFont));
            document.add(new com.itextpdf.text.Paragraph("Téléphone : " + client.getTelephoneClient(), regularFont));
            document.add(new com.itextpdf.text.Paragraph(" "));

            document.add(new com.itextpdf.text.Paragraph("Madame, Monsieur,", regularFont));
            document.add(new com.itextpdf.text.Paragraph("Nous vous prions de bien vouloir trouver ci-dessous la liste de vos matériels nécessitant une visite de maintenance.", regularFont));
            document.add(new com.itextpdf.text.Paragraph(" "));

            for (com.cashcash.models.Materiel m : client.getMateriels()) {
                document.add(new com.itextpdf.text.Paragraph("- Matériel NS: " + m.getNumSerie() + " (Emplacement: " + m.getEmplacement() + ")", regularFont));
            }

            document.add(new com.itextpdf.text.Paragraph(" "));
            document.add(new com.itextpdf.text.Paragraph("Dans l'attente de votre retour, nous vous prions d'agréer nos salutations distinguées.", regularFont));
            document.add(new com.itextpdf.text.Paragraph("L'équipe CashCash.", regularFont));

            document.close();

            LOGGER.info("PDF généré en mémoire pour : " + numClient);
            return baos.toByteArray();

        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors de la génération du PDF", e);
            return null;
        }
    }
}