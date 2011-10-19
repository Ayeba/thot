-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mer 19 Octobre 2011 à 18:38
-- Version du serveur: 5.5.9
-- Version de PHP: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `formation`
--
CREATE DATABASE `formation` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `formation`;

-- --------------------------------------------------------

--
-- Structure de la table `belongs_to`
--

CREATE TABLE `belongs_to` (
  `critere_id` int(11) NOT NULL,
  `famille_id` int(11) NOT NULL,
  UNIQUE KEY `critere_id` (`critere_id`,`famille_id`),
  KEY `famille_id` (`famille_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `belongs_to`
--

INSERT INTO `belongs_to` VALUES(1, 1);
INSERT INTO `belongs_to` VALUES(2, 1);
INSERT INTO `belongs_to` VALUES(3, 2);
INSERT INTO `belongs_to` VALUES(4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `critere`
--

CREATE TABLE `critere` (
  `id_critere` int(11) NOT NULL AUTO_INCREMENT,
  `nom_critere` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_critere`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `critere`
--

INSERT INTO `critere` VALUES(1, 'qualité', '');
INSERT INTO `critere` VALUES(2, 'sécurité', '');
INSERT INTO `critere` VALUES(3, 'administration', '');
INSERT INTO `critere` VALUES(4, 'association', '');
INSERT INTO `critere` VALUES(5, 'isa', '');
INSERT INTO `critere` VALUES(6, 'alexis', '');

-- --------------------------------------------------------

--
-- Structure de la table `famille_critere`
--

CREATE TABLE `famille_critere` (
  `id_famille` int(11) NOT NULL AUTO_INCREMENT,
  `nom_famille` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_famille`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `famille_critere`
--

INSERT INTO `famille_critere` VALUES(1, 'domaines', '');
INSERT INTO `famille_critere` VALUES(2, 'structures', '');
INSERT INTO `famille_critere` VALUES(3, 'labels', '');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

CREATE TABLE `formations` (
  `id_formation` int(11) NOT NULL AUTO_INCREMENT,
  `nom_formation` varchar(250) NOT NULL,
  `sous-titre` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `objectifs` text NOT NULL,
  `public` varchar(300) NOT NULL,
  `pre-requis` text NOT NULL,
  `code` varchar(5) NOT NULL,
  `programme` text NOT NULL,
  `duree_jours` int(11) NOT NULL,
  `duree_heures` int(11) NOT NULL,
  `plus` text NOT NULL,
  `tarif_inter` int(11) NOT NULL,
  `tarif_cp` int(11) NOT NULL,
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `formations`
--

INSERT INTO `formations` VALUES(1, 'toto', '', '', '', '', '', '', '', 0, 0, '', 0, 0);
INSERT INTO `formations` VALUES(2, 'titi', '', '', '', '', '', '', '', 0, 0, '', 0, 0);
INSERT INTO `formations` VALUES(3, 'reff', '', '', '', '', '', '', '', 0, 0, '', 0, 0);
INSERT INTO `formations` VALUES(6, 'l''essai', '', '', '', '', '', '', '', 0, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `has_critere`
--

CREATE TABLE `has_critere` (
  `formation_id` int(11) NOT NULL,
  `critere_id` int(11) NOT NULL,
  PRIMARY KEY (`formation_id`,`critere_id`),
  KEY `critere_id` (`critere_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `has_critere`
--

INSERT INTO `has_critere` VALUES(1, 1);
INSERT INTO `has_critere` VALUES(6, 1);
INSERT INTO `has_critere` VALUES(1, 2);
INSERT INTO `has_critere` VALUES(2, 2);
INSERT INTO `has_critere` VALUES(3, 2);
INSERT INTO `has_critere` VALUES(6, 2);
INSERT INTO `has_critere` VALUES(1, 3);
INSERT INTO `has_critere` VALUES(2, 3);
INSERT INTO `has_critere` VALUES(3, 3);
INSERT INTO `has_critere` VALUES(6, 3);
INSERT INTO `has_critere` VALUES(1, 4);
INSERT INTO `has_critere` VALUES(2, 4);
INSERT INTO `has_critere` VALUES(6, 4);
INSERT INTO `has_critere` VALUES(2, 5);
INSERT INTO `has_critere` VALUES(2, 6);

-- --------------------------------------------------------

--
-- Structure de la table `has_image`
--

CREATE TABLE `has_image` (
  `formation_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `principale` int(11) NOT NULL,
  PRIMARY KEY (`formation_id`,`image_id`),
  KEY `image_id` (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `has_image`
--


-- --------------------------------------------------------

--
-- Structure de la table `images`
--

CREATE TABLE `images` (
  `id_image` int(11) NOT NULL AUTO_INCREMENT,
  `nom_image` varchar(100) NOT NULL,
  PRIMARY KEY (`id_image`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `images`
--


-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` VALUES('romain', 'thomas', 'romain@ayeba.fr');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `belongs_to`
--
ALTER TABLE `belongs_to`
  ADD CONSTRAINT `belongs_to_ibfk_1` FOREIGN KEY (`critere_id`) REFERENCES `critere` (`id_critere`) ON DELETE CASCADE,
  ADD CONSTRAINT `belongs_to_ibfk_2` FOREIGN KEY (`famille_id`) REFERENCES `famille_critere` (`id_famille`) ON DELETE CASCADE;

--
-- Contraintes pour la table `has_critere`
--
ALTER TABLE `has_critere`
  ADD CONSTRAINT `has_critere_ibfk_1` FOREIGN KEY (`critere_id`) REFERENCES `critere` (`id_critere`) ON DELETE CASCADE,
  ADD CONSTRAINT `has_critere_ibfk_2` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `has_image`
--
ALTER TABLE `has_image`
  ADD CONSTRAINT `has_image_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `images` (`id_image`) ON DELETE CASCADE,
  ADD CONSTRAINT `has_image_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE;
