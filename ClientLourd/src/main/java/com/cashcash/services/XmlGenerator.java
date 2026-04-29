package com.cashcash.services;

import com.cashcash.models.Client;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import java.io.File;

public class XmlGenerator {

    /**
     * Génère un fichier XML à partir d'un objet Client.
     * @param client L'objet Client à sérialiser
     * @param destPath Le chemin du fichier de destination
     */
    public static void generateXml(Client client, String destPath) throws JAXBException {
        // Création du contexte JAXB pour la classe Client
        JAXBContext context = JAXBContext.newInstance(Client.class);
        Marshaller marshaller = context.createMarshaller();
        
        // Formatage pour avoir un beau XML indenté
        marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, Boolean.TRUE);

        // Ecriture dans le fichier
        File file = new File(destPath);
        marshaller.marshal(client, file);
        System.out.println("Fichier XML genere avec succes : " + file.getAbsolutePath());
    }
}
