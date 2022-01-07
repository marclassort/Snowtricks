<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211225185609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image ADD trick_unique_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB87DFEDA FOREIGN KEY (trick_unique_id) REFERENCES trick (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C53D045FB87DFEDA ON image (trick_unique_id)');
        $this->addSql('ALTER TABLE trick ADD first_image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E89BD1736 FOREIGN KEY (first_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91E89BD1736 ON trick (first_image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB87DFEDA');
        $this->addSql('DROP INDEX UNIQ_C53D045FB87DFEDA ON image');
        $this->addSql('ALTER TABLE image DROP trick_unique_id');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E89BD1736');
        $this->addSql('DROP INDEX UNIQ_D8F0A91E89BD1736 ON trick');
        $this->addSql('ALTER TABLE trick DROP first_image_id');
    }
}
