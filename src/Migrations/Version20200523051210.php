<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523051210 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, distribuitor VARCHAR(255) DEFAULT NULL, barcode VARCHAR(255) NOT NULL, stock INT NOT NULL, price DOUBLE PRECISION NOT NULL, author VARCHAR(255) NOT NULL, year VARCHAR(255) NOT NULL, genero JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edition (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_edition_product_type (product_edition_id INT NOT NULL, product_type_id INT NOT NULL, INDEX IDX_7E4028FEE74F4575 (product_edition_id), INDEX IDX_7E4028FE14959723 (product_type_id), PRIMARY KEY(product_edition_id, product_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_format (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_format_product_type (product_format_id INT NOT NULL, product_type_id INT NOT NULL, INDEX IDX_B831BD76B8894088 (product_format_id), INDEX IDX_B831BD7614959723 (product_type_id), PRIMARY KEY(product_format_id, product_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_edition_product_type ADD CONSTRAINT FK_7E4028FEE74F4575 FOREIGN KEY (product_edition_id) REFERENCES product_edition (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_edition_product_type ADD CONSTRAINT FK_7E4028FE14959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_format_product_type ADD CONSTRAINT FK_B831BD76B8894088 FOREIGN KEY (product_format_id) REFERENCES product_format (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_format_product_type ADD CONSTRAINT FK_B831BD7614959723 FOREIGN KEY (product_type_id) REFERENCES product_type (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_edition_product_type DROP FOREIGN KEY FK_7E4028FEE74F4575');
        $this->addSql('ALTER TABLE product_format_product_type DROP FOREIGN KEY FK_B831BD76B8894088');
        $this->addSql('ALTER TABLE product_edition_product_type DROP FOREIGN KEY FK_7E4028FE14959723');
        $this->addSql('ALTER TABLE product_format_product_type DROP FOREIGN KEY FK_B831BD7614959723');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_edition');
        $this->addSql('DROP TABLE product_edition_product_type');
        $this->addSql('DROP TABLE product_format');
        $this->addSql('DROP TABLE product_format_product_type');
        $this->addSql('DROP TABLE product_type');
        $this->addSql('DROP TABLE user');
    }
}
