<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230401205320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet_chercheur (projet_id INT NOT NULL, chercheur_id INT NOT NULL, INDEX IDX_BE2C7C84C18272 (projet_id), INDEX IDX_BE2C7C84F0950B34 (chercheur_id), PRIMARY KEY(projet_id, chercheur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet_chercheur ADD CONSTRAINT FK_BE2C7C84C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_chercheur ADD CONSTRAINT FK_BE2C7C84F0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projet_chercheur DROP FOREIGN KEY FK_BE2C7C84C18272');
        $this->addSql('ALTER TABLE projet_chercheur DROP FOREIGN KEY FK_BE2C7C84F0950B34');
        $this->addSql('DROP TABLE projet_chercheur');
    }
}
