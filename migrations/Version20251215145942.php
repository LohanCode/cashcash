<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251215145942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $platformClass = $this->connection->getDatabasePlatform()::class;

        if (str_contains($platformClass, 'PostgreSQL')) {
            $this->addSql('CREATE TABLE agence (id SERIAL NOT NULL, num_agence VARCHAR(255) NOT NULL, nom_agence VARCHAR(255) DEFAULT NULL, adresse_agence VARCHAR(255) DEFAULT NULL, tel_agence INT DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE TABLE client (id SERIAL NOT NULL, num_client VARCHAR(255) NOT NULL, rais_sociale VARCHAR(255) DEFAULT NULL, siren BIGINT DEFAULT NULL, code_ape INT DEFAULT NULL, adresse_client VARCHAR(255) DEFAULT NULL, telephone_client INT DEFAULT NULL, email_client VARCHAR(255) DEFAULT NULL, duree_deplacement VARCHAR(255) DEFAULT NULL, distance_km NUMERIC(10, 2) DEFAULT NULL, agence_id INT NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_C7440455D725330D ON client (agence_id)');
            $this->addSql('CREATE TABLE contrat_maintenance (id SERIAL NOT NULL, num_contrat VARCHAR(255) NOT NULL, date_signature TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_echeance TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE TABLE controler (id SERIAL NOT NULL, num_serie VARCHAR(255) NOT NULL, num_intervenant VARCHAR(255) NOT NULL, temps_passe VARCHAR(255) DEFAULT NULL, commentaire TEXT DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE TABLE famille (id SERIAL NOT NULL, code_famille VARCHAR(255) NOT NULL, libelle_famille VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE TABLE intervention (id SERIAL NOT NULL, date_visite DATE DEFAULT NULL, heure_visite TIME(0) WITHOUT TIME ZONE DEFAULT NULL, client_id INT NOT NULL, technicien_id INT NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_D11814AB19EB6921 ON intervention (client_id)');
            $this->addSql('CREATE INDEX IDX_D11814AB13457256 ON intervention (technicien_id)');
            $this->addSql('CREATE TABLE materiel (id SERIAL NOT NULL, num_serie VARCHAR(255) NOT NULL, date_vente TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_installation TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, prix_vente NUMERIC(10, 2) DEFAULT NULL, emplacement VARCHAR(255) DEFAULT NULL, client_id INT NOT NULL, type_materiel_id INT NOT NULL, contrat_maintenance_id INT DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_18D2B09119EB6921 ON materiel (client_id)');
            $this->addSql('CREATE INDEX IDX_18D2B0915D91DD3E ON materiel (type_materiel_id)');
            $this->addSql('CREATE INDEX IDX_18D2B09195269DC1 ON materiel (contrat_maintenance_id)');
            $this->addSql('CREATE TABLE type_contrat (id SERIAL NOT NULL, ref_type_contrat VARCHAR(255) NOT NULL, delail_intervention VARCHAR(255) DEFAULT NULL, taux_applicable VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE TABLE type_materiel (id SERIAL NOT NULL, ref_interne VARCHAR(255) NOT NULL, libelle_type_materiel VARCHAR(255) NOT NULL, famille_id INT NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_D52D976D97A77B84 ON type_materiel (famille_id)');
            $this->addSql('CREATE TABLE utilisateur (id SERIAL NOT NULL, type_utilisateur VARCHAR(50) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, agence_id INT NOT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B3E7927C74 ON utilisateur (email)');
            $this->addSql('CREATE INDEX IDX_1D1C63B3D725330D ON utilisateur (agence_id)');
            $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
            $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
            $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
            $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');

            $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB13457256 FOREIGN KEY (technicien_id) REFERENCES utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09119EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0915D91DD3E FOREIGN KEY (type_materiel_id) REFERENCES type_materiel (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09195269DC1 FOREIGN KEY (contrat_maintenance_id) REFERENCES contrat_maintenance (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE type_materiel ADD CONSTRAINT FK_D52D976D97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
            $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

            return;
        }

        $this->abortIf(!str_contains($platformClass, 'MySQL'), sprintf('Unsupported platform "%s" for this migration.', $platformClass));

        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, num_agence VARCHAR(255) NOT NULL, nom_agence VARCHAR(255) DEFAULT NULL, adresse_agence VARCHAR(255) DEFAULT NULL, tel_agence INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, num_client VARCHAR(255) NOT NULL, rais_sociale VARCHAR(255) DEFAULT NULL, siren BIGINT DEFAULT NULL, code_ape INT DEFAULT NULL, adresse_client VARCHAR(255) DEFAULT NULL, telephone_client INT DEFAULT NULL, email_client VARCHAR(255) DEFAULT NULL, duree_deplacement VARCHAR(255) DEFAULT NULL, distance_km NUMERIC(10, 2) DEFAULT NULL, agence_id INT NOT NULL, INDEX IDX_C7440455D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE contrat_maintenance (id INT AUTO_INCREMENT NOT NULL, num_contrat VARCHAR(255) NOT NULL, date_signature DATETIME DEFAULT NULL, date_echeance DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE controler (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, num_intervenant VARCHAR(255) NOT NULL, temps_passe VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, code_famille VARCHAR(255) NOT NULL, libelle_famille VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE intervention (id INT AUTO_INCREMENT NOT NULL, date_visite DATE DEFAULT NULL, heure_visite TIME DEFAULT NULL, client_id INT NOT NULL, technicien_id INT NOT NULL, INDEX IDX_D11814AB19EB6921 (client_id), INDEX IDX_D11814AB13457256 (technicien_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE materiel (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, date_vente DATETIME DEFAULT NULL, date_installation DATETIME DEFAULT NULL, prix_vente NUMERIC(10, 2) DEFAULT NULL, emplacement VARCHAR(255) DEFAULT NULL, client_id INT NOT NULL, type_materiel_id INT NOT NULL, contrat_maintenance_id INT DEFAULT NULL, INDEX IDX_18D2B09119EB6921 (client_id), INDEX IDX_18D2B0915D91DD3E (type_materiel_id), INDEX IDX_18D2B09195269DC1 (contrat_maintenance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_contrat (id INT AUTO_INCREMENT NOT NULL, ref_type_contrat VARCHAR(255) NOT NULL, delail_intervention VARCHAR(255) DEFAULT NULL, taux_applicable VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE type_materiel (id INT AUTO_INCREMENT NOT NULL, ref_interne VARCHAR(255) NOT NULL, libelle_type_materiel VARCHAR(255) NOT NULL, famille_id INT NOT NULL, INDEX IDX_D52D976D97A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, type_utilisateur VARCHAR(50) NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, agence_id INT NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3E7927C74 (email), INDEX IDX_1D1C63B3D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB13457256 FOREIGN KEY (technicien_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09119EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B0915D91DD3E FOREIGN KEY (type_materiel_id) REFERENCES type_materiel (id)');
        $this->addSql('ALTER TABLE materiel ADD CONSTRAINT FK_18D2B09195269DC1 FOREIGN KEY (contrat_maintenance_id) REFERENCES contrat_maintenance (id)');
        $this->addSql('ALTER TABLE type_materiel ADD CONSTRAINT FK_D52D976D97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
    }

    public function down(Schema $schema): void
    {
        $platformClass = $this->connection->getDatabasePlatform()::class;

        if (str_contains($platformClass, 'PostgreSQL')) {
            $this->addSql('DROP TABLE IF EXISTS messenger_messages');
            $this->addSql('DROP TABLE IF EXISTS intervention');
            $this->addSql('DROP TABLE IF EXISTS materiel');
            $this->addSql('DROP TABLE IF EXISTS utilisateur');
            $this->addSql('DROP TABLE IF EXISTS type_materiel');
            $this->addSql('DROP TABLE IF EXISTS type_contrat');
            $this->addSql('DROP TABLE IF EXISTS controler');
            $this->addSql('DROP TABLE IF EXISTS contrat_maintenance');
            $this->addSql('DROP TABLE IF EXISTS client');
            $this->addSql('DROP TABLE IF EXISTS famille');
            $this->addSql('DROP TABLE IF EXISTS agence');

            return;
        }

        $this->abortIf(!str_contains($platformClass, 'MySQL'), sprintf('Unsupported platform "%s" for this migration.', $platformClass));

        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455D725330D');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB19EB6921');
        $this->addSql('ALTER TABLE intervention DROP FOREIGN KEY FK_D11814AB13457256');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09119EB6921');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B0915D91DD3E');
        $this->addSql('ALTER TABLE materiel DROP FOREIGN KEY FK_18D2B09195269DC1');
        $this->addSql('ALTER TABLE type_materiel DROP FOREIGN KEY FK_D52D976D97A77B84');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3D725330D');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE contrat_maintenance');
        $this->addSql('DROP TABLE controler');
        $this->addSql('DROP TABLE famille');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE materiel');
        $this->addSql('DROP TABLE type_contrat');
        $this->addSql('DROP TABLE type_materiel');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
