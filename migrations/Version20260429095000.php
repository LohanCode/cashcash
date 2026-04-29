<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429095000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make TypeContrat not nullable in ContratMaintenance';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contrat_maintenance MODIFY type_contrat_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contrat_maintenance MODIFY type_contrat_id INT DEFAULT NULL');
    }
}
