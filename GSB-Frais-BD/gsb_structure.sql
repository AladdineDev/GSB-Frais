drop database if exists gsbFrais;
create database gsbFrais;
use gsbFrais;
-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 04 Juillet 2011 à 14:08
-- Version du serveur: 5.5.8
-- Version de PHP: 5.3.5
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8 */
;
--
-- Base de données: `gsbFrais`
--
-- --------------------------------------------------------
--
-- Structure de la table `FraisForfait`
--
CREATE TABLE IF NOT EXISTS `FraisForfait` (
  `id` char(3) NOT NULL,
  `libelle` char(20) DEFAULT NULL,
  `montant` decimal(5, 2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;
-- --------------------------------------------------------
--
-- Structure de la table `Etat`
--
CREATE TABLE IF NOT EXISTS `Etat` (
  `id` char(2) NOT NULL,
  `libelle` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;
-- --------------------------------------------------------
--
-- Structure de la table `Visiteur`
--
CREATE TABLE IF NOT EXISTS `Visiteur` (
  `id` char(4) NOT NULL,
  `nom` char(30) DEFAULT NULL,
  `prenom` char(30) DEFAULT NULL,
  `login` char(20) DEFAULT NULL,
  `mdp` char(20) DEFAULT NULL,
  `adresse` char(30) DEFAULT NULL,
  `cp` char(5) DEFAULT NULL,
  `ville` char(30) DEFAULT NULL,
  `dateEmbauche` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;
-- --------------------------------------------------------
--
-- Structure de la table `FicheFrais`
--
CREATE TABLE IF NOT EXISTS `FicheFrais` (
  `idVisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `nbJustificatifs` int(11) DEFAULT NULL,
  `montantValide` decimal(10, 2) DEFAULT NULL,
  `dateModif` date DEFAULT NULL,
  `idEtat` char(2) DEFAULT 'CR',
  PRIMARY KEY (`idVisiteur`, `mois`),
  FOREIGN KEY (`idEtat`) REFERENCES Etat(`id`),
  FOREIGN KEY (`idVisiteur`) REFERENCES Visiteur(`id`)
) ENGINE = InnoDB;
-- --------------------------------------------------------
--
-- Structure de la table `LigneFraisForfait`
--
CREATE TABLE IF NOT EXISTS `LigneFraisForfait` (
  `idVisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `idFraisForfait` char(3) NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  PRIMARY KEY (`idVisiteur`, `mois`, `idFraisForfait`),
  FOREIGN KEY (`idVisiteur`, `mois`) REFERENCES FicheFrais(`idVisiteur`, `mois`),
  FOREIGN KEY (`idFraisForfait`) REFERENCES FraisForfait(`id`)
) ENGINE = InnoDB;
-- --------------------------------------------------------
--
-- Structure de la table `LigneFraisHorsForfait`
--
CREATE TABLE IF NOT EXISTS `LigneFraisHorsForfait` (
  `id` int(11) NOT NULL auto_increment,
  `idVisiteur` char(4) NOT NULL,
  `mois` char(6) NOT NULL,
  `libelle` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `montant` decimal(10, 2) DEFAULT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (`idVisiteur`, `mois`) REFERENCES FicheFrais(`idVisiteur`, `mois`)
) ENGINE = InnoDB;
-- --------------------------------------------------------
--
-- Structure de la table `Comptable`
--
-- CREATE TABLE IF NOT EXISTS `Comptable` (
--   `id` char(4) NOT NULL,
--   `nom` char(30) DEFAULT NULL,
--   `prenom` char(30) DEFAULT NULL,
--   `login` char(20) DEFAULT NULL,
--   `mdp` char(20) DEFAULT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE = InnoDB;