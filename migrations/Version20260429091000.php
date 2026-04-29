<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429091000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove unused controler table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS controler');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE controler (id INT AUTO_INCREMENT NOT NULL, num_serie VARCHAR(255) NOT NULL, num_intervenant VARCHAR(255) NOT NULL, temps_passe VARCHAR(255) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 ENGINE = InnoDB');
    }
}
