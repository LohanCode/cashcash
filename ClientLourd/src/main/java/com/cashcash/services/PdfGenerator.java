package com.cashcash.services;

import com.cashcash.models.Client;
import com.cashcash.models.Materiel;
import com.itextpdf.text.Document;
import com.itextpdf.text.DocumentException;
import com.itextpdf.text.Font;
import com.itextpdf.text.Paragraph;
import com.itextpdf.text.pdf.PdfWriter;

import java.io.FileOutputStream;

public class PdfGenerator {

    /**
     * Génère un fichier PDF contenant la lettre de relance pour un client.
     * @param client L'objet Client
     * @param destPath Le chemin du fichier PDF de destination
     */
    public static void generatePdf(Client client, String destPath) throws Exception {
        Document document = new Document();
        
        try {
            PdfWriter.getInstance(document, new FileOutputStream(destPath));
            document.open();

            // Polices
            Font titleFont = new Font(Font.FontFamily.HELVETICA, 18, Font.BOLD);
            Font regularFont = new Font(Font.FontFamily.HELVETICA, 12, Font.NORMAL);

            // Titre
            Paragraph titre = new Paragraph("Lettre de relance de maintenance", titleFont);
            titre.setAlignment(Paragraph.ALIGN_CENTER);
            titre.setSpacingAfter(20);
            document.add(titre);

            // Coordonnées client
            document.add(new Paragraph("A l'attention de : " + client.getRaisSociale(), regularFont));
            document.add(new Paragraph("Adresse : " + client.getAdresseClient(), regularFont));
            document.add(new Paragraph("Telephone : " + client.getTelephoneClient(), regularFont));
            document.add(new Paragraph(" "));
            
            // Corps du message
            document.add(new Paragraph("Madame, Monsieur,", regularFont));
            document.add(new Paragraph("Nous vous prions de bien vouloir trouver ci-dessous la liste " +
                    "de vos materiels necessitant une visite de maintenance.", regularFont));
            document.add(new Paragraph(" "));

            // Liste des matériels
            for (Materiel m : client.getMateriels()) {
                document.add(new Paragraph("- Materiel NS: " + m.getNumSerie() + " (Emplacement: " + m.getEmplacement() + ")", regularFont));
            }

            document.add(new Paragraph(" "));
            document.add(new Paragraph("Dans l'attente de votre retour, nous vous prions d'agreer " +
                    "nos salutations distinguees.", regularFont));
            document.add(new Paragraph("L'equipe CashCash.", regularFont));

            System.out.println("Fichier PDF genere avec succes : " + destPath);
        } catch (DocumentException e) {
            System.err.println("Erreur lors de la creation du document PDF");
            throw e;
        } finally {
            if (document.isOpen()) {
                document.close();
            }
        }
    }
}
