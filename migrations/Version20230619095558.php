<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619095558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partners_project (partners_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_5CFFC69EBDE7F1C6 (partners_id), INDEX IDX_5CFFC69E166D1F9C (project_id), PRIMARY KEY(partners_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE partners_project ADD CONSTRAINT FK_5CFFC69EBDE7F1C6 FOREIGN KEY (partners_id) REFERENCES partners (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE partners_project ADD CONSTRAINT FK_5CFFC69E166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partners_project DROP FOREIGN KEY FK_5CFFC69EBDE7F1C6');
        $this->addSql('ALTER TABLE partners_project DROP FOREIGN KEY FK_5CFFC69E166D1F9C');
        $this->addSql('DROP TABLE partners_project');
    }
}
