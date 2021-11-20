<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120200539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comptable CHANGE id id CHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE FicheFrais CHANGE idEtat idEtat CHAR(2) NOT NULL, CHANGE idVisiteur idVisiteur CHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisForfait CHANGE fiche_frais_id fiche_frais_id INT NOT NULL, CHANGE frais_forfait_id frais_forfait_id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait CHANGE idStatut idStatut CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE Statut CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comptable CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FicheFrais CHANGE idEtat idEtat CHAR(2) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE idVisiteur idVisiteur CHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE LigneFraisForfait CHANGE fiche_frais_id fiche_frais_id INT DEFAULT NULL, CHANGE frais_forfait_id frais_forfait_id CHAR(3) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait CHANGE idStatut idStatut CHAR(3) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Statut CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
