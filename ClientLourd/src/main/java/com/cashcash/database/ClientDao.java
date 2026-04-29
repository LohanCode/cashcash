package com.cashcash.database;

import com.cashcash.models.Client;
import com.cashcash.models.Materiel;

import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

/**
 * Data Access Object pour Client et ses Matériels.
 */
public class ClientDao {

    /**
     * Recherche un client par son NumClient et charge ses matériels.
     * @param numClient l'identifiant métier du client
     * @return un objet Client rempli, ou null si introuvable
     */
    public Client getClientByNum(String numClient) {
        Client client = null;
        Connection conn = DatabaseConnection.getInstance();
        if (conn == null) return null;

        String queryClient = "SELECT * FROM client WHERE num_client = ?";
        
        try (PreparedStatement pstmt = conn.prepareStatement(queryClient)) {
            pstmt.setString(1, numClient);
            ResultSet rs = pstmt.executeQuery();

            if (rs.next()) {
                client = new Client();
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
                
                // Charger le matériel associé
                chargerMateriels(client, conn);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }

        return client;
    }

    private void chargerMateriels(Client client, Connection conn) {
        String query = "SELECT * FROM materiel WHERE client_id = ?";
        try (PreparedStatement pstmt = conn.prepareStatement(query)) {
            pstmt.setInt(1, client.getId());
            ResultSet rs = pstmt.executeQuery();

            while (rs.next()) {
                Materiel m = new Materiel();
                m.setId(rs.getInt("id"));
                m.setNumSerie(rs.getString("num_serie"));
                m.setDateVente(rs.getString("date_vente"));
                m.setDateInstallation(rs.getString("date_installation"));
                m.setPrixVente(rs.getDouble("prix_vente"));
                m.setEmplacement(rs.getString("emplacement"));
                
                client.addMateriel(m);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}
