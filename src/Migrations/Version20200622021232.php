<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200622021232 extends AbstractMigration
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
        $this->addSql('ALTER TABLE product CHANGE wish_list_id wish_list_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE distribuitor distribuitor VARCHAR(255) DEFAULT NULL, CHANGE genero genero JSON NOT NULL, CHANGE verified verified TINYINT(1) DEFAULT NULL, CHANGE images images JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sale CHANGE ticket_id ticket_id INT DEFAULT NULL, CHANGE seller_id seller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE se_busca CHANGE publisher_id publisher_id INT DEFAULT NULL, CHANGE images images JSON NOT NULL');
        $this->addSql('ALTER TABLE shipping_address CHANGE personalization_id personalization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD seller_id INT DEFAULT NULL, ADD buyer_id INT DEFAULT NULL, CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA36C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA38DE820D9 ON ticket (seller_id)');
        $this->addSql('CREATE INDEX IDX_97A0ADA36C755722 ON ticket (buyer_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE last_login last_login DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE verifications CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wish_list CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE personalization CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product CHANGE wish_list_id wish_list_id INT DEFAULT NULL, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE distribuitor distribuitor VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE genero genero LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE verified verified TINYINT(1) DEFAULT \'NULL\', CHANGE images images LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sale CHANGE seller_id seller_id INT DEFAULT NULL, CHANGE ticket_id ticket_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE se_busca CHANGE publisher_id publisher_id INT DEFAULT NULL, CHANGE images images LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE shipping_address CHANGE personalization_id personalization_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA38DE820D9');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA36C755722');
        $this->addSql('DROP INDEX IDX_97A0ADA38DE820D9 ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA36C755722 ON ticket');
        $this->addSql('ALTER TABLE ticket DROP seller_id, DROP buyer_id, CHANGE status status VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE last_login last_login DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE verifications CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wish_list CHANGE user_id user_id INT DEFAULT NULL');
    }
}
