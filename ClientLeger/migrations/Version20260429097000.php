<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429097000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Simplify Controler table: remove num_serie and num_intervenant columns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler DROP COLUMN num_serie');
        $this->addSql('ALTER TABLE controler DROP COLUMN num_intervenant');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler ADD num_serie VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE controler ADD num_intervenant VARCHAR(255) NOT NULL');
    }
}
