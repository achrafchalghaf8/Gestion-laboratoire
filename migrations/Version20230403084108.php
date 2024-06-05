<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403084108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, demandeur_id INT DEFAULT NULL, materiel_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, num_tel BIGINT NOT NULL, INDEX IDX_42C8495595A6EE59 (demandeur_id), INDEX IDX_42C8495516880AAF (materiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495595A6EE59 FOREIGN KEY (demandeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495516880AAF FOREIGN KEY (materiel_id) REFERENCES materiel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495595A6EE59');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495516880AAF');
        $this->addSql('DROP TABLE reservation');
    }
}
