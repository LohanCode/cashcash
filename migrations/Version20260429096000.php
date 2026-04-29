<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429096000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix typo: rename delail_intervention to delai_intervention in type_contrat table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE type_contrat CHANGE delail_intervention delai_intervention VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE type_contrat CHANGE delai_intervention delail_intervention VARCHAR(255) DEFAULT NULL');
    }
}
