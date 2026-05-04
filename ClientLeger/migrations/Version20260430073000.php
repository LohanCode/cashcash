<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260430073000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create famille table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE famille (id INT AUTO_INCREMENT NOT NULL, libelle_famille VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE famille');
    }
}
