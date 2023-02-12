<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210817170753 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE family (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, position_id INT DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, affiliatenumber VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, INDEX IDX_A5E6215B8565851 (establishment_id), INDEX IDX_A5E6215BDD842E46 (position_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worker (id INT AUTO_INCREMENT NOT NULL, establishment_id INT DEFAULT NULL, position_id INT DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, affiliatenumber VARCHAR(255) DEFAULT NULL, birthday DATETIME DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, INDEX IDX_9FB2BF628565851 (establishment_id), INDEX IDX_9FB2BF62DD842E46 (position_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE family ADD CONSTRAINT FK_A5E6215B8565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE family ADD CONSTRAINT FK_A5E6215BDD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF628565851 FOREIGN KEY (establishment_id) REFERENCES establishment (id)');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF62DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('DROP TABLE adherent_adherent');
        $this->addSql('DROP TABLE establishement');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE adherent_adherent (adherent_source INT NOT NULL, adherent_target INT NOT NULL, INDEX IDX_9A562F754B882921 (adherent_source), INDEX IDX_9A562F75526D79AE (adherent_target), PRIMARY KEY(adherent_source, adherent_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE establishement (id INT AUTO_INCREMENT NOT NULL, status TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE adherent_adherent ADD CONSTRAINT FK_9A562F754B882921 FOREIGN KEY (adherent_source) REFERENCES adherent (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE adherent_adherent ADD CONSTRAINT FK_9A562F75526D79AE FOREIGN KEY (adherent_target) REFERENCES adherent (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE worker');
    }
}
