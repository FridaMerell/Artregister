<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311133547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, start DATE DEFAULT NULL, ends DATE DEFAULT NULL)');
        $this->addSql('CREATE TABLE card_species (card_id INTEGER NOT NULL, species_id INTEGER NOT NULL, PRIMARY KEY(card_id, species_id), CONSTRAINT FK_F2157D644ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F2157D64B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F2157D644ACC9A20 ON card_species (card_id)');
        $this->addSql('CREATE INDEX IDX_F2157D64B2A1D860 ON card_species (species_id)');
        $this->addSql('CREATE TABLE card_user (card_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(card_id, user_id), CONSTRAINT FK_61A0D4EB4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_61A0D4EBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_61A0D4EB4ACC9A20 ON card_user (card_id)');
        $this->addSql('CREATE INDEX IDX_61A0D4EBA76ED395 ON card_user (user_id)');
        $this->addSql('CREATE TABLE card_sightings (card_id INTEGER NOT NULL, sighting_id INTEGER NOT NULL, PRIMARY KEY(card_id, sighting_id), CONSTRAINT FK_D5B8896C4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D5B8896C34964DD9 FOREIGN KEY (sighting_id) REFERENCES taxon_species (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D5B8896C4ACC9A20 ON card_sightings (card_id)');
        $this->addSql('CREATE INDEX IDX_D5B8896C34964DD9 ON card_sightings (sighting_id)');
        $this->addSql('CREATE TABLE family (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tax_order_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_A5E6215B6D0BCC86 FOREIGN KEY (tax_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A5E6215B6D0BCC86 ON family (tax_order_id)');
        $this->addSql('CREATE TABLE genus (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, family_id INTEGER NOT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_38C5106EC35E566A FOREIGN KEY (family_id) REFERENCES family (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_38C5106EC35E566A ON genus (family_id)');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, class_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_F5299398EA000B10 FOREIGN KEY (class_id) REFERENCES tax_class (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F5299398EA000B10 ON "order" (class_id)');
        $this->addSql('CREATE TABLE species (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, genus_id INTEGER DEFAULT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL, swedish_prominence VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_A50FF71285C4074C FOREIGN KEY (genus_id) REFERENCES genus (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A50FF71285C4074C ON species (genus_id)');
        $this->addSql('CREATE TABLE tax_class (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, taxonomy_id INTEGER NOT NULL, scientific_name VARCHAR(255) NOT NULL, vernacular_name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE taxon_species (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, species_id INTEGER NOT NULL, location VARCHAR(255) DEFAULT NULL, date_time DATETIME NOT NULL, comment VARCHAR(512) DEFAULT NULL, CONSTRAINT FK_82070F8CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_82070F8CB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_82070F8CA76ED395 ON taxon_species (user_id)');
        $this->addSql('CREATE INDEX IDX_82070F8CB2A1D860 ON taxon_species (species_id)');
        $this->addSql('CREATE TABLE "user" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_species');
        $this->addSql('DROP TABLE card_user');
        $this->addSql('DROP TABLE card_sightings');
        $this->addSql('DROP TABLE family');
        $this->addSql('DROP TABLE genus');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE species');
        $this->addSql('DROP TABLE tax_class');
        $this->addSql('DROP TABLE taxon_species');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
