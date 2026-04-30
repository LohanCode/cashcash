<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260430074000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make contrat_maintenance_id NOT NULL in materiel table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE materiel MODIFY contrat_maintenance_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE materiel MODIFY contrat_maintenance_id INT');
    }
}
