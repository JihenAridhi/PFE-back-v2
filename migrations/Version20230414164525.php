<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230414164525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP photo');
        $this->addSql('ALTER TABLE feedback DROP date');
        $this->addSql('ALTER TABLE news DROP photo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD photo LONGBLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE feedback ADD date DATE NOT NULL');
        $this->addSql('ALTER TABLE news ADD photo LONGBLOB DEFAULT NULL');
    }
}
