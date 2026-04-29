package com.cashcash.database;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

/**
 * Singleton de connexion à la base de données MySQL.
 */
public class DatabaseConnection {

    private static final String URL = "jdbc:mysql://127.0.0.1:3306/cashcash?serverTimezone=UTC";
    private static final String USER = "root";
    private static final String PWD = "";
    
    private static Connection connection = null;

    /**
     * Retourne l'instance unique de la connexion JDBC.
     */
    public static Connection getInstance() {
        if (connection == null) {
            try {
                // Charger explicitement le driver
                Class.forName("com.mysql.cj.jdbc.Driver");
                connection = DriverManager.getConnection(URL, USER, PWD);
                System.out.println("Connexion JDBC a MySQL reussie !");
            } catch (SQLException | ClassNotFoundException e) {
                System.err.println("Erreur de connexion a la base de donnees : " + e.getMessage());
            }
        }
        return connection;
    }
}
