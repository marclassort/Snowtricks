<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214125412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E89BD1736');
        $this->addSql('DROP INDEX UNIQ_D8F0A91E89BD1736 ON trick');
        $this->addSql('ALTER TABLE trick ADD firstImage INT DEFAULT NULL, DROP first_image_id');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EAE2BE474 FOREIGN KEY (firstImage) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91EAE2BE474 ON trick (firstImage)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EAE2BE474');
        $this->addSql('DROP INDEX UNIQ_D8F0A91EAE2BE474 ON trick');
        $this->addSql('ALTER TABLE trick ADD first_image_id INT NOT NULL, DROP firstImage');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E89BD1736 FOREIGN KEY (first_image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91E89BD1736 ON trick (first_image_id)');
    }
}
