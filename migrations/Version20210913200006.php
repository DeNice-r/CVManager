<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210913200006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv ADD positive_reviews INT DEFAULT NULL, ADD negative_reviews INT DEFAULT NULL, ADD position VARCHAR(255) NOT NULL, ADD edited_on DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD active_cvs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', ADD additonal_info LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv DROP positive_reviews, DROP negative_reviews, DROP position, DROP edited_on');
        $this->addSql('ALTER TABLE user DROP active_cvs, DROP additonal_info');
    }
}
