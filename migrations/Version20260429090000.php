<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429090000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add TypeContrat relation to ContratMaintenance';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contrat_maintenance ADD type_contrat_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat_maintenance ADD CONSTRAINT FK_3D14B3C9C7A36A39 FOREIGN KEY (type_contrat_id) REFERENCES type_contrat (id)');
        $this->addSql('CREATE INDEX IDX_3D14B3C9C7A36A39 ON contrat_maintenance (type_contrat_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contrat_maintenance DROP FOREIGN KEY FK_3D14B3C9C7A36A39');
        $this->addSql('DROP INDEX IDX_3D14B3C9C7A36A39 ON contrat_maintenance');
        $this->addSql('ALTER TABLE contrat_maintenance DROP COLUMN type_contrat_id');
    }
}
