<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429099000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove id column from Controler table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler DROP COLUMN id');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler ADD id INT AUTO_INCREMENT UNIQUE FIRST');
    }
}
