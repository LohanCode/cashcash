<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429093000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Intervention and Materiel relations to Controler table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler ADD intervention_id INT NOT NULL, ADD materiel_id INT NOT NULL');
        $this->addSql('ALTER TABLE controler ADD CONSTRAINT FK_INTERVENTION FOREIGN KEY (intervention_id) REFERENCES intervention (id)');
        $this->addSql('ALTER TABLE controler ADD CONSTRAINT FK_MATERIEL FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
        $this->addSql('CREATE INDEX IDX_CONTROLER_INTERVENTION ON controler (intervention_id)');
        $this->addSql('CREATE INDEX IDX_CONTROLER_MATERIEL ON controler (materiel_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE controler DROP FOREIGN KEY FK_INTERVENTION');
        $this->addSql('ALTER TABLE controler DROP FOREIGN KEY FK_MATERIEL');
        $this->addSql('DROP INDEX IDX_CONTROLER_INTERVENTION ON controler');
        $this->addSql('DROP INDEX IDX_CONTROLER_MATERIEL ON controler');
        $this->addSql('ALTER TABLE controler DROP COLUMN intervention_id, DROP COLUMN materiel_id');
    }
}
