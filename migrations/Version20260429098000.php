<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429098000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Make Controler have composite primary key (intervention_id, materiel_id)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler MODIFY id INT');
        $this->addSql('ALTER TABLE controler DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE controler DROP COLUMN id');
        $this->addSql('ALTER TABLE controler ADD PRIMARY KEY (intervention_id, materiel_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE controler ADD id INT AUTO_INCREMENT UNIQUE');
        $this->addSql('ALTER TABLE controler ADD PRIMARY KEY (id)');
    }
}
