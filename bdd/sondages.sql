-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 27 oct. 2020 à 14:00
-- Version du serveur :  5.7.11
-- Version de PHP : 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sondages`
--
CREATE DATABASE `sondages`;
USE `sondages`;
-- --------------------------------------------------------

--
-- Structure de la table `responses`
--

CREATE TABLE `responses` (
  `id` int(11) NOT NULL,
  `id_survey` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `responses`
--

INSERT INTO `responses` (`id`, `id_survey`, `title`, `count`) VALUES
(1, 1, 'Bleu', 5),
(2, 1, 'Noir', 2),
(3, 1, 'Jaune', 1),
(4, 2, 'Lundi', 0),
(5, 2, 'Samedi', 6),
(6, 2, 'Dimanche', 10),
(7, 3, '10 ans', 25),
(8, 3, '25 ans', 0),
(9, 3, '100 ans', 2),
(10, 4, 'Sucré', 0);

-- --------------------------------------------------------

--
-- Structure de la table `surveys`
--

CREATE TABLE `surveys` (
  `id` int(11) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `surveys`
--

INSERT INTO `surveys` (`id`, `owner`, `question`) VALUES
(1, 'marc', 'Quelle est votre couleur préférée?'),
(2, 'marc', 'Quel est votre jour de la semaine préféré?'),
(3, 'paul', 'Combien d\'années représentent une décennie?'),
(4, 'marc', 'Vous préférez sucré ou salé?'),
(5, 'paul', 'Quel est l\'année de la Révolution française?');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `owner` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`owner`, `password`) VALUES
('andré', '$2y$10$E6so.kBz6Sq8uaf6eHWuje14o872MQB2lGXDZOEfS.xC04Mj0Tr8q'),
('marc', '$2y$10$ad0vRR.qwOU/OcGJhJHR2OjYxtFuxBUF4UFEfVsT./GdvA8P9gfDK'),
('paul', '$2y$10$HV0P.qwBatnsqNoGD1kN7.qHvoXTkLywunmzKF7t67KztN.jaNUia');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_survey` (`id_survey`);

--
-- Index pour la table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner` (`owner`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`owner`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `responses`
--
ALTER TABLE `responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`id_survey`) REFERENCES `surveys` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `users` (`owner`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
