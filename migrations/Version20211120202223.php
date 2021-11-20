<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211120202223 extends AbstractMigration
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
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisForfait DROP FOREIGN KEY FK_146093DC7B70375E');
        $this->addSql('ALTER TABLE LigneFraisForfait DROP FOREIGN KEY FK_146093DCD94F5755');
        $this->addSql('DROP INDEX IDX_146093DCD94F5755 ON LigneFraisForfait');
        $this->addSql('DROP INDEX IDX_146093DC7B70375E ON LigneFraisForfait');
        $this->addSql('ALTER TABLE LigneFraisForfait CHANGE fiche_frais_id idFicheFrais INT NOT NULL, CHANGE frais_forfait_id idFraisForfait CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisForfait ADD CONSTRAINT FK_146093DCD1E09AE6 FOREIGN KEY (idFicheFrais) REFERENCES FicheFrais (id)');
        $this->addSql('ALTER TABLE LigneFraisForfait ADD CONSTRAINT FK_146093DC4134ACE6 FOREIGN KEY (idFraisForfait) REFERENCES FraisForfait (id)');
        $this->addSql('CREATE INDEX IDX_146093DCD1E09AE6 ON LigneFraisForfait (idFicheFrais)');
        $this->addSql('CREATE INDEX IDX_146093DC4134ACE6 ON LigneFraisForfait (idFraisForfait)');
        $this->addSql('ALTER TABLE Statut CHANGE id id CHAR(3) NOT NULL');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Comptable CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Etat CHANGE id id CHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE FraisForfait CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE LigneFraisForfait DROP FOREIGN KEY FK_146093DCD1E09AE6');
        $this->addSql('ALTER TABLE LigneFraisForfait DROP FOREIGN KEY FK_146093DC4134ACE6');
        $this->addSql('DROP INDEX IDX_146093DCD1E09AE6 ON LigneFraisForfait');
        $this->addSql('DROP INDEX IDX_146093DC4134ACE6 ON LigneFraisForfait');
        $this->addSql('ALTER TABLE LigneFraisForfait CHANGE idfichefrais fiche_frais_id INT NOT NULL, CHANGE idfraisforfait frais_forfait_id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE LigneFraisForfait ADD CONSTRAINT FK_146093DC7B70375E FOREIGN KEY (frais_forfait_id) REFERENCES FraisForfait (id)');
        $this->addSql('ALTER TABLE LigneFraisForfait ADD CONSTRAINT FK_146093DCD94F5755 FOREIGN KEY (fiche_frais_id) REFERENCES FicheFrais (id)');
        $this->addSql('CREATE INDEX IDX_146093DCD94F5755 ON LigneFraisForfait (fiche_frais_id)');
        $this->addSql('CREATE INDEX IDX_146093DC7B70375E ON LigneFraisForfait (frais_forfait_id)');
        $this->addSql('ALTER TABLE Statut CHANGE id id CHAR(3) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE Visiteur CHANGE id id CHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
