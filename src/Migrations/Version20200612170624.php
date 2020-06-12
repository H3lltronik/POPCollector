<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200612170624 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE personalization (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name LONGTEXT NOT NULL, father_last_name LONGTEXT NOT NULL, mother_last_name LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_6B2251D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_type_id INT NOT NULL, format_id INT NOT NULL, edition_id INT NOT NULL, wish_list_id INT DEFAULT NULL, publisher_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, distribuitor VARCHAR(255) DEFAULT NULL, stock INT NOT NULL, price DOUBLE PRECISION NOT NULL, author VARCHAR(255) NOT NULL, year VARCHAR(255) NOT NULL, genero JSON NOT NULL, verified TINYINT(1) DEFAULT NULL, verification_comments LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, images JSON DEFAULT NULL, is_visible TINYINT(1) DEFAULT \'0\' NOT NULL, clicks INT DEFAULT 0, INDEX IDX_D34A04AD14959723 (product_type_id), INDEX IDX_D34A04ADD629F605 (format_id), INDEX IDX_D34A04AD74281A5E (edition_id), INDEX IDX_D34A04ADD69F3311 (wish_list_id), INDEX IDX_D34A04AD40C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edition (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edition_product_type (product_edition_id INT NOT NULL, product_type_id INT NOT NULL, INDEX IDX_7E4028FEE74F4575 (product_edition_id), INDEX IDX_7E4028FE14959723 (product_type_id), PRIMARY KEY(product_edition_id, product_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_format (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_format_product_type (product_format_id INT NOT NULL, product_type_id INT NOT NULL, INDEX IDX_B831BD76B8894088 (product_format_id), INDEX IDX_B831BD7614959723 (product_type_id), PRIMARY KEY(product_format_id, product_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, ticket_id INT NOT NULL, seller_id INT DEFAULT NULL, created_at DATETIME NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_E54BC0054584665A (product_id), INDEX IDX_E54BC005700047D2 (ticket_id), INDEX IDX_E54BC0058DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE se_busca (id INT AUTO_INCREMENT NOT NULL, publisher_id INT DEFAULT NULL, created_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, images JSON NOT NULL, is_active TINYINT(1) NOT NULL, is_visible TINYINT(1) NOT NULL, INDEX IDX_FE6A5B5A40C86FCE (publisher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE se_busca_product (se_busca_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_1C5D7593F3426271 (se_busca_id), INDEX IDX_1C5D75934584665A (product_id), PRIMARY KEY(se_busca_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping_address (id INT AUTO_INCREMENT NOT NULL, state_id INT NOT NULL, personalization_id INT DEFAULT NULL, postal_code INT NOT NULL, address VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, phone VARCHAR(255) NOT NULL, INDEX IDX_EB0669455D83CC1 (state_id), UNIQUE INDEX UNIQ_EB0669459BC31F71 (personalization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, seller_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_97A0ADA3A76ED395 (user_id), INDEX IDX_97A0ADA38DE820D9 (seller_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, last_login DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user (user_source INT NOT NULL, user_target INT NOT NULL, INDEX IDX_F7129A803AD8644E (user_source), INDEX IDX_F7129A80233D34C1 (user_target), PRIMARY KEY(user_source, user_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE verifications (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8C86E7464584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wish_list (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_5B8739BDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wish_list_product (wish_list_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_9B7C1C9DD69F3311 (wish_list_id), INDEX IDX_9B7C1C9D4584665A (product_id), PRIMARY KEY(wish_list_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE personalization ADD CONSTRAINT FK_6B2251D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD629F605 FOREIGN KEY (format_id) REFERENCES product_format (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD74281A5E FOREIGN KEY (edition_id) REFERENCES product_edition (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD40C86FCE FOREIGN KEY (publisher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_edition_product_type ADD CONSTRAINT FK_7E4028FEE74F4575 FOREIGN KEY (product_edition_id) REFERENCES product_edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_edition_product_type ADD CONSTRAINT FK_7E4028FE14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_format_product_type ADD CONSTRAINT FK_B831BD76B8894088 FOREIGN KEY (product_format_id) REFERENCES product_format (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_format_product_type ADD CONSTRAINT FK_B831BD7614959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0054584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC005700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_E54BC0058DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE se_busca ADD CONSTRAINT FK_FE6A5B5A40C86FCE FOREIGN KEY (publisher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE se_busca_product ADD CONSTRAINT FK_1C5D7593F3426271 FOREIGN KEY (se_busca_id) REFERENCES se_busca (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE se_busca_product ADD CONSTRAINT FK_1C5D75934584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shipping_address ADD CONSTRAINT FK_EB0669455D83CC1 FOREIGN KEY (state_id) REFERENCES state (id)');
        $this->addSql('ALTER TABLE shipping_address ADD CONSTRAINT FK_EB0669459BC31F71 FOREIGN KEY (personalization_id) REFERENCES personalization (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38DE820D9 FOREIGN KEY (seller_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A803AD8644E FOREIGN KEY (user_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user ADD CONSTRAINT FK_F7129A80233D34C1 FOREIGN KEY (user_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE verifications ADD CONSTRAINT FK_8C86E7464584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wish_list_product ADD CONSTRAINT FK_9B7C1C9DD69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wish_list_product ADD CONSTRAINT FK_9B7C1C9D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shipping_address DROP FOREIGN KEY FK_EB0669459BC31F71');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC0054584665A');
        $this->addSql('ALTER TABLE se_busca_product DROP FOREIGN KEY FK_1C5D75934584665A');
        $this->addSql('ALTER TABLE verifications DROP FOREIGN KEY FK_8C86E7464584665A');
        $this->addSql('ALTER TABLE wish_list_product DROP FOREIGN KEY FK_9B7C1C9D4584665A');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD74281A5E');
        $this->addSql('ALTER TABLE product_edition_product_type DROP FOREIGN KEY FK_7E4028FEE74F4575');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD629F605');
        $this->addSql('ALTER TABLE product_format_product_type DROP FOREIGN KEY FK_B831BD76B8894088');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD14959723');
        $this->addSql('ALTER TABLE product_edition_product_type DROP FOREIGN KEY FK_7E4028FE14959723');
        $this->addSql('ALTER TABLE product_format_product_type DROP FOREIGN KEY FK_B831BD7614959723');
        $this->addSql('ALTER TABLE se_busca_product DROP FOREIGN KEY FK_1C5D7593F3426271');
        $this->addSql('ALTER TABLE shipping_address DROP FOREIGN KEY FK_EB0669455D83CC1');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005700047D2');
        $this->addSql('ALTER TABLE personalization DROP FOREIGN KEY FK_6B2251D5A76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD40C86FCE');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC0058DE820D9');
        $this->addSql('ALTER TABLE se_busca DROP FOREIGN KEY FK_FE6A5B5A40C86FCE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A76ED395');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA38DE820D9');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A803AD8644E');
        $this->addSql('ALTER TABLE user_user DROP FOREIGN KEY FK_F7129A80233D34C1');
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BDA76ED395');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD69F3311');
        $this->addSql('ALTER TABLE wish_list_product DROP FOREIGN KEY FK_9B7C1C9DD69F3311');
        $this->addSql('DROP TABLE personalization');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_edition');
        $this->addSql('DROP TABLE product_edition_product_type');
        $this->addSql('DROP TABLE product_format');
        $this->addSql('DROP TABLE product_format_product_type');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE se_busca');
        $this->addSql('DROP TABLE se_busca_product');
        $this->addSql('DROP TABLE shipping_address');
        $this->addSql('DROP TABLE state');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user');
        $this->addSql('DROP TABLE verifications');
        $this->addSql('DROP TABLE wish_list');
        $this->addSql('DROP TABLE wish_list_product');
    }
}
