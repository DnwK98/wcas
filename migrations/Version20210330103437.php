<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210330103437 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domains (id VARCHAR(36) NOT NULL, owner_id VARCHAR(36) DEFAULT NULL, domain TINYTEXT NOT NULL, INDEX IDX_8C7BBF9D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE scheduled_command (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, command VARCHAR(100) NOT NULL, arguments LONGTEXT DEFAULT NULL, cron_expression VARCHAR(100) DEFAULT NULL, last_execution DATETIME NOT NULL, last_return_code INT DEFAULT NULL, log_file VARCHAR(100) DEFAULT NULL, priority INT NOT NULL, execute_immediately TINYINT(1) NOT NULL, disabled TINYINT(1) NOT NULL, locked TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id VARCHAR(36) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE websites (id VARCHAR(36) NOT NULL, owner_id VARCHAR(36) DEFAULT NULL, url TINYTEXT NOT NULL, INDEX IDX_2527D78D7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE websites_pages (id VARCHAR(36) NOT NULL, website_id VARCHAR(36) DEFAULT NULL, path VARCHAR(64) NOT NULL, definition LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_30D4BE2A18F45C82 (website_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domains ADD CONSTRAINT FK_8C7BBF9D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE websites ADD CONSTRAINT FK_2527D78D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE websites_pages ADD CONSTRAINT FK_30D4BE2A18F45C82 FOREIGN KEY (website_id) REFERENCES websites (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domains DROP FOREIGN KEY FK_8C7BBF9D7E3C61F9');
        $this->addSql('ALTER TABLE websites DROP FOREIGN KEY FK_2527D78D7E3C61F9');
        $this->addSql('ALTER TABLE websites_pages DROP FOREIGN KEY FK_30D4BE2A18F45C82');
        $this->addSql('DROP TABLE domains');
        $this->addSql('DROP TABLE scheduled_command');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE websites');
        $this->addSql('DROP TABLE websites_pages');
    }
}
