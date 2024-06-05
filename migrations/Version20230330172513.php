<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330172513 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chercheur DROP FOREIGN KEY FK_9DD29B5067B3B43D');
        $this->addSql('DROP INDEX IDX_9DD29B5067B3B43D ON chercheur');
        $this->addSql('ALTER TABLE chercheur CHANGE users_id nom_prenom_id INT DEFAULT NULL, CHANGE nom_prenom nomprenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chercheur ADD CONSTRAINT FK_9DD29B50CC70EEDF FOREIGN KEY (nom_prenom_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9DD29B50CC70EEDF ON chercheur (nom_prenom_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chercheur DROP FOREIGN KEY FK_9DD29B50CC70EEDF');
        $this->addSql('DROP INDEX IDX_9DD29B50CC70EEDF ON chercheur');
        $this->addSql('ALTER TABLE chercheur CHANGE nom_prenom_id users_id INT DEFAULT NULL, CHANGE nomprenom nom_prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE chercheur ADD CONSTRAINT FK_9DD29B5067B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9DD29B5067B3B43D ON chercheur (users_id)');
    }
}
