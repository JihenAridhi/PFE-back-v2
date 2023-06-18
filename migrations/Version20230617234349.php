<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617234349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_partners (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, partner_id INT DEFAULT NULL, INDEX IDX_D2A5DABF166D1F9C (project_id), INDEX IDX_D2A5DABF9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_partners ADD CONSTRAINT FK_D2A5DABF166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_partners ADD CONSTRAINT FK_D2A5DABF9393F8FE FOREIGN KEY (partner_id) REFERENCES partners (id)');
        $this->addSql('ALTER TABLE partners ADD co_partner TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE project ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_partners DROP FOREIGN KEY FK_D2A5DABF166D1F9C');
        $this->addSql('ALTER TABLE project_partners DROP FOREIGN KEY FK_D2A5DABF9393F8FE');
        $this->addSql('DROP TABLE project_partners');
        $this->addSql('ALTER TABLE partners DROP co_partner');
        $this->addSql('ALTER TABLE project DROP type');
    }
}
