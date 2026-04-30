<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260430076000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Link type_materiel to famille with foreign key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE type_materiel ADD famille_id INT NOT NULL');
        $this->addSql('ALTER TABLE type_materiel ADD CONSTRAINT FK_type_materiel_famille FOREIGN KEY (famille_id) REFERENCES famille (id)');
        $this->addSql('CREATE INDEX IDX_type_materiel_famille ON type_materiel (famille_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE type_materiel DROP FOREIGN KEY FK_type_materiel_famille');
        $this->addSql('DROP INDEX IDX_type_materiel_famille ON type_materiel');
        $this->addSql('ALTER TABLE type_materiel DROP COLUMN famille_id');
    }
}
