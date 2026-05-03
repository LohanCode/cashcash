package com.cashcash.models;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;
import java.util.ArrayList;
import java.util.List;

/**
 * Classe représentant un matériel possédé par un client.
 */
@XmlRootElement(name = "Materiel")
public class Materiel {

    private int id;
    private String numSerie;
    private String dateVente;
    private String dateInstallation;
    private double prixVente;
    private String emplacement;
    private List<ContratMaintenance> contrats;

    public Materiel() {
        this.contrats = new ArrayList<>();
    }

    @XmlElement(name = "Id")
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    @XmlElement(name = "NumSerie")
    public String getNumSerie() {
        return numSerie;
    }

    public void setNumSerie(String numSerie) {
        this.numSerie = numSerie;
    }

    @XmlElement(name = "DateVente")
    public String getDateVente() {
        return dateVente;
    }

    public void setDateVente(String dateVente) {
        this.dateVente = dateVente;
    }

    @XmlElement(name = "DateInstallation")
    public String getDateInstallation() {
        return dateInstallation;
    }

    public void setDateInstallation(String dateInstallation) {
        this.dateInstallation = dateInstallation;
    }

    @XmlElement(name = "PrixVente")
    public double getPrixVente() {
        return prixVente;
    }

    public void setPrixVente(double prixVente) {
        this.prixVente = prixVente;
    }

    @XmlElement(name = "Emplacement")
    public String getEmplacement() {
        return emplacement;
    }

    public void setEmplacement(String emplacement) {
        this.emplacement = emplacement;
    }

    public List<ContratMaintenance> getContrats() {
        return contrats;
    }

    public void setContrats(List<ContratMaintenance> contrats) {
        this.contrats = contrats;
    }

    public void addContrat(ContratMaintenance contrat) {
        this.contrats.add(contrat);
    }
}
