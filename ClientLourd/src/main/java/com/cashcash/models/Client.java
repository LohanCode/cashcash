package com.cashcash.models;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlElementWrapper;
import javax.xml.bind.annotation.XmlRootElement;
import java.util.ArrayList;
import java.util.List;

/**
 * Classe représentant un client dans le système CashCash.
 * Cette classe respecte les conventions de nommage : PascalCase pour la classe
 * et camelCase pour les méthodes et attributs.
 */
@XmlRootElement(name = "Client")
public class Client {

    private int id;
    private String numClient;
    private String raisSociale;
    private String siren;
    private int codeApe;
    private String adresseClient;
    private String telephoneClient; // Using String instead of int because phone numbers can have leading zeros
    private String emailClient;
    private String dureeDeplacement;
    private double distanceKm;
    
    private List<Materiel> materiels;

    /**
     * Constructeur par défaut nécessaire pour JAXB (XML).
     */
    public Client() {
        this.materiels = new ArrayList<>();
    }

    // --- Getters & Setters ---

    @XmlElement(name = "Id")
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    @XmlElement(name = "NumClient")
    public String getNumClient() {
        return numClient;
    }

    public void setNumClient(String numClient) {
        this.numClient = numClient;
    }

    @XmlElement(name = "RaisonSociale")
    public String getRaisSociale() {
        return raisSociale;
    }

    public void setRaisSociale(String raisSociale) {
        this.raisSociale = raisSociale;
    }

    @XmlElement(name = "Siren")
    public String getSiren() {
        return siren;
    }

    public void setSiren(String siren) {
        this.siren = siren;
    }

    @XmlElement(name = "CodeApe")
    public int getCodeApe() {
        return codeApe;
    }

    public void setCodeApe(int codeApe) {
        this.codeApe = codeApe;
    }

    @XmlElement(name = "Adresse")
    public String getAdresseClient() {
        return adresseClient;
    }

    public void setAdresseClient(String adresseClient) {
        this.adresseClient = adresseClient;
    }

    @XmlElement(name = "Telephone")
    public String getTelephoneClient() {
        return telephoneClient;
    }

    public void setTelephoneClient(String telephoneClient) {
        this.telephoneClient = telephoneClient;
    }

    @XmlElement(name = "Email")
    public String getEmailClient() {
        return emailClient;
    }

    public void setEmailClient(String emailClient) {
        this.emailClient = emailClient;
    }

    @XmlElement(name = "DureeDeplacement")
    public String getDureeDeplacement() {
        return dureeDeplacement;
    }

    public void setDureeDeplacement(String dureeDeplacement) {
        this.dureeDeplacement = dureeDeplacement;
    }

    @XmlElement(name = "DistanceKm")
    public double getDistanceKm() {
        return distanceKm;
    }

    public void setDistanceKm(double distanceKm) {
        this.distanceKm = distanceKm;
    }

    @XmlElementWrapper(name = "Materiels")
    @XmlElement(name = "Materiel")
    public List<Materiel> getMateriels() {
        return materiels;
    }

    public void setMateriels(List<Materiel> materiels) {
        this.materiels = materiels;
    }

    public void addMateriel(Materiel m) {
        this.materiels.add(m);
    }
}
