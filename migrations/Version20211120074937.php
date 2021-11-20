<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120074937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comptable CHANGE id id CHAR(4) NOT NULL');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) NOT NULL, CHANGE libelle libelle VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE FicheFrais CHANGE mois mois DATE NOT NULL, CHANGE dateModif dateModif DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) NOT NULL, CHANGE libelle libelle CHAR(20) NOT NULL, CHANGE montant montant NUMERIC(5, 2) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait CHANGE mois mois DATE NOT NULL, CHANGE libelle libelle VARCHAR(100) DEFAULT \'NULL\' NOT NULL, CHANGE date date DATE NOT NULL, CHANGE montant montant NUMERIC(10, 2) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE Statut CHANGE id id CHAR(3) NOT NULL, CHANGE libelle libelle VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comptable CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE libelle libelle VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FicheFrais CHANGE mois mois DATE DEFAULT \'0000-00-00\' NOT NULL, CHANGE dateModif dateModif DATE DEFAULT \'0000-00-00\'');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE libelle libelle CHAR(20) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE montant montant NUMERIC(5, 2) DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait CHANGE mois mois DATE DEFAULT \'0000-00-00\' NOT NULL, CHANGE libelle libelle VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date date DATE DEFAULT \'0000-00-00\', CHANGE montant montant NUMERIC(10, 2) DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE Statut CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE libelle libelle VARCHAR(30) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
