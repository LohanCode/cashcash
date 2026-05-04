package com.cashcash;

import com.cashcash.database.ClientDao;
import com.cashcash.models.Client;
import com.cashcash.services.PdfGenerator;
import com.cashcash.services.XmlGenerator;
import javafx.application.Application;
import javafx.geometry.Insets;
import javafx.geometry.Pos;
import javafx.scene.Scene;
import javafx.scene.control.*;
import javafx.scene.layout.HBox;
import javafx.scene.layout.VBox;
import javafx.stage.Stage;
import java.util.logging.Level;
import java.util.logging.Logger;

/**
 * Interface graphique principale du Client Lourd CashCash.
 * <p>
 * Permet au gérant de rechercher un client par son numéro,
 * d'afficher ses informations et de générer des documents
 * de relance au format XML et PDF.
 * </p>
 * <p>
 * Architecture : cette classe joue le rôle de Vue et de Contrôleur
 * dans le pattern MVC. Elle s'appuie sur {@link ClientDao} pour l'accès
 * aux données et sur {@link XmlGenerator} / {@link PdfGenerator} pour
 * la génération de documents.
 * </p>
 *
 * @author Lohan Poulain
 * @version 1.0
 */
public class MainApp extends Application {

    /** Logger pour tracer les actions et les erreurs de l'IHM. */
    private static final Logger LOGGER = Logger.getLogger(MainApp.class.getName());

    /** DAO utilisé pour interroger la base de données MySQL. */
    private ClientDao clientDao = new ClientDao();

    /** Client actuellement sélectionné dans l'interface (null si aucun). */
    private Client currentClient = null;

    /**
     * Initialise et affiche la fenêtre principale de l'application.
     * <p>
     * Construit tous les composants de l'IHM, définit leurs identifiants
     * et configure leurs actions (événements).
     * </p>
     *
     * @param primaryStage la fenêtre principale JavaFX
     */
    @Override
    public void start(Stage primaryStage) {
        primaryStage.setTitle("Client Lourd CashCash");

        // --- Composants de l'IHM ---

        // Étiquette indiquant le champ de saisie du numéro client
        Label lblNumClient = new Label("Num Client :");

        // Champ de saisie pour entrer le numéro du client à rechercher
        TextField txtNumClient = new TextField();
        txtNumClient.setId("txtNumClient");

        // Bouton déclenchant la recherche du client en base de données
        Button btnSearch = new Button("Rechercher");
        btnSearch.setId("btnSearch");

        // Zone d'affichage des informations du client trouvé
        TextArea txtResult = new TextArea();
        txtResult.setId("txtResult");
        txtResult.setEditable(false);
        txtResult.setPrefHeight(200);

        // Bouton de génération du fichier XML (désactivé par défaut, activé après une
        // recherche réussie)
        Button btnXml = new Button("Generer XML");
        btnXml.setId("btnXml");
        btnXml.setDisable(true);

        // Bouton de génération de la lettre de relance PDF (désactivé par défaut,
        // activé après une recherche réussie)
        Button btnPdf = new Button("Generer PDF de relance");
        btnPdf.setId("btnPdf");
        btnPdf.setDisable(true);

        // --- Gestion des événements ---

        // Recherche d'un client par son numéro
        btnSearch.setOnAction(e -> {
            String num = txtNumClient.getText().trim();
            if (!num.isEmpty()) {
                LOGGER.info("Recherche du client numéro : " + num);
                currentClient = clientDao.getClientByNum(num);
                if (currentClient != null) {
                    txtResult.setText("Client trouve : " + currentClient.getRaisSociale() + "\n"
                            + "Materiels : " + currentClient.getMateriels().size());
                    btnXml.setDisable(false);
                    btnPdf.setDisable(false);
                    LOGGER.info("Client trouvé : " + currentClient.getRaisSociale());
                } else {
                    txtResult.setText("Aucun client trouve pour ce numero ou erreur de connexion.");
                    btnXml.setDisable(true);
                    btnPdf.setDisable(true);
                    LOGGER.warning("Aucun client trouvé pour le numéro : " + num);
                }
            }
        });

        // Génération du fichier XML de relance
        btnXml.setOnAction(e -> {
            if (currentClient != null) {
                javafx.stage.FileChooser fileChooser = new javafx.stage.FileChooser();
                fileChooser.setTitle("Enregistrer l'export XML");
                fileChooser.setInitialFileName("client_" + currentClient.getNumClient() + ".xml");
                fileChooser.getExtensionFilters().add(new javafx.stage.FileChooser.ExtensionFilter("Fichiers XML", "*.xml"));
                
                java.io.File file = fileChooser.showSaveDialog(primaryStage);
                if (file != null) {
                    try {
                        XmlGenerator.generateXml(currentClient, file.getAbsolutePath());
                        LOGGER.info("Fichier XML généré : " + file.getAbsolutePath());
                        showAlert(Alert.AlertType.INFORMATION, "Succès", "Fichier XML enregistré avec succès !");
                    } catch (Exception ex) {
                        LOGGER.log(Level.SEVERE, "Erreur lors de la génération du fichier XML", ex);
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la génération du XML.");
                    }
                }
            }
        });

        // Génération de la lettre de relance PDF
        btnPdf.setOnAction(e -> {
            if (currentClient != null) {
                javafx.stage.FileChooser fileChooser = new javafx.stage.FileChooser();
                fileChooser.setTitle("Enregistrer la lettre de relance PDF");
                fileChooser.setInitialFileName("relance_" + currentClient.getNumClient() + ".pdf");
                fileChooser.getExtensionFilters().add(new javafx.stage.FileChooser.ExtensionFilter("Fichiers PDF", "*.pdf"));

                java.io.File file = fileChooser.showSaveDialog(primaryStage);
                if (file != null) {
                    try {
                        PdfGenerator.generatePdf(currentClient, file.getAbsolutePath());
                        LOGGER.info("Fichier PDF généré : " + file.getAbsolutePath());
                        showAlert(Alert.AlertType.INFORMATION, "Succès", "Fichier PDF enregistré avec succès !");
                    } catch (Exception ex) {
                        LOGGER.log(Level.SEVERE, "Erreur lors de la génération du fichier PDF", ex);
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la génération du PDF.");
                    }
                }
            }
        });

        // --- Construction de la mise en page ---

        // Ligne du haut : saisie du numéro + bouton rechercher
        HBox topBox = new HBox(10, lblNumClient, txtNumClient, btnSearch);
        topBox.setAlignment(Pos.CENTER_LEFT);

        // Ligne du bas : boutons d'export
        HBox bottomBox = new HBox(10, btnXml, btnPdf);
        bottomBox.setAlignment(Pos.CENTER);

        // Disposition verticale principale
        VBox mainBox = new VBox(15, topBox, txtResult, bottomBox);
        mainBox.setPadding(new Insets(20));

        Scene scene = new Scene(mainBox, 450, 350);
        primaryStage.setScene(scene);
        primaryStage.show();
        LOGGER.info("Interface MainApp affichée.");
    }

    /**
     * Affiche une boîte de dialogue à l'utilisateur.
     *
     * @param type    le type d'alerte (INFORMATION, ERROR, etc.)
     * @param title   le titre de la boîte de dialogue
     * @param content le message affiché à l'utilisateur
     */
    private void showAlert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }

    /**
     * Point d'entrée JavaFX (appelé par {@link Launcher}).
     *
     * @param args arguments de la ligne de commande
     */
    public static void main(String[] args) {
        launch(args);
    }
}
