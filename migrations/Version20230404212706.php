<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230404212706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projets_chercheurs (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, chercheur_id INT NOT NULL, INDEX IDX_D5B27E5BC18272 (projet_id), INDEX IDX_D5B27E5BF0950B34 (chercheur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5BC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5BF0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5BC18272');
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5BF0950B34');
        $this->addSql('DROP TABLE projets_chercheurs');
    }
}
