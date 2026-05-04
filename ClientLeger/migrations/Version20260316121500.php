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
        $this->abortIf(
            !($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQLPlatform),
            'This migration is intended for MySQL.'
        );

        $this->addSql('ALTER TABLE intervention ADD titre VARCHAR(255) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD statut VARCHAR(50) DEFAULT NULL, ADD gravite VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->abortIf(
            !($this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\MySQLPlatform),
            'This migration is intended for MySQL.'
        );

        $this->addSql('ALTER TABLE intervention DROP COLUMN gravite, DROP COLUMN statut, DROP COLUMN description, DROP COLUMN titre');
    }
}
