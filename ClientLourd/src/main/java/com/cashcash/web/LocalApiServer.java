package com.cashcash.web;

import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;

import java.io.*;
import java.net.InetSocketAddress;
import java.util.logging.Logger;
import java.util.logging.Level;

/**
 * Serveur HTTP local pour exposer les méthodes Java au JavaScript via REST API.
 */
public class LocalApiServer {
    private static final Logger LOGGER = Logger.getLogger(LocalApiServer.class.getName());
    private static final int PORT = 9999;
    private HttpServer server;
    private JavaScriptBridge bridge;

    public LocalApiServer() {
        this.bridge = new JavaScriptBridge();
    }

    public void start() throws Exception {
        server = HttpServer.create(new InetSocketAddress("localhost", PORT), 0);

        server.createContext("/api/client/xml", new HttpHandler() {
            @Override
            public void handle(HttpExchange exchange) {
                try {
                    String query = exchange.getRequestURI().getQuery();
                    String numClient = extractParam(query, "numClient");
                    String filename = extractParam(query, "filename");

                    // Générer le XML en mémoire
                    com.cashcash.models.Client client = new com.cashcash.database.ClientDao().getClientByNum(numClient);
                    if (client == null) {
                        sendJsonResponse(exchange, "{\"error\": \"Client not found\"}");
                        return;
                    }

                    // Générer le XML en ByteArrayOutputStream
                    ByteArrayOutputStream baos = new ByteArrayOutputStream();
                    try {
                        javax.xml.bind.JAXBContext context = javax.xml.bind.JAXBContext.newInstance(com.cashcash.models.Client.class);
                        javax.xml.bind.Marshaller marshaller = context.createMarshaller();
                        marshaller.setProperty(javax.xml.bind.Marshaller.JAXB_FORMATTED_OUTPUT, Boolean.TRUE);
                        marshaller.marshal(client, baos);
                    } catch (Exception e) {
                        LOGGER.log(Level.SEVERE, "Erreur JAXB", e);
                        sendJsonResponse(exchange, "{\"error\": \"XML generation failed\"}");
                        return;
                    }

                    // Servir le fichier en téléchargement
                    byte[] xmlData = baos.toByteArray();
                    String xmlFilename = (filename.isEmpty() ? "client" : filename) + ".xml";

                    // Sauvegarder directement dans Téléchargements
                    String downloadsDir = System.getProperty("user.home") + File.separator + "Downloads";
                    File downloadFile = new File(downloadsDir, xmlFilename);
                    downloadFile.getParentFile().mkdirs();
                    java.nio.file.Files.write(downloadFile.toPath(), xmlData);
                    LOGGER.info("✓ Fichier XML généré: " + downloadFile.getAbsolutePath());

                    sendJsonResponse(exchange, "{\"success\": true, \"message\": \"XML généré avec succès\", \"path\": \"" + downloadFile.getAbsolutePath() + "\", \"filename\": \"" + xmlFilename + "\"}");

                } catch (Exception e) {
                    LOGGER.log(Level.SEVERE, "Erreur API XML", e);
                    try {
                        sendJsonResponse(exchange, "{\"error\": \"" + e.getMessage() + "\"}");
                    } catch (Exception ex) {
                        LOGGER.log(Level.SEVERE, "Erreur envoi réponse", ex);
                    }
                }
            }
        });

        server.createContext("/api/client/pdf", new HttpHandler() {
            @Override
            public void handle(HttpExchange exchange) {
                try {
                    String query = exchange.getRequestURI().getQuery();
                    String numClient = extractParam(query, "numClient");
                    String filename = extractParam(query, "filename");

                    // Récupérer le client
                    com.cashcash.models.Client client = new com.cashcash.database.ClientDao().getClientByNum(numClient);
                    if (client == null) {
                        sendJsonResponse(exchange, "{\"error\": \"Client not found\"}");
                        return;
                    }

                    // Générer le PDF en ByteArrayOutputStream
                    ByteArrayOutputStream baos = new ByteArrayOutputStream();
                    try {
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
                    } catch (Exception e) {
                        LOGGER.log(Level.SEVERE, "Erreur iText", e);
                        sendJsonResponse(exchange, "{\"error\": \"PDF generation failed\"}");
                        return;
                    }

                    // Servir le fichier en téléchargement
                    byte[] pdfData = baos.toByteArray();
                    String pdfFilename = (filename.isEmpty() ? "relance" : filename) + ".pdf";

                    // Sauvegarder directement dans Téléchargements
                    String downloadsDir = System.getProperty("user.home") + File.separator + "Downloads";
                    File downloadFile = new File(downloadsDir, pdfFilename);
                    downloadFile.getParentFile().mkdirs();
                    java.nio.file.Files.write(downloadFile.toPath(), pdfData);
                    LOGGER.info("✓ Fichier PDF généré: " + downloadFile.getAbsolutePath());

                    sendJsonResponse(exchange, "{\"success\": true, \"message\": \"PDF généré avec succès\", \"path\": \"" + downloadFile.getAbsolutePath() + "\", \"filename\": \"" + pdfFilename + "\"}");

                } catch (Exception e) {
                    LOGGER.log(Level.SEVERE, "Erreur API PDF", e);
                    try {
                        sendJsonResponse(exchange, "{\"error\": \"" + e.getMessage() + "\"}");
                    } catch (Exception ex) {
                        LOGGER.log(Level.SEVERE, "Erreur envoi réponse", ex);
                    }
                }
            }
        });

        server.createContext("/api/client/data", new HttpHandler() {
            @Override
            public void handle(HttpExchange exchange) {
                try {
                    String query = exchange.getRequestURI().getQuery();
                    String numClient = extractParam(query, "numClient");

                    String result = bridge.getClientData(numClient);
                    sendJsonResponse(exchange, result);
                } catch (Exception e) {
                    LOGGER.log(Level.SEVERE, "Erreur API data", e);
                    try {
                        sendJsonResponse(exchange, "{\"error\": \"" + e.getMessage() + "\"}");
                    } catch (Exception ex) {
                        LOGGER.log(Level.SEVERE, "Erreur envoi réponse", ex);
                    }
                }
            }
        });

        server.setExecutor(null);
        server.start();
        LOGGER.info("Serveur API local démarré sur http://localhost:" + PORT);
    }

    public void stop() {
        if (server != null) {
            server.stop(0);
            LOGGER.info("Serveur API local arrêté");
        }
    }

    private String extractParam(String query, String paramName) {
        if (query == null) return "";
        String[] params = query.split("&");
        for (String param : params) {
            String[] pair = param.split("=");
            if (pair.length == 2 && pair[0].equals(paramName)) {
                try {
                    return java.net.URLDecoder.decode(pair[1], "UTF-8");
                } catch (Exception e) {
                    return pair[1];
                }
            }
        }
        return "";
    }

    private void sendFileDownload(HttpExchange exchange, byte[] data, String filename, String contentType) throws Exception {
        exchange.getResponseHeaders().set("Content-Type", "application/octet-stream");
        exchange.getResponseHeaders().set("Content-Disposition", "attachment; filename=\"" + filename + "\"");
        exchange.getResponseHeaders().set("Content-Length", String.valueOf(data.length));
        exchange.getResponseHeaders().set("Access-Control-Allow-Origin", "*");
        exchange.getResponseHeaders().set("Cache-Control", "no-cache, no-store, must-revalidate");
        exchange.getResponseHeaders().set("Pragma", "no-cache");
        exchange.getResponseHeaders().set("Expires", "0");
        exchange.sendResponseHeaders(200, data.length);
        OutputStream os = exchange.getResponseBody();
        os.write(data);
        os.close();
    }

    private void sendJsonResponse(HttpExchange exchange, String response) throws Exception {
        exchange.getResponseHeaders().set("Content-Type", "application/json; charset=UTF-8");
        exchange.getResponseHeaders().set("Access-Control-Allow-Origin", "*");
        exchange.sendResponseHeaders(200, response.getBytes().length);
        OutputStream os = exchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}

