<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211011181628 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Comptable (id CHAR(4) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, login VARCHAR(20) NOT NULL, mdp VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Etat (id CHAR(2) NOT NULL, libelle VARCHAR(30) DEFAULT \'NULL\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FicheFrais (id INT AUTO_INCREMENT NOT NULL, mois CHAR(6) NOT NULL, nbJustificatifs INT DEFAULT 0, montantValide NUMERIC(10, 2) DEFAULT \'0\', dateModif DATE DEFAULT \'0000-00-00\', idEtat CHAR(2) DEFAULT NULL, idVisiteur CHAR(4) DEFAULT NULL, INDEX idEtat (idEtat), INDEX idVisiteur (idVisiteur), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE FraisForfait (id CHAR(3) NOT NULL, libelle CHAR(20) DEFAULT \'NULL\', montant NUMERIC(5, 2) DEFAULT \'0\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE LigneFraisForfait (id INT AUTO_INCREMENT NOT NULL, fiche_frais_id INT DEFAULT NULL, frais_forfait_id CHAR(3) DEFAULT NULL, quantite INT DEFAULT NULL, INDEX IDX_146093DCD94F5755 (fiche_frais_id), INDEX IDX_146093DC7B70375E (frais_forfait_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE LigneFraisHorsForfait (id INT AUTO_INCREMENT NOT NULL, idVisiteur CHAR(4) NOT NULL, mois CHAR(6) NOT NULL, libelle VARCHAR(100) DEFAULT \'NULL\', date DATE DEFAULT \'0000-00-00\', montant NUMERIC(10, 2) DEFAULT \'0\', idFicheFrais INT DEFAULT NULL, INDEX idFicheFrais (idFicheFrais), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Visiteur (id CHAR(4) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, login VARCHAR(20) NOT NULL, mdp VARCHAR(20) NOT NULL, adresse VARCHAR(30) NOT NULL, cp VARCHAR(5) NOT NULL, ville VARCHAR(30) NOT NULL, dateEmbauche DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE FicheFrais ADD CONSTRAINT FK_1C4987DC2637A9FC FOREIGN KEY (idEtat) REFERENCES Etat (id)');
        $this->addSql('ALTER TABLE FicheFrais ADD CONSTRAINT FK_1C4987DC1D06ADE3 FOREIGN KEY (idVisiteur) REFERENCES Visiteur (id)');
        $this->addSql('ALTER TABLE LigneFraisForfait ADD CONSTRAINT FK_146093DCD94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES FicheFrais (id)');
        $this->addSql('ALTER TABLE LigneFraisForfait ADD CONSTRAINT FK_146093DC7B70375E FOREIGN KEY (frais_forfait_id) REFERENCES FraisForfait (id)');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait ADD CONSTRAINT FK_4C9509ABD1E09AE6 FOREIGN KEY (idFicheFrais) REFERENCES FicheFrais (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE FicheFrais DROP FOREIGN KEY FK_1C4987DC2637A9FC');
        $this->addSql('ALTER TABLE LigneFraisForfait DROP FOREIGN KEY FK_146093DCD94F5755');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP FOREIGN KEY FK_4C9509ABD1E09AE6');
        $this->addSql('ALTER TABLE LigneFraisForfait DROP FOREIGN KEY FK_146093DC7B70375E');
        $this->addSql('ALTER TABLE FicheFrais DROP FOREIGN KEY FK_1C4987DC1D06ADE3');
        $this->addSql('DROP TABLE Comptable');
        $this->addSql('DROP TABLE Etat');
        $this->addSql('DROP TABLE FicheFrais');
        $this->addSql('DROP TABLE FraisForfait');
        $this->addSql('DROP TABLE LigneFraisForfait');
        $this->addSql('DROP TABLE LigneFraisHorsForfait');
        $this->addSql('DROP TABLE Visiteur');
    }
}
