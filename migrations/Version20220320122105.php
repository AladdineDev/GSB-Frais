<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220320122105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE FicheFrais CHANGE montantValide montantValide NUMERIC(10, 2) DEFAULT \'0\'');
        $this->addSql('ALTER TABLE FraisForfait CHANGE montant montant NUMERIC(5, 2) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait DROP idVisiteur, DROP mois, CHANGE montant montant NUMERIC(10, 2) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE FicheFrais CHANGE montantValide montantValide NUMERIC(10, 2) DEFAULT \'0.00\'');
        $this->addSql('ALTER TABLE FraisForfait CHANGE montant montant NUMERIC(5, 2) DEFAULT \'0.00\' NOT NULL');
        $this->addSql('ALTER TABLE LigneFraisHorsForfait ADD idVisiteur CHAR(4) NOT NULL, ADD mois DATE NOT NULL, CHANGE montant montant NUMERIC(10, 2) DEFAULT \'0.00\' NOT NULL');
    }
}
