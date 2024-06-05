<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516211052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5BC18272');
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5BD36DAE95');
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5B1A65C546');
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5BF0950B34');
        $this->addSql('DROP TABLE projets_chercheurs');
        $this->addSql('DROP TABLE role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projets_chercheurs (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, chercheur_id INT NOT NULL, no_id INT DEFAULT NULL, nope_id INT DEFAULT NULL, INDEX IDX_D5B27E5BD36DAE95 (nope_id), INDEX IDX_D5B27E5BC18272 (projet_id), INDEX IDX_D5B27E5BF0950B34 (chercheur_id), INDEX IDX_D5B27E5B1A65C546 (no_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5BC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5BD36DAE95 FOREIGN KEY (nope_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5B1A65C546 FOREIGN KEY (no_id) REFERENCES chercheur (id)');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5BF0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id)');
    }
}
