<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251212075135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, num_agence VARCHAR(255) NOT NULL, nom_agence VARCHAR(255) DEFAULT NULL, adresse_agence VARCHAR(255) DEFAULT NULL, tel_agence INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, num_client VARCHAR(255) NOT NULL, rais_sociale VARCHAR(255) DEFAULT NULL, siren BIGINT DEFAULT NULL, code_ape INT DEFAULT NULL, adresse_client VARCHAR(255) DEFAULT NULL, telephone_client INT DEFAULT NULL, email_client VARCHAR(255) DEFAULT NULL, duree_deplacement VARCHAR(255) DEFAULT NULL, distance_km NUMERIC(10, 2) DEFAULT NULL, agence_id INT NOT NULL, INDEX IDX_C7440455D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE contrat_maintenance (id INT AUTO_INCREMENT NOT NULL, num_contrat VARCHAR(255) NOT NULL, date_signature DATETIME DEFAULT NULL, date_echeance DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE controler (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, num_intervenant VARCHAR(255) NOT NULL, temps_passe VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, nom_employe VARCHAR(255) DEFAULT NULL, prenom_employe VARCHAR(255) DEFAULT NULL, adresse_employe VARCHAR(255) DEFAULT NULL, date_embauche DATE DEFAULT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, tel_mobile INT DEFAULT NULL, qualification VARCHAR(255) DEFAULT NULL, date_obtention_qualification DATE DEFAULT NULL, agence_id INT DEFAULT NULL, INDEX IDX_F804D3B9D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, code_famille VARCHAR(255) NOT NULL, libelle_famille VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, date_visite DATE DEFAULT NULL, heure_visite TIME DEFAULT NULL, client_id INT NOT NULL, technicien_id INT NOT NULL, INDEX IDX_D11814AB19EB6921 (client_id), INDEX IDX_D11814AB13457256 (technicien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, date_vente DATETIME DEFAULT NULL, date_installation DATETIME DEFAULT NULL, prix_vente NUMERIC(10, 2) DEFAULT NULL, emplacement VARCHAR(255) DEFAULT NULL, client_id INT NOT NULL, type_materiel_id INT NOT NULL, contrat_maintenance_id INT DEFAULT NULL, INDEX IDX_18D2B09119EB6921 (client_id), INDEX IDX_18D2B0915D91DD3E (type_materiel_id), INDEX IDX_18D2B09195269DC1 (contrat_maintenance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE technicien (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, tel_mobile INT DEFAULT NULL, qualif VARCHAR(255) DEFAULT NULL, date_obtention DATETIME DEFAULT NULL, mot_de_passe_tech VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, ref_type_contrat VARCHAR(255) NOT NULL, delail_intervention VARCHAR(255) DEFAULT NULL, taux_applicable VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_materiel (id INT AUTO_INCREMENT NOT NULL, ref_interne VARCHAR(255) NOT NULL, libelle_type_materiel VARCHAR(255) NOT NULL, famille_id INT NOT NULL, INDEX IDX_D52D976D97A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB13457256 FOREIGN KEY (technicien_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0915D91DD3E FOREIGN KEY (type_materiel_id) REFERENCES type_materiel (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09195269DC1 FOREIGN KEY (contrat_maintenance_id) REFERENCES contrat_maintenance (id)');
        $this->addSql('ALTER TABLE type_materiel ADD CONSTRAINT FK_D52D976D97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455D725330D');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9D725330D');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB19EB6921');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB13457256');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09119EB6921');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0915D91DD3E');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09195269DC1');
        $this->addSql('ALTER TABLE type_materiel DROP FOREIGN KEY FK_D52D976D97A77B84');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contrat_maintenance');
        $this->addSql('DROP TABLE controler');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE technicien');
        $this->addSql('DROP TABLE type_contrat');
        $this->addSql('DROP TABLE type_materiel');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
