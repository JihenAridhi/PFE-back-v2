<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329220936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD first_page INT NOT NULL, ADD last_page INT NOT NULL, DROP firstpage, DROP lastpage');
        $this->addSql('ALTER TABLE partners CHANGE urlpage url_page VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE person ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, DROP firstname, DROP lastname');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD firstpage INT NOT NULL, ADD lastpage INT NOT NULL, DROP first_page, DROP last_page');
        $this->addSql('ALTER TABLE partners CHANGE url_page urlpage VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE person ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, DROP first_name, DROP last_name');
    }
}
