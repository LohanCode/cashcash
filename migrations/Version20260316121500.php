<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260316121500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add missing intervention columns (titre, description, statut, gravite)';
    }

    public function up(Schema $schema): void
    {
        $platformClass = $this->connection->getDatabasePlatform()::class;

        if (str_contains($platformClass, 'PostgreSQL')) {
            $this->addSql('ALTER TABLE intervention ADD titre VARCHAR(255) DEFAULT NULL');
            $this->addSql('ALTER TABLE intervention ADD description TEXT DEFAULT NULL');
            $this->addSql('ALTER TABLE intervention ADD statut VARCHAR(50) DEFAULT NULL');
            $this->addSql('ALTER TABLE intervention ADD gravite VARCHAR(50) DEFAULT NULL');
            return;
        }

        if (str_contains($platformClass, 'MySQL')) {
            $this->addSql('ALTER TABLE intervention ADD titre VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD statut VARCHAR(50) DEFAULT NULL, ADD gravite VARCHAR(50) DEFAULT NULL');
            return;
        }

        $this->abortIf(true, sprintf('Unsupported platform "%s" for this migration.', $platformClass));
    }

    public function down(Schema $schema): void
    {
        $platformClass = $this->connection->getDatabasePlatform()::class;

        if (str_contains($platformClass, 'PostgreSQL')) {
            $this->addSql('ALTER TABLE intervention DROP gravite');
            $this->addSql('ALTER TABLE intervention DROP statut');
            $this->addSql('ALTER TABLE intervention DROP description');
            $this->addSql('ALTER TABLE intervention DROP titre');
            return;
        }

        if (str_contains($platformClass, 'MySQL')) {
            $this->addSql('ALTER TABLE intervention DROP COLUMN gravite, DROP COLUMN statut, DROP COLUMN description, DROP COLUMN titre');
            return;
        }

        $this->abortIf(true, sprintf('Unsupported platform "%s" for this migration.', $platformClass));
    }
}
