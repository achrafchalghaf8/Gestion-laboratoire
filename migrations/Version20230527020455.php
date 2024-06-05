<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527020455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chercheur ADD compte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chercheur ADD CONSTRAINT FK_9DD29B50F2C56620 FOREIGN KEY (compte_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9DD29B50F2C56620 ON chercheur (compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chercheur DROP FOREIGN KEY FK_9DD29B50F2C56620');
        $this->addSql('DROP INDEX IDX_9DD29B50F2C56620 ON chercheur');
        $this->addSql('ALTER TABLE chercheur DROP compte_id');
    }
}
