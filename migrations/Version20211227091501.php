<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227091501 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB87DFEDA');
        $this->addSql('DROP INDEX UNIQ_C53D045FB87DFEDA ON image');
        $this->addSql('ALTER TABLE image ADD is_thumbnail TINYINT(1) NOT NULL, DROP trick_unique_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD trick_unique_id INT DEFAULT NULL, DROP is_thumbnail');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB87DFEDA FOREIGN KEY (trick_unique_id) REFERENCES trick (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045FB87DFEDA ON image (trick_unique_id)');
    }
}
