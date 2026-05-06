package com.cashcash.database;

import com.cashcash.models.Client;
import com.cashcash.models.Materiel;
import com.cashcash.models.ContratMaintenance;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.logging.Level;
import java.util.logging.Logger;

public class ClientDao {

    private static final Logger LOGGER = Logger.getLogger(ClientDao.class.getName());

    public List<Client> getAllClients() {
        List<Client> clients = new ArrayList<>();
        String sql = "SELECT id, num_client, rais_sociale FROM client ORDER BY rais_sociale";
        try (Connection conn = DatabaseConnection.getInstance()) {
            if (conn != null) {
                try (Statement stmt = conn.createStatement();
                     ResultSet rs = stmt.executeQuery(sql)) {
                    while (rs.next()) {
                        Client c = new Client();
                        c.setId(rs.getInt("id"));
                        c.setNumClient(rs.getString("num_client"));
                        c.setRaisSociale(rs.getString("rais_sociale"));
                        clients.add(c);
                    }
                }
            } else {
                LOGGER.severe("Connexion MySQL impossible (null)");
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur getAllClients", e);
        }
        return clients;
    }

    public Client getClientByNum(String numClient) {
        Client client = null;
        String sql = "SELECT id, num_client, rais_sociale FROM client WHERE num_client = ?";
        try (Connection conn = DatabaseConnection.getInstance();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, numClient);
            try (ResultSet rs = pstmt.executeQuery()) {
                if (rs.next()) {
                    client = new Client();
                    client.setId(rs.getInt("id"));
                    client.setNumClient(rs.getString("num_client"));
                    client.setRaisSociale(rs.getString("rais_sociale"));
                    chargerMateriels(client, conn);
                }
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "ERREUR SQL dans getClientByNum : " + e.getMessage(), e);
        }
        return client;
    }

    private void chargerMateriels(Client client, Connection conn) {
        String sql = "SELECT id, num_serie FROM materiel WHERE client_id = ?"; 
        try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, client.getId());
            try (ResultSet rs = pstmt.executeQuery()) {
                int count = 0;
                while (rs.next()) {
                    Materiel m = new Materiel();
                    m.setId(rs.getInt("id"));
                    m.setNumSerie(rs.getString("num_serie"));
                    chargerContrats(m, conn);
                    client.addMateriel(m);
                    count++;
                }
                LOGGER.info(count + " matériels chargés pour le client.");
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "ERREUR SQL dans chargerMateriels : " + e.getMessage(), e);
        }
    }

    private void chargerContrats(Materiel m, Connection conn) {
        String sql = "SELECT id, date_echeance FROM contrat_maintenance WHERE materiel_id = ?";
        try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setInt(1, m.getId());
            try (ResultSet rs = pstmt.executeQuery()) {
                while (rs.next()) {
                    ContratMaintenance c = new ContratMaintenance();
                    c.setId(rs.getInt("id"));
                    Date dateExp = rs.getDate("date_echeance");
                    if (dateExp != null) {
                        c.setDateEcheance(dateExp.toLocalDate());
                    }
                    m.addContrat(c);
                }
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur chargerContrats : " + e.getMessage(), e);
        }
    }

    /**
     * Ajoute un nouveau matériel pour un client donné.
     * @param numSerie le numéro de série du matériel
     * @param clientId l'ID du client propriétaire
     * @return true si l'insertion a réussi
     */
    public boolean ajouterMateriel(String numSerie, int clientId, int typeMaterielId) {
        String sql = "INSERT INTO materiel (num_serie, client_id, type_materiel_id) VALUES (?, ?, ?)";
        try (Connection conn = DatabaseConnection.getInstance();
             PreparedStatement pstmt = conn.prepareStatement(sql)) {
            pstmt.setString(1, numSerie);
            pstmt.setInt(2, clientId);
            pstmt.setInt(3, typeMaterielId);
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur ajouterMateriel : " + e.getMessage(), e);
            return false;
        }
    }

    /**
     * Retourne tous les types de matériels disponibles sous forme de paires ID/Libellé.
     */
    public java.util.Map<Integer, String> getAllTypesMateriel() {
        java.util.Map<Integer, String> types = new java.util.LinkedHashMap<>();
        String sql = "SELECT id, libelle_type_materiel FROM type_materiel ORDER BY libelle_type_materiel";
        try (Connection conn = DatabaseConnection.getInstance();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                types.put(rs.getInt("id"), rs.getString("libelle_type_materiel"));
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur getAllTypesMateriel : " + e.getMessage(), e);
        }
        return types;
    }

    /** Retourne les familles disponibles (ID → libellé). */
    public java.util.Map<Integer, String> getAllFamilles() {
        java.util.Map<Integer, String> familles = new java.util.LinkedHashMap<>();
        String sql = "SELECT id, libelle_famille FROM famille ORDER BY libelle_famille";
        try (Connection conn = DatabaseConnection.getInstance();
             Statement stmt = conn.createStatement();
             ResultSet rs = stmt.executeQuery(sql)) {
            while (rs.next()) {
                familles.put(rs.getInt("id"), rs.getString("libelle_famille"));
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur getAllFamilles : " + e.getMessage(), e);
        }
        return familles;
    }

    /** Ajoute un nouveau type de matériel en base (vérifie les doublons). */
    public boolean ajouterTypeMateriel(String libelle, String refInterne, int familleId) {
        // Vérifier si un type avec le même libellé existe déjà
        String checkSql = "SELECT COUNT(*) FROM type_materiel WHERE libelle_type_materiel = ?";
        try (Connection conn = DatabaseConnection.getInstance();
             PreparedStatement pstmtCheck = conn.prepareStatement(checkSql)) {
            pstmtCheck.setString(1, libelle);
            try (ResultSet rs = pstmtCheck.executeQuery()) {
                if (rs.next() && rs.getInt(1) > 0) {
                    LOGGER.warning("Type de matériel '" + libelle + "' existe déjà.");
                    return false; // doublon détecté
                }
            }
            // Pas de doublon → on insère
            String sql = "INSERT INTO type_materiel (libelle_type_materiel, ref_interne, famille_id) VALUES (?, ?, ?)";
            try (PreparedStatement pstmt = conn.prepareStatement(sql)) {
                pstmt.setString(1, libelle);
                pstmt.setString(2, refInterne);
                pstmt.setInt(3, familleId);
                return pstmt.executeUpdate() > 0;
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "Erreur ajouterTypeMateriel : " + e.getMessage(), e);
            return false;
        }
    }

    public boolean ajouterContratMaintenance(String numSerie) {
        // 1. Récupérer l'ID du matériel à partir de son numéro de série
        int materielId = -1;
        String findIdSql = "SELECT id FROM materiel WHERE num_serie = ?";
        
        try (Connection conn = DatabaseConnection.getInstance();
             PreparedStatement pstmtFind = conn.prepareStatement(findIdSql)) {
            pstmtFind.setString(1, numSerie);
            try (ResultSet rs = pstmtFind.executeQuery()) {
                if (rs.next()) materielId = rs.getInt("id");
            }
            
            if (materielId == -1) {
                LOGGER.warning("Matériel introuvable pour le numéro de série : " + numSerie);
                return false;
            }

            // 2. Insérer le contrat avec materiel_id et un numéro de contrat auto-généré
            String insertSql = "INSERT INTO contrat_maintenance (date_signature, date_echeance, materiel_id, num_contrat) " +
                               "VALUES (CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 YEAR), ?, ?)";
            
            try (PreparedStatement pstmtInsert = conn.prepareStatement(insertSql)) {
                pstmtInsert.setInt(1, materielId);
                pstmtInsert.setString(2, "CTR-" + System.currentTimeMillis());
                return pstmtInsert.executeUpdate() > 0;
            }
        } catch (SQLException e) {
            LOGGER.log(Level.SEVERE, "ERREUR CRÉATION CONTRAT : " + e.getMessage(), e);
            return false;
        }
    }
}