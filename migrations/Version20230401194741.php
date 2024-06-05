<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230401194741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chercheur_projet (chercheur_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_414D615DF0950B34 (chercheur_id), INDEX IDX_414D615DC18272 (projet_id), PRIMARY KEY(chercheur_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chercheur_projet ADD CONSTRAINT FK_414D615DF0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chercheur_projet ADD CONSTRAINT FK_414D615DC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_chercheur DROP FOREIGN KEY FK_BE2C7C84C18272');
        $this->addSql('ALTER TABLE projet_chercheur DROP FOREIGN KEY FK_BE2C7C84F0950B34');
        $this->addSql('DROP TABLE projet_chercheur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE projet_chercheur (projet_id INT NOT NULL, chercheur_id INT NOT NULL, INDEX IDX_BE2C7C84F0950B34 (chercheur_id), INDEX IDX_BE2C7C84C18272 (projet_id), PRIMARY KEY(projet_id, chercheur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE projet_chercheur ADD CONSTRAINT FK_BE2C7C84C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_chercheur ADD CONSTRAINT FK_BE2C7C84F0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chercheur_projet DROP FOREIGN KEY FK_414D615DF0950B34');
        $this->addSql('ALTER TABLE chercheur_projet DROP FOREIGN KEY FK_414D615DC18272');
        $this->addSql('DROP TABLE chercheur_projet');
    }
}
