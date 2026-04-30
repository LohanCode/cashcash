package com.cashcash;

import javafx.application.Application;
import javafx.geometry.Rectangle2D;
import javafx.scene.Scene;
import javafx.scene.web.WebEngine;
import javafx.scene.web.WebView;
import javafx.stage.Screen;
import javafx.stage.Stage;
import netscape.javascript.JSObject;

import java.io.File;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * Application JavaFX utilisant WebView pour afficher l'interface Symfony.
 * Intègre l'interface web du projet CashCash dans le ClientLourd.
 */
public class WebInterfaceApp extends Application {

    private static final Logger LOGGER = Logger.getLogger(WebInterfaceApp.class.getName());
    private WebEngine webEngine;

    @Override
    public void start(Stage primaryStage) {
        try {
            // Démarrer le serveur API local
            com.cashcash.web.LocalApiServer apiServer = new com.cashcash.web.LocalApiServer();
            apiServer.start();

            // Configuration de la fenêtre
            primaryStage.setTitle("CashCash - Interface Web");

            // Créer la WebView
            WebView webView = new WebView();
            webEngine = webView.getEngine();

            // Configuration du WebEngine
            webEngine.setJavaScriptEnabled(true);

            // Charger la page HTML
            loadWebInterface();

            // Créer la scène
            Scene scene = new Scene(webView);
            primaryStage.setScene(scene);

            // Dimensionner la fenêtre (80% de l'écran)
            Rectangle2D screenBounds = Screen.getPrimary().getVisualBounds();
            primaryStage.setWidth(screenBounds.getWidth() * 0.8);
            primaryStage.setHeight(screenBounds.getHeight() * 0.8);
            primaryStage.setX((screenBounds.getWidth() - primaryStage.getWidth()) / 2);
            primaryStage.setY((screenBounds.getHeight() - primaryStage.getHeight()) / 2);

            primaryStage.show();
            LOGGER.info("Application WebInterface démarrée");

        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors du démarrage de l'application", e);
        }
    }

    /**
     * Charge l'interface web depuis les fichiers statiques.
     * Supporte deux modes :
     * 1. Chargement via serveur local (http://localhost:8000)
     * 2. Chargement depuis fichiers locaux
     */
    private void loadWebInterface() {
        try {
            // Exposer le bridge Java au JavaScript
            com.cashcash.web.JavaScriptBridge bridge = new com.cashcash.web.JavaScriptBridge();
            JSObject jsObject = (JSObject) webEngine.executeScript("window");
            jsObject.setMember("javaApplication", bridge);

            // Mode 1 : Charger via serveur Symfony local
            String localServerUrl = "http://localhost:8000/";

            try {
                // Vérifier si le serveur est disponible
                java.net.URL url = new java.net.URL(localServerUrl);
                java.net.URLConnection conn = url.openConnection();
                conn.setConnectTimeout(2000);
                conn.connect();

                webEngine.load(localServerUrl);
                LOGGER.info("Interface web chargée depuis le serveur local : " + localServerUrl);
                return;
            } catch (Exception e) {
                LOGGER.warning("Serveur local non disponible, essai du chargement depuis fichiers");
            }

            // Mode 2 : Charger depuis fichiers statiques locaux
            String filePath = new File("./templates/base.html.twig").getAbsolutePath();
            String fileUrl = "file:///" + filePath.replace("\\", "/");

            if (new File(filePath).exists()) {
                webEngine.load(fileUrl);
                LOGGER.info("Interface web chargée depuis le fichier : " + fileUrl);
            } else {
                LOGGER.severe("Fichier interface web introuvable : " + filePath);
            }

        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors du chargement de l'interface web", e);
        }
    }

    /**
     * Permet au MainApp d'accéder au WebEngine pour des appels JavaScript.
     */
    public WebEngine getWebEngine() {
        return webEngine;
    }

    public static void main(String[] args) {
        launch(args);
    }
}
