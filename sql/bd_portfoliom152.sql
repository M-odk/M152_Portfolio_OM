-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 25 jan. 2021 à 09:43
-- Version du serveur :  5.7.31
-- Version de PHP : 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bd_portfoliom152`
--
CREATE DATABASE IF NOT EXISTS `bd_portfoliom152` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bd_portfoliom152`;

-- --------------------------------------------------------

--
-- Structure de la table `t_media`
--

DROP TABLE IF EXISTS `t_media`;
CREATE TABLE IF NOT EXISTS `t_media` (
  `idMedia` int(11) NOT NULL AUTO_INCREMENT,
  `typeMedia` text NOT NULL,
  `nomMedia` text NOT NULL,
  `creationDate` timestamp NOT NULL,
  `modificationDate` date NOT NULL,
  PRIMARY KEY (`idMedia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `t_post`
--

DROP TABLE IF EXISTS `t_post`;
CREATE TABLE IF NOT EXISTS `t_post` (
  `idPost` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` text NOT NULL,
  `creationDate` date NOT NULL,
  `modificationDate` date NOT NULL,
  `mediaUtilise` int(11) NOT NULL,
  PRIMARY KEY (`idPost`),
  KEY `mediaUtilise` (`mediaUtilise`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `t_post`
--
ALTER TABLE `t_post`
  ADD CONSTRAINT `t_post_ibfk_1` FOREIGN KEY (`mediaUtilise`) REFERENCES `t_media` (`idMedia`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
