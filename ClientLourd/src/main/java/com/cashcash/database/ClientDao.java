package com.cashcash.database;

import com.cashcash.models.Client;
import com.cashcash.models.Materiel;
import com.cashcash.models.ContratMaintenance;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.time.LocalDate;
import java.time.format.DateTimeParseException;
import java.util.logging.Logger;
import java.util.logging.Level;

/**
 * Data Access Object pour Client et ses Matériels.
 */
public class ClientDao {

    private static final Logger LOGGER = Logger.getLogger(ClientDao.class.getName());

    /**
     * Recherche un client par son NumClient et charge ses matériels.
     * 
     * @param numClient l'identifiant métier du client
     * @return un objet Client rempli, ou null si introuvable
     */
    public Client getClientByNum(String numClient) {
        if (numClient == null || numClient.trim().isEmpty()) {
            LOGGER.warning("NumClient vide ou null fourni à getClientByNum");
            return null;
        }

        Client client = null;
        Connection conn = DatabaseConnection.getInstance();
        if (conn == null) {
            LOGGER.severe("Impossible de récupérer la connexion à la base de données");
            return null;
        }

        String queryClient = "SELECT * FROM client WHERE num_client = ?";

        try (PreparedStatement pstmt = conn.prepareStatement(queryClient)) {
            pstmt.setString(1, numClient);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    client = new Client();
                    try {
                        client.setId(rs.getInt("id"));
                        client.setNumClient(rs.getString("num_client"));
                        client.setRaisSociale(rs.getString("rais_sociale"));
                        client.setSiren(rs.getString("siren"));
                        client.setCodeApe(rs.getInt("code_ape"));
                        client.setAdresseClient(rs.getString("adresse_client"));
                        client.setTelephoneClient(rs.getString("telephone_client"));
                        client.setEmailClient(rs.getString("email_client"));
                        client.setDureeDeplacement(rs.getString("duree_deplacement"));
                        client.setDistanceKm(rs.getDouble("distance_km"));

                        // Charger le matériel et les contrats associés
                        chargerMateriels(client, conn);
                        LOGGER.info("Client " + numClient + " chargé avec succès");
                    } catch (NumberFormatException e) {
                        LOGGER.log(Level.SEVERE,
                                "Erreur de conversion de type lors du chargement du client " + numClient, e);
                        return null;
                    }
                } else {
                    LOGGER.warning("Aucun client trouvé avec NumClient: " + numClient);
                }
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur SQL lors de la recherche du client " + numClient, e);
        }

        return client;
    }

    private void chargerMateriels(Client client, Connection conn) {
        String query = "SELECT * FROM materiel WHERE client_id = ?";
        try (PreparedStatement pstmt = conn.prepareStatement(query)) {
            pstmt.setInt(1, client.getId());
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    try {
                        Materiel m = new Materiel();
                        m.setId(rs.getInt("id"));
                        m.setNumSerie(rs.getString("num_serie"));
                        m.setDateVente(rs.getString("date_vente"));
                        m.setDateInstallation(rs.getString("date_installation"));
                        m.setPrixVente(rs.getDouble("prix_vente"));
                        m.setEmplacement(rs.getString("emplacement"));

                        // Charger les contrats de maintenance associés
                        chargerContratsMaintenanceParMateriel(m, conn);

                        client.addMateriel(m);
                        LOGGER.fine("Matériel " + m.getNumSerie() + " chargé pour le client " + client.getNumClient());
                    } catch (NumberFormatException e) {
                        LOGGER.log(Level.WARNING, "Erreur de conversion lors du chargement d'un matériel", e);
                        continue;
                    }
                }
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur SQL lors du chargement des matériels pour le client " + client.getId(), e);
        }
    }

    /**
     * Charge les contrats de maintenance d'un matériel.
     * 
     * @param materiel le matériel dont on veut charger les contrats
     * @param conn     la connexion à la base de données
     */
    private void chargerContratsMaintenanceParMateriel(Materiel materiel, Connection conn) {
        String query = "SELECT cm.* FROM contrat_maintenance cm " +
                "WHERE cm.materiel_id = ? ORDER BY cm.date_echeance DESC";
        try (PreparedStatement pstmt = conn.prepareStatement(query)) {
            pstmt.setInt(1, materiel.getId());
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    try {
                        ContratMaintenance contrat = new ContratMaintenance();
                        contrat.setId(rs.getInt("id"));
                        contrat.setNumContrat(rs.getString("num_contrat"));

                        // Conversion sécurisée des dates
                        try {
                            String dateSignature = rs.getString("date_signature");
                            if (dateSignature != null) {
                                contrat.setDateSignature(LocalDate.parse(dateSignature));
                            }
                        } catch (DateTimeParseException e) {
                            LOGGER.log(Level.WARNING, "Format de date invalide pour date_signature", e);
                        }

                        try {
                            String dateEcheance = rs.getString("date_echeance");
                            if (dateEcheance != null) {
                                contrat.setDateEcheance(LocalDate.parse(dateEcheance));
                            }
                        } catch (DateTimeParseException e) {
                            LOGGER.log(Level.WARNING, "Format de date invalide pour date_echeance", e);
                        }

                        // Ajout du contrat au matériel
                        materiel.addContrat(contrat);

                        LOGGER.fine(
                                "Contrat " + contrat.getNumContrat() + " chargé pour le matériel " + materiel.getId());
                    } catch (Exception e) {
                        LOGGER.log(Level.WARNING, "Erreur lors du chargement d'un contrat de maintenance", e);
                        continue;
                    }
                }
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur SQL lors du chargement des contrats de maintenance", e);
        }
    }
}