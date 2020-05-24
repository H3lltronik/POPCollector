<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200524083424 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD format_id INT NOT NULL, ADD edition_id INT NOT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL, CHANGE distribuitor distribuitor VARCHAR(255) DEFAULT NULL, CHANGE genero genero JSON NOT NULL, CHANGE images images JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD629F605 FOREIGN KEY (format_id) REFERENCES product_format (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD74281A5E FOREIGN KEY (edition_id) REFERENCES product_edition (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADD629F605 ON product (format_id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD74281A5E ON product (edition_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD629F605');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD74281A5E');
        $this->addSql('DROP INDEX IDX_D34A04ADD629F605 ON product');
        $this->addSql('DROP INDEX IDX_D34A04AD74281A5E ON product');
        $this->addSql('ALTER TABLE product DROP format_id, DROP edition_id, CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE distribuitor distribuitor VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE genero genero LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE images images LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
