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

public class MainApp extends Application {

    private ClientDao clientDao = new ClientDao();
    private Client currentClient = null;

    @Override
    public void start(Stage primaryStage) {
        primaryStage.setTitle("Client Lourd CashCash");

        // UI Components
        Label lblNumClient = new Label("Num Client :");
        TextField txtNumClient = new TextField();
        Button btnSearch = new Button("Rechercher");

        TextArea txtResult = new TextArea();
        txtResult.setEditable(false);
        txtResult.setPrefHeight(200);

        Button btnXml = new Button("Generer XML");
        btnXml.setDisable(true);
        Button btnPdf = new Button("Generer PDF de relance");
        btnPdf.setDisable(true);

        // Actions
        btnSearch.setOnAction(e -> {
            String num = txtNumClient.getText().trim();
            if(!num.isEmpty()) {
                currentClient = clientDao.getClientByNum(num);
                if (currentClient != null) {
                    txtResult.setText("Client trouve : " + currentClient.getRaisSociale() + "\n"
                            + "Materiels : " + currentClient.getMateriels().size());
                    btnXml.setDisable(false);
                    btnPdf.setDisable(false);
                } else {
                    txtResult.setText("Aucun client trouve pour ce numero ou erreur de connexion.");
                    btnXml.setDisable(true);
                    btnPdf.setDisable(true);
                }
            }
        });

        btnXml.setOnAction(e -> {
            if(currentClient != null) {
                try {
                    String path = "client_" + currentClient.getNumClient() + ".xml";
                    XmlGenerator.generateXml(currentClient, path);
                    showAlert(Alert.AlertType.INFORMATION, "Succes", "Fichier XML genere : " + path);
                } catch (Exception ex) {
                    ex.printStackTrace();
                    showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la generation du XML.");
                }
            }
        });

        btnPdf.setOnAction(e -> {
            if(currentClient != null) {
                try {
                    String path = "relance_" + currentClient.getNumClient() + ".pdf";
                    PdfGenerator.generatePdf(currentClient, path);
                    showAlert(Alert.AlertType.INFORMATION, "Succes", "Fichier PDF genere : " + path);
                } catch (Exception ex) {
                    ex.printStackTrace();
                    showAlert(Alert.AlertType.ERROR, "Erreur", "Erreur lors de la generation du PDF.");
                }
            }
        });

        // Layouts
        HBox topBox = new HBox(10, lblNumClient, txtNumClient, btnSearch);
        topBox.setAlignment(Pos.CENTER_LEFT);

        HBox bottomBox = new HBox(10, btnXml, btnPdf);
        bottomBox.setAlignment(Pos.CENTER);

        VBox mainBox = new VBox(15, topBox, txtResult, bottomBox);
        mainBox.setPadding(new Insets(20));

        Scene scene = new Scene(mainBox, 450, 350);
        primaryStage.setScene(scene);
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
