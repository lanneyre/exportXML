-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : jeu. 08 juin 2023 à 12:52
-- Version du serveur : 10.7.3-MariaDB-log
-- Version de PHP : 8.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cdcxml`
--

-- --------------------------------------------------------

--
-- Structure de la table `certificateur`
--

CREATE TABLE `certificateur` (
  `idClient` varchar(8) NOT NULL,
  `idContrat` varchar(20) NOT NULL,
  `emetteur` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `certification`
--

CREATE TABLE `certification` (
  `id` int(11) NOT NULL,
  `type` enum('RNCP','RS','Diplôme d''Etablissement','Diplôme Universitaire','Habilitation','Reconnaissance') NOT NULL,
  `code` varchar(100) NOT NULL,
  `natureDeposant` enum('CERTIFICATEUR','TIERS_CONFIANCE') DEFAULT NULL,
  `certificateur` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `emetteur`
--

CREATE TABLE `emetteur` (
  `idClient` varchar(8) NOT NULL,
  `flux` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `flux`
--

CREATE TABLE `flux` (
  `idFlux` varchar(50) NOT NULL,
  `horodatage` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `modalitesinscription`
--

CREATE TABLE `modalitesinscription` (
  `id` int(11) NOT NULL,
  `modaliteAcces` enum('FORMATION_INITIALE_HORS_APPRENTISSAGE','FORMATION_INITIALE_APPRENTISSAGE','FORMATION_CONTINUE_HORS_CONTRAT_DE_PROFESSIONNALISATION','FORMATION_CONTINUE_CONTRAT_DE_PROFESSIONNALISATION','VAE','EQUIVALENCE_(DIPLOME_ETRANGER)','CANDIDAT_LIBRE','nil') NOT NULL DEFAULT 'nil',
  `voieAccessVAE` enum('CONGES_VAE','VAE_CLASSIQUE') DEFAULT NULL,
  `initiativeInscription` enum('CERTIFIE','OF','POLE_EMPLOI','EMPLOYEUR','AUTRE') DEFAULT NULL,
  `dateInscription` date DEFAULT NULL,
  `passageCertification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `niveaunumeriqueeuropeen`
--

CREATE TABLE `niveaunumeriqueeuropeen` (
  `id` int(11) NOT NULL,
  `scoreGeneral` int(11) DEFAULT NULL,
  `passageCertification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `passagecertification`
--

CREATE TABLE `passagecertification` (
  `idTechnique` varchar(255) NOT NULL,
  `urlPreuve` varchar(255) DEFAULT NULL,
  `libelleOption` varchar(255) DEFAULT NULL,
  `obtentionCertification` enum('PAR_ADMISSION','PAR_SCORING') NOT NULL,
  `donneeCertifiee` tinyint(1) NOT NULL,
  `dateDebutExamen` date DEFAULT NULL,
  `dateFinExamen` date DEFAULT NULL,
  `modalitePassageExamen` enum('A_DISTANCE','EN_PRESENTIEL','MIXTE') DEFAULT NULL,
  `codePostalCentreExamen` varchar(9) DEFAULT NULL,
  `dateDebutValidite` date DEFAULT NULL,
  `dateFinValidite` date NOT NULL,
  `presenceNiveauLangueEuro` tinyint(1) NOT NULL,
  `niveauLangueEuropeen` enum('C2','C1','B2','B1','A2','A1','INSUFFISANT') DEFAULT NULL,
  `presenceNiveauNumeriqueEuro` tinyint(1) NOT NULL,
  `scoring` varchar(255) NOT NULL DEFAULT 'nil',
  `mentionValidee` enum('SANS_MENTION','MENTION_ASSEZ_BIEN','MENTION_BIEN','MENTION_TRES_BIEN','MENTION_TRES_BIEN_AVEC_FELICITATIONS_DU_JURY') NOT NULL DEFAULT 'SANS_MENTION',
  `verbatim` varchar(255) DEFAULT NULL,
  `certification` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `resultat`
--

CREATE TABLE `resultat` (
  `id` int(11) NOT NULL,
  `niveau` int(11) DEFAULT NULL,
  `domaineCompetenceId` varchar(1) DEFAULT NULL,
  `competenceId` varchar(1) DEFAULT NULL,
  `niveaunumeriqueeuropeen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `titulaire`
--

CREATE TABLE `titulaire` (
  `id` int(11) NOT NULL,
  `nomNaissance` varchar(60) NOT NULL,
  `nomUsage` varchar(60) NOT NULL DEFAULT 'nil',
  `prenom1` varchar(60) NOT NULL,
  `prenom2` varchar(60) DEFAULT NULL,
  `prenom3` varchar(60) DEFAULT NULL,
  `anneeNaissance` int(11) NOT NULL,
  `moisNaissance` int(11) NOT NULL,
  `jourNaissance` int(11) NOT NULL,
  `sexe` enum('F','M') NOT NULL,
  `codeInsee` varchar(5) DEFAULT NULL,
  `codePostal` varchar(9) DEFAULT NULL,
  `libelleCommuneNaissance` varchar(40) DEFAULT NULL,
  `codePaysNaissance` varchar(3) DEFAULT NULL,
  `libellePaysNaissance` varchar(40) DEFAULT NULL,
  `passagecertification` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `certificateur`
--
ALTER TABLE `certificateur`
  ADD PRIMARY KEY (`idClient`),
  ADD KEY `emetteur` (`emetteur`);

--
-- Index pour la table `certification`
--
ALTER TABLE `certification`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `emetteur`
--
ALTER TABLE `emetteur`
  ADD PRIMARY KEY (`idClient`),
  ADD KEY `flux` (`flux`);

--
-- Index pour la table `flux`
--
ALTER TABLE `flux`
  ADD PRIMARY KEY (`idFlux`);

--
-- Index pour la table `modalitesinscription`
--
ALTER TABLE `modalitesinscription`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `niveaunumeriqueeuropeen`
--
ALTER TABLE `niveaunumeriqueeuropeen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `passageCertification` (`passageCertification`);

--
-- Index pour la table `passagecertification`
--
ALTER TABLE `passagecertification`
  ADD PRIMARY KEY (`idTechnique`),
  ADD KEY `certification` (`certification`);

--
-- Index pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `niveaunumeriqueeuropeen` (`niveaunumeriqueeuropeen`);

--
-- Index pour la table `titulaire`
--
ALTER TABLE `titulaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `passagecertification` (`passagecertification`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `certification`
--
ALTER TABLE `certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modalitesinscription`
--
ALTER TABLE `modalitesinscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `niveaunumeriqueeuropeen`
--
ALTER TABLE `niveaunumeriqueeuropeen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `resultat`
--
ALTER TABLE `resultat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `titulaire`
--
ALTER TABLE `titulaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `certificateur`
--
ALTER TABLE `certificateur`
  ADD CONSTRAINT `certificateur_ibfk_1` FOREIGN KEY (`emetteur`) REFERENCES `emetteur` (`idClient`);

--
-- Contraintes pour la table `emetteur`
--
ALTER TABLE `emetteur`
  ADD CONSTRAINT `emetteur_ibfk_1` FOREIGN KEY (`flux`) REFERENCES `flux` (`idFlux`);

--
-- Contraintes pour la table `niveaunumeriqueeuropeen`
--
ALTER TABLE `niveaunumeriqueeuropeen`
  ADD CONSTRAINT `niveaunumeriqueeuropeen_ibfk_1` FOREIGN KEY (`passageCertification`) REFERENCES `passagecertification` (`idTechnique`);

--
-- Contraintes pour la table `passagecertification`
--
ALTER TABLE `passagecertification`
  ADD CONSTRAINT `passagecertification_ibfk_1` FOREIGN KEY (`certification`) REFERENCES `certification` (`id`);

--
-- Contraintes pour la table `resultat`
--
ALTER TABLE `resultat`
  ADD CONSTRAINT `resultat_ibfk_1` FOREIGN KEY (`niveaunumeriqueeuropeen`) REFERENCES `niveaunumeriqueeuropeen` (`id`);

--
-- Contraintes pour la table `titulaire`
--
ALTER TABLE `titulaire`
  ADD CONSTRAINT `titulaire_ibfk_1` FOREIGN KEY (`passagecertification`) REFERENCES `passagecertification` (`idTechnique`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
