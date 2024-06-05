<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519124732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_chercheur DROP FOREIGN KEY FK_81919DC7F0950B34');
        $this->addSql('ALTER TABLE publication_chercheur DROP FOREIGN KEY FK_81919DC738B217A7');
        $this->addSql('DROP TABLE publication_chercheur');
        $this->addSql('ALTER TABLE publication ADD autres_auteurs VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication_chercheur (chercheur_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_81919DC7F0950B34 (chercheur_id), INDEX IDX_81919DC738B217A7 (publication_id), PRIMARY KEY(publication_id, chercheur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE publication_chercheur ADD CONSTRAINT FK_81919DC7F0950B34 FOREIGN KEY (chercheur_id) REFERENCES chercheur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_chercheur ADD CONSTRAINT FK_81919DC738B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication DROP autres_auteurs');
    }
}
