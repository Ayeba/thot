-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 07 Novembre 2011 à 23:45
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
INSERT INTO `belongs_to` VALUES(7, 3);
INSERT INTO `belongs_to` VALUES(10, 4);
INSERT INTO `belongs_to` VALUES(11, 4);
INSERT INTO `belongs_to` VALUES(12, 4);
INSERT INTO `belongs_to` VALUES(13, 4);
INSERT INTO `belongs_to` VALUES(14, 4);
INSERT INTO `belongs_to` VALUES(15, 4);
INSERT INTO `belongs_to` VALUES(16, 4);
INSERT INTO `belongs_to` VALUES(17, 4);
INSERT INTO `belongs_to` VALUES(18, 4);
INSERT INTO `belongs_to` VALUES(19, 4);
INSERT INTO `belongs_to` VALUES(20, 4);
INSERT INTO `belongs_to` VALUES(21, 4);
INSERT INTO `belongs_to` VALUES(22, 4);
INSERT INTO `belongs_to` VALUES(23, 4);
INSERT INTO `belongs_to` VALUES(24, 4);
INSERT INTO `belongs_to` VALUES(25, 4);
INSERT INTO `belongs_to` VALUES(26, 4);
INSERT INTO `belongs_to` VALUES(27, 4);
INSERT INTO `belongs_to` VALUES(28, 4);
INSERT INTO `belongs_to` VALUES(29, 4);
INSERT INTO `belongs_to` VALUES(30, 4);
INSERT INTO `belongs_to` VALUES(31, 4);

-- --------------------------------------------------------

--
-- Structure de la table `critere`
--

CREATE TABLE `critere` (
  `id_critere` int(11) NOT NULL AUTO_INCREMENT,
  `nom_critere` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_critere`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Contenu de la table `critere`
--

INSERT INTO `critere` VALUES(1, 'qualité', '');
INSERT INTO `critere` VALUES(2, 'sécurité', '');
INSERT INTO `critere` VALUES(3, 'administration', '');
INSERT INTO `critere` VALUES(4, 'association', '');
INSERT INTO `critere` VALUES(7, 'Ayeba', '');
INSERT INTO `critere` VALUES(10, 'chef de projet', '');
INSERT INTO `critere` VALUES(11, 'directeur de projet ', '');
INSERT INTO `critere` VALUES(12, 'responsable de projet ', '');
INSERT INTO `critere` VALUES(13, 'dirigeant', '');
INSERT INTO `critere` VALUES(14, 'chef d''entreprise ', '');
INSERT INTO `critere` VALUES(15, 'gérant', '');
INSERT INTO `critere` VALUES(16, 'manager', '');
INSERT INTO `critere` VALUES(17, 'développeur', '');
INSERT INTO `critere` VALUES(18, 'codeur', '');
INSERT INTO `critere` VALUES(19, 'graphiste', '');
INSERT INTO `critere` VALUES(20, 'intégrateur', '');
INSERT INTO `critere` VALUES(21, 'administrateur système', '');
INSERT INTO `critere` VALUES(22, 'administrateur de base de données', '');
INSERT INTO `critere` VALUES(23, 'responsable marketing', '');
INSERT INTO `critere` VALUES(24, 'commercial', '');
INSERT INTO `critere` VALUES(25, 'directeur commercial', '');
INSERT INTO `critere` VALUES(26, 'chef de produit', '');
INSERT INTO `critere` VALUES(27, 'directeur marketing', '');
INSERT INTO `critere` VALUES(28, 'directeur financier', '');
INSERT INTO `critere` VALUES(29, 'comptable', '');
INSERT INTO `critere` VALUES(30, 'administration des ventes', '');
INSERT INTO `critere` VALUES(31, 'responsable des ressources humaines', '');

-- --------------------------------------------------------

--
-- Structure de la table `dates`
--

CREATE TABLE `dates` (
  `formation_id` int(11) NOT NULL,
  `ville_id` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`formation_id`,`ville_id`,`date`),
  KEY `ville_id` (`ville_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `dates`
--

INSERT INTO `dates` VALUES(1, 1, '2011-11-13');
INSERT INTO `dates` VALUES(1, 1, '2011-11-23');
INSERT INTO `dates` VALUES(9, 1, '2011-11-21');
INSERT INTO `dates` VALUES(11, 1, '2011-11-11');
INSERT INTO `dates` VALUES(1, 2, '2011-11-16');
INSERT INTO `dates` VALUES(1, 2, '2011-12-12');

-- --------------------------------------------------------

--
-- Structure de la table `en_relation`
--

CREATE TABLE `en_relation` (
  `formation_id` int(11) NOT NULL,
  `relation_formation_id` int(11) NOT NULL,
  PRIMARY KEY (`formation_id`,`relation_formation_id`),
  KEY `relation_formation_id` (`relation_formation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `en_relation`
--

INSERT INTO `en_relation` VALUES(9, 12);

-- --------------------------------------------------------

--
-- Structure de la table `famille_critere`
--

CREATE TABLE `famille_critere` (
  `id_famille` int(11) NOT NULL AUTO_INCREMENT,
  `nom_famille` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_famille`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `famille_critere`
--

INSERT INTO `famille_critere` VALUES(1, 'domaines', '');
INSERT INTO `famille_critere` VALUES(2, 'structures', '');
INSERT INTO `famille_critere` VALUES(3, 'labels', '');
INSERT INTO `famille_critere` VALUES(4, 'public', '');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

CREATE TABLE `formations` (
  `id_formation` int(11) NOT NULL AUTO_INCREMENT,
  `nom_formation` varchar(250) NOT NULL,
  `soustitre` varchar(300) NOT NULL,
  `description` text NOT NULL,
  `objectifs` text NOT NULL,
  `prerequis` text NOT NULL,
  `code` varchar(5) NOT NULL,
  `programme` text NOT NULL,
  `dureejours` int(11) NOT NULL,
  `dureeheures` int(11) NOT NULL,
  `plus` text NOT NULL,
  `tarifinter` int(11) NOT NULL,
  `tarifcp` int(11) NOT NULL,
  `image` varchar(100) NOT NULL,
  `status` int(11) NOT NULL COMMENT 'brouillon : 0 / à valider : 1 / publiée : 2',
  PRIMARY KEY (`id_formation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `formations`
--

INSERT INTO `formations` VALUES(1, 'toto', '', '', '', '', '', '', 0, 0, '', 0, 0, 'photo1320311136.JPG', 0);
INSERT INTO `formations` VALUES(2, 'titi', '', '', '', '', '', '', 0, 0, '', 0, 0, '', 0);
INSERT INTO `formations` VALUES(6, 'XC303', '', '', '', '', '', '', 0, 0, '', 0, 0, '', 0);
INSERT INTO `formations` VALUES(9, 'Devenir parfait', 'La meilleure formation de tous les temps', 'La meilleure formation de tous les temps vous permet de devenir le meilleur homme de tous les temps', 'devenir fort\r\ndevenir grand\r\navoir de grands bras\r\navoir de grandes jambes', 'avoir deux yeux\r\navoir deux oreilles', 'XC304', 'ici le programme de la formation. reste à définir un format pour l''affichage', 1, 14, 'Cette formation est animée par dieu', 300, 4000, 'photo1320310036.JPG', 2);
INSERT INTO `formations` VALUES(11, 'blabla', '', '', '', '', '', '', 0, 0, '', 0, 0, '', 0);
INSERT INTO `formations` VALUES(12, 'boubou', '', '', '', '', '', '', 0, 0, '', 0, 0, '', 2);
INSERT INTO `formations` VALUES(13, 'nouvelle formation', '', '', '', '', '', '', 0, 0, '', 0, 0, '', 1);
INSERT INTO `formations` VALUES(14, 'encore', '', '', '', '', '', '', 0, 0, '', 0, 0, '', 2);

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

INSERT INTO `has_critere` VALUES(12, 1);
INSERT INTO `has_critere` VALUES(1, 2);
INSERT INTO `has_critere` VALUES(2, 2);
INSERT INTO `has_critere` VALUES(6, 2);
INSERT INTO `has_critere` VALUES(12, 2);
INSERT INTO `has_critere` VALUES(1, 3);
INSERT INTO `has_critere` VALUES(2, 3);
INSERT INTO `has_critere` VALUES(6, 3);
INSERT INTO `has_critere` VALUES(9, 3);
INSERT INTO `has_critere` VALUES(1, 4);
INSERT INTO `has_critere` VALUES(2, 4);
INSERT INTO `has_critere` VALUES(6, 4);
INSERT INTO `has_critere` VALUES(9, 7);
INSERT INTO `has_critere` VALUES(13, 7);
INSERT INTO `has_critere` VALUES(12, 10);
INSERT INTO `has_critere` VALUES(9, 15);
INSERT INTO `has_critere` VALUES(9, 22);
INSERT INTO `has_critere` VALUES(9, 24);
INSERT INTO `has_critere` VALUES(9, 25);
INSERT INTO `has_critere` VALUES(9, 28);

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
-- Structure de la table `in_selection`
--

CREATE TABLE `in_selection` (
  `selection_id` int(11) NOT NULL,
  `formation_id` int(11) NOT NULL,
  PRIMARY KEY (`selection_id`,`formation_id`),
  KEY `formation_id` (`formation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `in_selection`
--

INSERT INTO `in_selection` VALUES(1, 12);
INSERT INTO `in_selection` VALUES(1, 14);
INSERT INTO `in_selection` VALUES(2, 14);

-- --------------------------------------------------------

--
-- Structure de la table `plus_loin`
--

CREATE TABLE `plus_loin` (
  `formation_id` int(11) NOT NULL,
  `plusloin_formation_id` int(11) NOT NULL,
  PRIMARY KEY (`formation_id`,`plusloin_formation_id`),
  KEY `plusloin_formation_id` (`plusloin_formation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `plus_loin`
--

INSERT INTO `plus_loin` VALUES(9, 2);
INSERT INTO `plus_loin` VALUES(9, 14);

-- --------------------------------------------------------

--
-- Structure de la table `selections`
--

CREATE TABLE `selections` (
  `id_selection` int(11) NOT NULL AUTO_INCREMENT,
  `nom_selection` varchar(200) NOT NULL,
  `commentaire` text NOT NULL,
  `datemod` datetime NOT NULL,
  PRIMARY KEY (`id_selection`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `selections`
--

INSERT INTO `selections` VALUES(1, 'première selection', 'première selection qui sert aux tests', '2011-11-07 21:05:48');
INSERT INTO `selections` VALUES(2, 'deuxième test', 'blabla', '2011-11-07 23:17:49');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `droits` int(11) NOT NULL COMMENT '1 : admin / 10 : webmaster / 100 : comercial',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` VALUES('romain', 'f71dbe52628a3f83a77ab494817525c6', 'romain@ayeba.fr', 1);

-- --------------------------------------------------------

--
-- Structure de la table `villes`
--

CREATE TABLE `villes` (
  `id_ville` int(11) NOT NULL AUTO_INCREMENT,
  `nom_ville` varchar(100) NOT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id_ville`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `villes`
--

INSERT INTO `villes` VALUES(1, 'Bordeaux', '');
INSERT INTO `villes` VALUES(2, 'Toulouse', '');
INSERT INTO `villes` VALUES(3, 'blagnac', '');
INSERT INTO `villes` VALUES(4, 'Paris', '');

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
-- Contraintes pour la table `dates`
--
ALTER TABLE `dates`
  ADD CONSTRAINT `dates_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE,
  ADD CONSTRAINT `dates_ibfk_2` FOREIGN KEY (`ville_id`) REFERENCES `villes` (`id_ville`) ON DELETE CASCADE;

--
-- Contraintes pour la table `en_relation`
--
ALTER TABLE `en_relation`
  ADD CONSTRAINT `en_relation_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE,
  ADD CONSTRAINT `en_relation_ibfk_2` FOREIGN KEY (`relation_formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `has_image_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE,
  ADD CONSTRAINT `has_image_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `images` (`id_image`) ON DELETE CASCADE;

--
-- Contraintes pour la table `in_selection`
--
ALTER TABLE `in_selection`
  ADD CONSTRAINT `in_selection_ibfk_2` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE,
  ADD CONSTRAINT `in_selection_ibfk_1` FOREIGN KEY (`selection_id`) REFERENCES `selections` (`id_selection`) ON DELETE CASCADE;

--
-- Contraintes pour la table `plus_loin`
--
ALTER TABLE `plus_loin`
  ADD CONSTRAINT `plus_loin_ibfk_1` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE,
  ADD CONSTRAINT `plus_loin_ibfk_2` FOREIGN KEY (`plusloin_formation_id`) REFERENCES `formations` (`id_formation`) ON DELETE CASCADE;
