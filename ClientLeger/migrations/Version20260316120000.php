<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260316120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add missing matricule column to utilisateur table';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQLPlatform),
            'This migration is intended for MySQL.'
        );

        $this->addSql('ALTER TABLE utilisateur ADD matricule VARCHAR(20) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B31F0AF90D ON utilisateur (matricule)');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQLPlatform),
            'This migration is intended for MySQL.'
        );

        $this->addSql('ALTER TABLE utilisateur DROP INDEX UNIQ_1D1C63B31F0AF90D');
        $this->addSql('ALTER TABLE utilisateur DROP COLUMN matricule');
    }
}
