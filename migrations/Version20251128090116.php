<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251128090116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, num_agence VARCHAR(255) NOT NULL, nom_agence VARCHAR(255) DEFAULT NULL, adresse_agence VARCHAR(255) DEFAULT NULL, tel_agence INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, num_client VARCHAR(255) NOT NULL, rais_sociale VARCHAR(255) DEFAULT NULL, siren BIGINT DEFAULT NULL, code_ape INT DEFAULT NULL, adresse_client VARCHAR(255) DEFAULT NULL, telephone_client INT DEFAULT NULL, email_client VARCHAR(255) DEFAULT NULL, duree_deplacement VARCHAR(255) DEFAULT NULL, distance_km NUMERIC(10, 2) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE contrat_maintenance (id INT AUTO_INCREMENT NOT NULL, num_contrat VARCHAR(255) NOT NULL, date_signature DATETIME DEFAULT NULL, date_echeance DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE controler (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, num_intervenant VARCHAR(255) NOT NULL, temps_passe VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, nom_employe VARCHAR(255) DEFAULT NULL, prenom_employe VARCHAR(255) DEFAULT NULL, adresse_employe VARCHAR(255) DEFAULT NULL, date_embauche DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, num_intervenant VARCHAR(255) NOT NULL, date_visite DATETIME DEFAULT NULL, heure_visite TIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, date_vente DATETIME DEFAULT NULL, date_installation DATETIME DEFAULT NULL, prix_vente NUMERIC(10, 2) DEFAULT NULL, emplacement VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE technicien (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, tel_mobile INT DEFAULT NULL, qualif VARCHAR(255) DEFAULT NULL, date_obtention DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, ref_type_contrat VARCHAR(255) NOT NULL, delail_intervention VARCHAR(255) DEFAULT NULL, taux_applicable VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_materiel (id INT AUTO_INCREMENT NOT NULL, ref_interne VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contrat_maintenance');
        $this->addSql('DROP TABLE controler');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE technicien');
        $this->addSql('DROP TABLE type_contrat');
        $this->addSql('DROP TABLE type_materiel');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
