<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230612171447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, year INT NOT NULL, month INT DEFAULT NULL, institute VARCHAR(255) DEFAULT NULL, first_page INT DEFAULT NULL, last_page INT DEFAULT NULL, editor VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, volume VARCHAR(255) DEFAULT NULL, numero VARCHAR(255) DEFAULT NULL, bibtex VARCHAR(255) NOT NULL, location VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_person (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, author_id INT DEFAULT NULL, INDEX IDX_5E1B46F67294869C (article_id), INDEX IDX_5E1B46F6F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autorisation (id INT AUTO_INCREMENT NOT NULL, autorisation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autorisation_person (autorisation_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_A64A156052C5E836 (autorisation_id), INDEX IDX_A64A1560217BBB47 (person_id), PRIMARY KEY(autorisation_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date DATE NOT NULL, location VARCHAR(255) NOT NULL, organiser VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, phone INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, date DATE DEFAULT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partners (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, text VARCHAR(255) NOT NULL, url_page VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profession VARCHAR(255) DEFAULT NULL, team VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, co_author TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, bio VARCHAR(255) DEFAULT NULL, research_gate VARCHAR(255) DEFAULT NULL, orcid VARCHAR(255) DEFAULT NULL, scholar VARCHAR(255) DEFAULT NULL, linkedin VARCHAR(255) DEFAULT NULL, dblp VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_34DCD176E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme_person (theme_id INT NOT NULL, person_id INT NOT NULL, INDEX IDX_E4E4E50A59027487 (theme_id), INDEX IDX_E4E4E50A217BBB47 (person_id), PRIMARY KEY(theme_id, person_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_person ADD CONSTRAINT FK_5E1B46F67294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE article_person ADD CONSTRAINT FK_5E1B46F6F675F31B FOREIGN KEY (author_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE autorisation_person ADD CONSTRAINT FK_A64A156052C5E836 FOREIGN KEY (autorisation_id) REFERENCES autorisation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE autorisation_person ADD CONSTRAINT FK_A64A1560217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_person ADD CONSTRAINT FK_E4E4E50A59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE theme_person ADD CONSTRAINT FK_E4E4E50A217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_person DROP FOREIGN KEY FK_5E1B46F67294869C');
        $this->addSql('ALTER TABLE article_person DROP FOREIGN KEY FK_5E1B46F6F675F31B');
        $this->addSql('ALTER TABLE autorisation_person DROP FOREIGN KEY FK_A64A156052C5E836');
        $this->addSql('ALTER TABLE autorisation_person DROP FOREIGN KEY FK_A64A1560217BBB47');
        $this->addSql('ALTER TABLE theme_person DROP FOREIGN KEY FK_E4E4E50A59027487');
        $this->addSql('ALTER TABLE theme_person DROP FOREIGN KEY FK_E4E4E50A217BBB47');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE article_person');
        $this->addSql('DROP TABLE autorisation');
        $this->addSql('DROP TABLE autorisation_person');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE partners');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE theme_person');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
