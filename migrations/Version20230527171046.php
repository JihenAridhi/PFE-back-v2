<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527171046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE person_article (person_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_20A4BDEF217BBB47 (person_id), INDEX IDX_20A4BDEF7294869C (article_id), PRIMARY KEY(person_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE person_article ADD CONSTRAINT FK_20A4BDEF217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_article ADD CONSTRAINT FK_20A4BDEF7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person_article DROP FOREIGN KEY FK_20A4BDEF217BBB47');
        $this->addSql('ALTER TABLE person_article DROP FOREIGN KEY FK_20A4BDEF7294869C');
        $this->addSql('DROP TABLE person_article');
    }
}
