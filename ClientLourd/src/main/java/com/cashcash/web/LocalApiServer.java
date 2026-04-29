package com.cashcash.web;

import com.sun.net.httpserver.HttpServer;
import com.sun.net.httpserver.HttpExchange;
import com.sun.net.httpserver.HttpHandler;

import java.io.OutputStream;
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

                    String result = bridge.generateClientXml(numClient, filename);
                    sendResponse(exchange, result);
                } catch (Exception e) {
                    LOGGER.log(Level.SEVERE, "Erreur API XML", e);
                    try {
                        sendResponse(exchange, "{\"error\": \"" + e.getMessage() + "\"}");
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

                    String result = bridge.generateClientPdf(numClient, filename);
                    sendResponse(exchange, result);
                } catch (Exception e) {
                    LOGGER.log(Level.SEVERE, "Erreur API PDF", e);
                    try {
                        sendResponse(exchange, "{\"error\": \"" + e.getMessage() + "\"}");
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
                    sendResponse(exchange, result);
                } catch (Exception e) {
                    LOGGER.log(Level.SEVERE, "Erreur API data", e);
                    try {
                        sendResponse(exchange, "{\"error\": \"" + e.getMessage() + "\"}");
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

    private void sendResponse(HttpExchange exchange, String response) throws Exception {
        exchange.getResponseHeaders().set("Content-Type", "application/json; charset=UTF-8");
        exchange.getResponseHeaders().set("Access-Control-Allow-Origin", "*");
        exchange.sendResponseHeaders(200, response.getBytes().length);
        OutputStream os = exchange.getResponseBody();
        os.write(response.getBytes());
        os.close();
    }
}
