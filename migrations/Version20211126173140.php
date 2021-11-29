<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211126173140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP first_name, DROP last_name, DROP is_valid, CHANGE trick_id trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE media DROP INDEX UNIQ_6A2CA10CB281BE2E, ADD INDEX IDX_6A2CA10CB281BE2E (trick_id)');
        $this->addSql('ALTER TABLE user CHANGE role role LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD is_valid TINYINT(1) NOT NULL, CHANGE trick_id trick_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media DROP INDEX IDX_6A2CA10CB281BE2E, ADD UNIQUE INDEX UNIQ_6A2CA10CB281BE2E (trick_id)');
        $this->addSql('ALTER TABLE user CHANGE role role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
