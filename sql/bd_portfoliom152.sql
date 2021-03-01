-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2021 at 06:37 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bd_portfoliom152`
--
CREATE DATABASE IF NOT EXISTS `bd_portfoliom152` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bd_portfoliom152`;

-- --------------------------------------------------------

--
-- Table structure for table `t_media`
--

CREATE TABLE `t_media` (
  `idMedia` int(11) NOT NULL,
  `typeMedia` text NOT NULL,
  `nomMedia` text NOT NULL,
  `creationDate` datetime NOT NULL,
  `postUtilise` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_media`
--

INSERT INTO `t_media` (`idMedia`, `typeMedia`, `nomMedia`, `creationDate`, `postUtilise`) VALUES
(40, 'image/png', '603bdf91c9a88_beerEmpty.png', '2021-02-28 18:23:13', 10),
(41, 'image/png', '603bdf91c9bdf_beerFull.png', '2021-02-28 18:23:13', 10);

-- --------------------------------------------------------

--
-- Table structure for table `t_post`
--

CREATE TABLE `t_post` (
  `idPost` int(11) NOT NULL,
  `commentaire` text,
  `creationDate` datetime NOT NULL,
  `modificationDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_post`
--

INSERT INTO `t_post` (`idPost`, `commentaire`, `creationDate`, `modificationDate`) VALUES
(10, 'asd', '2021-02-28 18:23:13', '2021-02-28 18:23:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_media`
--
ALTER TABLE `t_media`
  ADD PRIMARY KEY (`idMedia`),
  ADD KEY `postUtilise` (`postUtilise`);

--
-- Indexes for table `t_post`
--
ALTER TABLE `t_post`
  ADD PRIMARY KEY (`idPost`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_media`
--
ALTER TABLE `t_media`
  MODIFY `idMedia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `t_post`
--
ALTER TABLE `t_post`
  MODIFY `idPost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_media`
--
ALTER TABLE `t_media`
  ADD CONSTRAINT `postFK` FOREIGN KEY (`postUtilise`) REFERENCES `t_post` (`idPost`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
