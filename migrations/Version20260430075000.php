<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260430075000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add code_famille column to famille table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE famille ADD code_famille VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE famille DROP COLUMN code_famille');
    }
}
