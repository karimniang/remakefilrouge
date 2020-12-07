<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203152241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brief_livrable_attendu (brief_id INT NOT NULL, livrable_attendu_id INT NOT NULL, INDEX IDX_B91E74A6757FABFF (brief_id), INDEX IDX_B91E74A675180ACC (livrable_attendu_id), PRIMARY KEY(brief_id, livrable_attendu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brief_livrable_attendu ADD CONSTRAINT FK_B91E74A6757FABFF FOREIGN KEY (brief_id) REFERENCES brief (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief_livrable_attendu ADD CONSTRAINT FK_B91E74A675180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brief ADD livrables LONGTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brief_livrable_attendu DROP FOREIGN KEY FK_B91E74A675180ACC');
        $this->addSql('DROP TABLE brief_livrable_attendu');
        $this->addSql('DROP TABLE livrable_attendu');
        $this->addSql('ALTER TABLE brief DROP livrables');
    }
}
