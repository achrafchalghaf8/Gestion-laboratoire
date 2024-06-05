<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405073612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projets_chercheurs ADD no_id INT DEFAULT NULL, ADD nope_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5B1A65C546 FOREIGN KEY (no_id) REFERENCES chercheur (id)');
        $this->addSql('ALTER TABLE projets_chercheurs ADD CONSTRAINT FK_D5B27E5BD36DAE95 FOREIGN KEY (nope_id) REFERENCES projet (id)');
        $this->addSql('CREATE INDEX IDX_D5B27E5B1A65C546 ON projets_chercheurs (no_id)');
        $this->addSql('CREATE INDEX IDX_D5B27E5BD36DAE95 ON projets_chercheurs (nope_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5B1A65C546');
        $this->addSql('ALTER TABLE projets_chercheurs DROP FOREIGN KEY FK_D5B27E5BD36DAE95');
        $this->addSql('DROP INDEX IDX_D5B27E5B1A65C546 ON projets_chercheurs');
        $this->addSql('DROP INDEX IDX_D5B27E5BD36DAE95 ON projets_chercheurs');
        $this->addSql('ALTER TABLE projets_chercheurs DROP no_id, DROP nope_id');
    }
}
