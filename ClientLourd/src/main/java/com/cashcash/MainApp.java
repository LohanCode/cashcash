package com.cashcash;

import com.cashcash.database.ClientDao;
import com.cashcash.models.Client;
import com.cashcash.models.Materiel;
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
import javafx.collections.FXCollections;
import java.util.List;
import java.util.ArrayList;
import java.util.logging.Level;
import java.util.logging.Logger;

public class MainApp extends Application {

    private static final Logger LOGGER = Logger.getLogger(MainApp.class.getName());
    private ClientDao clientDao = new ClientDao();
    private Client currentClient = null;

    @Override
    public void start(Stage primaryStage) {
        primaryStage.setTitle("Client Lourd CashCash");

        Label lblClient = new Label("Choisir Client :");
        ComboBox<Client> comboClients = new ComboBox<>(FXCollections.observableArrayList(clientDao.getAllClients()));
        comboClients.setPrefWidth(200);
        comboClients.setPromptText("Sélectionnez un client...");

        Button btnSearch = new Button("Charger Données");
        Button btnNouveauType = new Button("Nouveau Type");
        TextArea txtResult = new TextArea();
        txtResult.setEditable(false);
        txtResult.setPrefHeight(250);

        Button btnXml = new Button("Generer XML");
        Button btnPdf = new Button("Generer PDF");
        Button btnCouvrir = new Button("Couvrir par Contrat");
        Button btnAjouterMateriel = new Button("Ajouter Matériel");
        btnXml.setDisable(true);
        btnPdf.setDisable(true);
        btnCouvrir.setDisable(true);
        btnAjouterMateriel.setDisable(true);

        btnSearch.setOnAction(e -> {
            Client selected = comboClients.getValue();
            if (selected != null) {
                currentClient = clientDao.getClientByNum(selected.getNumClient());
                if (currentClient != null) {
                    txtResult.setText("Client : " + currentClient.getRaisSociale() + "\n");
                    txtResult.appendText("Nombre de matériels : " + currentClient.getMateriels().size() + "\n");
                    txtResult.appendText("----------------------------------\n");

                    for (Materiel m : currentClient.getMateriels()) {
                        String status = (!m.getContrats().isEmpty()) ? "Couvert" : "NON COUVERT";
                        txtResult.appendText("- NS: " + m.getNumSerie() + " (" + status + ")\n");
                    }
                    btnXml.setDisable(false);
                    btnPdf.setDisable(false);
                    btnCouvrir.setDisable(false);
                    btnAjouterMateriel.setDisable(false);
                }
            }
        });

        btnXml.setOnAction(e -> {
            if (currentClient != null) {
                javafx.stage.FileChooser fc = new javafx.stage.FileChooser();
                fc.setInitialFileName("client_" + currentClient.getNumClient() + ".xml");
                java.io.File file = fc.showSaveDialog(primaryStage);
                if (file != null) {
                    try {
                        XmlGenerator.generateXml(currentClient, file.getAbsolutePath());
                        showAlert(Alert.AlertType.INFORMATION, "Succès", "Fichier XML enregistré.");
                    } catch (Exception ex) {
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la génération XML.");
                    }
                }
            }
        });

        btnPdf.setOnAction(e -> {
            if (currentClient != null) {
                javafx.stage.FileChooser fc = new javafx.stage.FileChooser();
                fc.setInitialFileName("relance_" + currentClient.getNumClient() + ".pdf");
                java.io.File file = fc.showSaveDialog(primaryStage);
                if (file != null) {
                    try {
                        PdfGenerator.generatePdf(currentClient, file.getAbsolutePath());
                        showAlert(Alert.AlertType.INFORMATION, "Succès", "Fichier PDF enregistré.");
                    } catch (Exception ex) {
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la génération PDF.");
                    }
                }
            }
        });

        btnCouvrir.setOnAction(e -> {
            if (currentClient != null && !currentClient.getMateriels().isEmpty()) {
                List<String> series = new ArrayList<>();
                for (Materiel m : currentClient.getMateriels()) {
                    series.add(m.getNumSerie());
                }
                ChoiceDialog<String> dialog = new ChoiceDialog<>(series.get(0), series);
                dialog.setTitle("Nouveau Contrat");
                dialog.setHeaderText("Sélectionnez le matériel à couvrir");
                dialog.showAndWait().ifPresent(ns -> {
                    if (clientDao.ajouterContratMaintenance(ns)) {
                        showAlert(Alert.AlertType.INFORMATION, "Succès", "Contrat créé.");
                        btnSearch.fire();
                    } else {
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la création.");
                    }
                });
            }
        });

        btnAjouterMateriel.setOnAction(e -> {
            if (currentClient != null) {
                // Étape 1 : Saisie du numéro de série
                TextInputDialog dialogNs = new TextInputDialog();
                dialogNs.setTitle("Nouveau Matériel");
                dialogNs.setHeaderText("Ajouter un matériel pour : " + currentClient.getRaisSociale());
                dialogNs.setContentText("Numéro de série :");
                dialogNs.showAndWait().ifPresent(numSerie -> {
                    String ns = numSerie.trim();
                    if (ns.isEmpty()) {
                        showAlert(Alert.AlertType.WARNING, "Attention", "Le numéro de série ne peut pas être vide.");
                        return;
                    }
                    // Étape 2 : Choix du type de matériel
                    java.util.Map<Integer, String> types = clientDao.getAllTypesMateriel();
                    if (types.isEmpty()) {
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Aucun type de matériel disponible en base.");
                        return;
                    }
                    List<String> typeLabels = new ArrayList<>(types.values());
                    ChoiceDialog<String> dialogType = new ChoiceDialog<>(typeLabels.get(0), typeLabels);
                    dialogType.setTitle("Type de Matériel");
                    dialogType.setHeaderText("Sélectionnez le type pour : " + ns);
                    dialogType.setContentText("Type :");
                    dialogType.showAndWait().ifPresent(chosenLabel -> {
                        // Retrouver l'ID correspondant au libellé choisi
                        int typeMaterielId = types.entrySet().stream()
                                .filter(entry -> entry.getValue().equals(chosenLabel))
                                .findFirst().map(java.util.Map.Entry::getKey).orElse(-1);
                        if (clientDao.ajouterMateriel(ns, currentClient.getId(), typeMaterielId)) {
                            showAlert(Alert.AlertType.INFORMATION, "Succès", "Matériel '" + ns + "' ajouté !");
                            btnSearch.fire();
                        } else {
                            showAlert(Alert.AlertType.ERROR, "Erreur", "Impossible d'ajouter le matériel.");
                        }
                    });
                });
            }
        });

        btnNouveauType.setOnAction(e -> {
            // Étape 1 : libellé
            TextInputDialog d1 = new TextInputDialog();
            d1.setTitle("Nouveau Type de Matériel");
            d1.setHeaderText("Créer un nouveau type de matériel");
            d1.setContentText("Libellé (ex: Terminal de caisse) :");
            d1.showAndWait().ifPresent(libelle -> {
                if (libelle.trim().isEmpty()) {
                    showAlert(Alert.AlertType.WARNING, "Attention", "Libellé vide.");
                    return;
                }
                // Étape 2 : référence interne
                TextInputDialog d2 = new TextInputDialog();
                d2.setTitle("Référence Interne");
                d2.setHeaderText("Référence interne pour : " + libelle.trim());
                d2.setContentText("Réf (ex: TERM-001) :");
                d2.showAndWait().ifPresent(ref -> {
                    if (ref.trim().isEmpty()) {
                        showAlert(Alert.AlertType.WARNING, "Attention", "Référence vide.");
                        return;
                    }
                    // Étape 3 : famille
                    java.util.Map<Integer, String> familles = clientDao.getAllFamilles();
                    if (familles.isEmpty()) {
                        showAlert(Alert.AlertType.ERROR, "Erreur", "Aucune famille disponible.");
                        return;
                    }
                    List<String> famLabels = new ArrayList<>(familles.values());
                    ChoiceDialog<String> d3 = new ChoiceDialog<>(famLabels.get(0), famLabels);
                    d3.setTitle("Famille");
                    d3.setHeaderText("Famille du type : " + libelle.trim());
                    d3.setContentText("Famille :");
                    d3.showAndWait().ifPresent(famLabel -> {
                        int famId = familles.entrySet().stream()
                                .filter(entry -> entry.getValue().equals(famLabel))
                                .findFirst().map(java.util.Map.Entry::getKey).orElse(-1);
                        if (clientDao.ajouterTypeMateriel(libelle.trim(), ref.trim(), famId)) {
                            showAlert(Alert.AlertType.INFORMATION, "Succès",
                                    "Type '" + libelle.trim() + "' créé avec succès !");
                        } else {
                            showAlert(Alert.AlertType.WARNING, "Doublon détecté",
                                    "Le type '" + libelle.trim() + "' existe déjà en base de données !");
                        }
                    });
                });
            });
        });

        HBox top = new HBox(10, lblClient, comboClients, btnSearch, btnNouveauType);
        top.setAlignment(Pos.CENTER_LEFT);
        HBox bot = new HBox(10, btnXml, btnPdf, btnCouvrir, btnAjouterMateriel);
        bot.setAlignment(Pos.CENTER);
        VBox root = new VBox(15, top, txtResult, bot);
        root.setPadding(new Insets(20));

        primaryStage.setScene(new Scene(root, 550, 450));
        primaryStage.show();
    }

    private void showAlert(Alert.AlertType type, String title, String content) {
        Alert alert = new Alert(type);
        alert.setTitle(title);
        alert.setHeaderText(null);
        alert.setContentText(content);
        alert.showAndWait();
    }

    public static void main(String[] args) {
        launch(args);
    }
}
