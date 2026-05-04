package com.cashcash;

import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * Point d'entrée de l'application CashCash ClientLourd.
 * Lance l'interface web intégrée via WebInterfaceApp.
 */
public class Launcher {

    private static final Logger LOGGER = Logger.getLogger(Launcher.class.getName());

    public static void main(String[] args) {
        try {
            LOGGER.info("Lancement du ClientLourd CashCash (Interface Native)...");
            // Lancer l'interface native JavaFX 100% indépendante
            MainApp.main(args);
        } catch (Exception e) {
            LOGGER.log(Level.SEVERE, "Erreur lors du lancement de l'application", e);
            System.exit(1);
        }
    }
}
