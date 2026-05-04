<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429094000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove Famille table and its relation from TypeMateriel';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE type_materiel DROP FOREIGN KEY FK_D52D976D97A77B84');
        $this->addSql('DROP INDEX IDX_D52D976D97A77B84 ON type_materiel');
        $this->addSql('ALTER TABLE type_materiel DROP COLUMN famille_id');
        $this->addSql('DROP TABLE famille');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, code_famille VARCHAR(255) NOT NULL, libelle_famille VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type_materiel ADD famille_id INT NOT NULL');
        $this->addSql('ALTER TABLE type_materiel ADD CONSTRAINT FK_D52D976D97A77B84 FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('CREATE INDEX IDX_D52D976D97A77B84 ON type_materiel (famille_id)');
    }
}
