<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125124544 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE groupe_competence ADD deleted TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE niveau_evaluation ADD deleted TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE competence DROP deleted');
        $this->addSql('ALTER TABLE groupe_competence DROP deleted');
        $this->addSql('ALTER TABLE niveau_evaluation DROP deleted');
    }
}
