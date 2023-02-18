<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230217213650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE species DROP FOREIGN KEY FK_A50FF712D8099476');
        $this->addSql('ALTER TABLE tax_class DROP FOREIGN KEY FK_5E31ECBE69B9E007');
        $this->addSql('ALTER TABLE strain DROP FOREIGN KEY FK_A630CD726976FEC0');
        $this->addSql('DROP TABLE organism_group');
        $this->addSql('DROP TABLE strain');
        $this->addSql('DROP TABLE kingdom');
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP INDEX IDX_A50FF712D8099476 ON species');
        $this->addSql('ALTER TABLE species DROP organism_group_id');
        $this->addSql('DROP INDEX IDX_5E31ECBE69B9E007 ON tax_class');
        $this->addSql('ALTER TABLE tax_class DROP strain_id');
        $this->addSql('ALTER TABLE user ADD is_verified TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE organism_group (id INT AUTO_INCREMENT NOT NULL, scientific_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, vernacular_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, taxonomy_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE strain (id INT AUTO_INCREMENT NOT NULL, kingdom_id INT DEFAULT NULL, scientific_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, vernacular_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, taxonomy_id INT NOT NULL, INDEX IDX_A630CD726976FEC0 (kingdom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE kingdom (id INT AUTO_INCREMENT NOT NULL, scientific_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, vernacular_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, taxonomy_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE area (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE strain ADD CONSTRAINT FK_A630CD726976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id)');
        $this->addSql('ALTER TABLE species ADD organism_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE species ADD CONSTRAINT FK_A50FF712D8099476 FOREIGN KEY (organism_group_id) REFERENCES organism_group (id)');
        $this->addSql('CREATE INDEX IDX_A50FF712D8099476 ON species (organism_group_id)');
        $this->addSql('ALTER TABLE `user` DROP is_verified');
        $this->addSql('ALTER TABLE tax_class ADD strain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tax_class ADD CONSTRAINT FK_5E31ECBE69B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id)');
        $this->addSql('CREATE INDEX IDX_5E31ECBE69B9E007 ON tax_class (strain_id)');
    }
}
