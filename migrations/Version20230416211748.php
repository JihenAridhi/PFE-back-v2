<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416211748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD journal VARCHAR(255) NOT NULL, ADD firstpage INT NOT NULL, ADD lastpage INT NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD doi VARCHAR(255) NOT NULL, DROP first_page, DROP last_page');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD first_page INT NOT NULL, ADD last_page INT NOT NULL, DROP journal, DROP firstpage, DROP lastpage, DROP description, DROP doi');
    }
}
