<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210804104243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE websites_pages DROP FOREIGN KEY FK_30D4BE2A18F45C82');
        $this->addSql('ALTER TABLE websites_pages ADD CONSTRAINT FK_30D4BE2A18F45C82 FOREIGN KEY (website_id) REFERENCES websites (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE websites_pages DROP FOREIGN KEY FK_30D4BE2A18F45C82');
        $this->addSql('ALTER TABLE websites_pages ADD CONSTRAINT FK_30D4BE2A18F45C82 FOREIGN KEY (website_id) REFERENCES websites (id)');
    }
}
