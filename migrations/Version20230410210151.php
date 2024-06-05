<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410210151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779C635DC9B');
        $this->addSql('DROP INDEX IDX_AF3C6779C635DC9B ON publication');
        $this->addSql('ALTER TABLE publication DROP chercheurs_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication ADD chercheurs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779C635DC9B FOREIGN KEY (chercheurs_id) REFERENCES chercheur (id)');
        $this->addSql('CREATE INDEX IDX_AF3C6779C635DC9B ON publication (chercheurs_id)');
    }
}
