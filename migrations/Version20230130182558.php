<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230130182558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE area (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE family (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tax_order_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_A5E6215B6D0BCC86 FOREIGN KEY (tax_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A5E6215B6D0BCC86 ON family (tax_order_id)');
        $this->addSql('CREATE TABLE genus (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, family_id INTEGER NOT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_38C5106EC35E566A FOREIGN KEY (family_id) REFERENCES family (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_38C5106EC35E566A ON genus (family_id)');
        $this->addSql('CREATE TABLE kingdom (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, taxonomy_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_F5299398EA000B10 FOREIGN KEY (class_id) REFERENCES tax_class (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F5299398EA000B10 ON "order" (class_id)');
        $this->addSql('CREATE TABLE organism_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, taxonomy_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE sighting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, species_id INTEGER NOT NULL, location VARCHAR(255) DEFAULT NULL, date_time DATETIME NOT NULL, comment VARCHAR(512) DEFAULT NULL, CONSTRAINT FK_6E9336F4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6E9336F4B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6E9336F4A76ED395 ON sighting (user_id)');
        $this->addSql('CREATE INDEX IDX_6E9336F4B2A1D860 ON sighting (species_id)');
        $this->addSql('CREATE TABLE species (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, genus_id INTEGER DEFAULT NULL, organism_group_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_A50FF71285C4074C FOREIGN KEY (genus_id) REFERENCES genus (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A50FF712D8099476 FOREIGN KEY (organism_group_id) REFERENCES organism_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A50FF71285C4074C ON species (genus_id)');
        $this->addSql('CREATE INDEX IDX_A50FF712D8099476 ON species (organism_group_id)');
        $this->addSql('CREATE TABLE strain (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, kingdom_id INTEGER DEFAULT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, taxonomy_id INTEGER NOT NULL, CONSTRAINT FK_A630CD726976FEC0 FOREIGN KEY (kingdom_id) REFERENCES kingdom (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A630CD726976FEC0 ON strain (kingdom_id)');
        $this->addSql('CREATE TABLE tax_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, strain_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_5E31ECBE69B9E007 FOREIGN KEY (strain_id) REFERENCES strain (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5E31ECBE69B9E007 ON tax_class (strain_id)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE area');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE genus');
        $this->addSql('DROP TABLE kingdom');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE organism_group');
        $this->addSql('DROP TABLE sighting');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE strain');
        $this->addSql('DROP TABLE tax_class');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
