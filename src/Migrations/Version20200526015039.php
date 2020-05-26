<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526015039 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE personalization CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE wish_list_id wish_list_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE distribuitor distribuitor VARCHAR(255) DEFAULT NULL, CHANGE genero genero JSON NOT NULL, CHANGE images images JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE se_busca CHANGE publisher_id publisher_id INT DEFAULT NULL, CHANGE images images JSON NOT NULL');
        $this->addSql('ALTER TABLE shipping_address ADD personalization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping_address ADD CONSTRAINT FK_EB0669459BC31F71 FOREIGN KEY (personalization_id) REFERENCES personalization (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EB0669459BC31F71 ON shipping_address (personalization_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE wish_list CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE personalization CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE wish_list_id wish_list_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE distribuitor distribuitor VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE genero genero LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE images images LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE se_busca CHANGE publisher_id publisher_id INT DEFAULT NULL, CHANGE images images LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE shipping_address DROP FOREIGN KEY FK_EB0669459BC31F71');
        $this->addSql('DROP INDEX UNIQ_EB0669459BC31F71 ON shipping_address');
        $this->addSql('ALTER TABLE shipping_address DROP personalization_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE wish_list CHANGE user_id user_id INT DEFAULT NULL');
    }
}
