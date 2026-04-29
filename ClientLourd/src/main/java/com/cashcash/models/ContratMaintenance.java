package com.cashcash.models;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;
import java.time.LocalDate;

/**
 * Classe représentant un contrat de maintenance d'un matériel.
 * Permet de déterminer si un matériel est "sous contrat" ou non.
 */
@XmlRootElement(name = "ContratMaintenance")
public class ContratMaintenance {

    private int id;
    private String numContrat;
    private LocalDate dateSignature;
    private LocalDate dateEcheance;
    private String typeContrat;

    public ContratMaintenance() {}

    public ContratMaintenance(int id, String numContrat, LocalDate dateSignature, LocalDate dateEcheance, String typeContrat) {
        this.id = id;
        this.numContrat = numContrat;
        this.dateSignature = dateSignature;
        this.dateEcheance = dateEcheance;
        this.typeContrat = typeContrat;
    }

    @XmlElement(name = "Id")
    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    @XmlElement(name = "NumContrat")
    public String getNumContrat() {
        return numContrat;
    }

    public void setNumContrat(String numContrat) {
        this.numContrat = numContrat;
    }

    @XmlElement(name = "DateSignature")
    public LocalDate getDateSignature() {
        return dateSignature;
    }

    public void setDateSignature(LocalDate dateSignature) {
        this.dateSignature = dateSignature;
    }

    @XmlElement(name = "DateEcheance")
    public LocalDate getDateEcheance() {
        return dateEcheance;
    }

    public void setDateEcheance(LocalDate dateEcheance) {
        this.dateEcheance = dateEcheance;
    }

    @XmlElement(name = "TypeContrat")
    public String getTypeContrat() {
        return typeContrat;
    }

    public void setTypeContrat(String typeContrat) {
        this.typeContrat = typeContrat;
    }

    /**
     * Vérifie si le contrat est actif (entre la date de signature et d'échéance).
     * @return true si le contrat est actif, false sinon
     */
    public boolean isActive() {
        LocalDate today = LocalDate.now();
        return !today.isBefore(dateSignature) && !today.isAfter(dateEcheance);
    }

    /**
     * Vérifie si le contrat est expiré.
     * @return true si le contrat est expiré, false sinon
     */
    public boolean isExpired() {
        return LocalDate.now().isAfter(dateEcheance);
    }
}
