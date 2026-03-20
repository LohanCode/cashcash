<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260316120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add missing matricule column to utilisateur table for PostgreSQL/MySQL compatibility';
    }

    public function up(Schema $schema): void
    {
        $platformClass = $this->connection->getDatabasePlatform()::class;

        if (str_contains($platformClass, 'PostgreSQL')) {
            $this->addSql('ALTER TABLE utilisateur ADD matricule VARCHAR(20) DEFAULT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B31F0AF90D ON utilisateur (matricule)');
            return;
        }

        if (str_contains($platformClass, 'MySQL')) {
            $this->addSql('ALTER TABLE utilisateur ADD matricule VARCHAR(20) DEFAULT NULL');
            $this->addSql('CREATE UNIQUE INDEX UNIQ_1D1C63B31F0AF90D ON utilisateur (matricule)');
            return;
        }

        $this->abortIf(true, sprintf('Unsupported platform "%s" for this migration.', $platformClass));
    }

    public function down(Schema $schema): void
    {
        $platformClass = $this->connection->getDatabasePlatform()::class;

        if (str_contains($platformClass, 'PostgreSQL')) {
            $this->addSql('DROP INDEX UNIQ_1D1C63B31F0AF90D');
            $this->addSql('ALTER TABLE utilisateur DROP matricule');
            return;
        }

        if (str_contains($platformClass, 'MySQL')) {
            $this->addSql('ALTER TABLE utilisateur DROP INDEX UNIQ_1D1C63B31F0AF90D');
            $this->addSql('ALTER TABLE utilisateur DROP COLUMN matricule');
            return;
        }

        $this->abortIf(true, sprintf('Unsupported platform "%s" for this migration.', $platformClass));
    }
}
