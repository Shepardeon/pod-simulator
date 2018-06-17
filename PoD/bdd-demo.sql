-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 17 juin 2018 à 15:02
-- Version du serveur :  5.7.21
-- Version de PHP :  5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bdd_pod`
--

-- --------------------------------------------------------

--
-- Structure de la table `joueurs`
--

DROP TABLE IF EXISTS `joueurs`;
CREATE TABLE IF NOT EXISTS `joueurs` (
  `ID_Joueurs` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permet de générer une id pour chaque nouvel utilisateur sur le jeu',
  `Pseudo` char(20) NOT NULL COMMENT 'Le nouveau joueur se définit un Pseudo qui permet la reconnexion cette valeur est unique',
  `Pass` varchar(70) NOT NULL COMMENT 'Le mot de passe du joueur',
  `Mail` char(50) NOT NULL COMMENT 'Permet d''éditer le profil unique et permet la reconnexion',
  `Valide` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Permet de savoir si l''utilisateur à validé son compte',
  `Chaine_Validation` char(10) NOT NULL COMMENT 'Coorespond à la chaine envoyé par mail pour finaliser la création du compte',
  `Fonds` int(6) NOT NULL DEFAULT '1000' COMMENT 'Ressources du joueur issue de ses différentes attaques, peut être dilapidée par des joueurs adverses',
  `Fonds_Securise` int(6) NOT NULL DEFAULT '0' COMMENT 'Ressources du joueur inviolable par un adversaire, dépend du niveau du joueur',
  `Revenus` int(6) NOT NULL DEFAULT '0' COMMENT 'Gain régulier du joueur',
  `Niveau` int(6) NOT NULL DEFAULT '1' COMMENT 'Niveau du joueur, évolue suivant les attaques du joueur',
  PRIMARY KEY (`ID_Joueurs`) COMMENT='Clé primaire de l''identifiant joueur utile pour la table ordinateurs et virus '
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `joueurs`
--

INSERT INTO `joueurs` (`ID_Joueurs`, `Pseudo`, `Pass`, `Mail`, `Valide`, `Chaine_Validation`, `Fonds`, `Fonds_Securise`, `Revenus`, `Niveau`) VALUES
(1, 'Joueur1', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'joueur1@test.fr', 1, 'A', 710, 500, 230, 3),
(2, 'Joueur2', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'joueur2@test.fr', 1, 'A', 0, 115, 0, 5),
(3, 'Joueur3', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'joueur3@test.fr', 1, 'A', 45, 1000, 0, 5),
(4, 'Joueur4', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'joueur4@test.fr', 1, 'A', 0, 1453, 20, 4),
(5, 'Joueur5', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'joueur5@test.fr', 1, 'A', 0, 1550, 0, 4),
(6, 'Joueur6', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'joueur6@test.fr', 1, 'A', 0, 10000, 280, 20);

-- --------------------------------------------------------

--
-- Structure de la table `ordinateurs`
--

DROP TABLE IF EXISTS `ordinateurs`;
CREATE TABLE IF NOT EXISTS `ordinateurs` (
  `ID_Ordinateurs` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permet d''identifier les machines du jeu',
  `IP` char(16) NOT NULL COMMENT 'Adresse IP virtuelle correspondant aux ordinateurs',
  `ID_Joueurs` int(11) NOT NULL COMMENT 'Identifiant du joueur qui permet de lui affecter une machine',
  `Pare_feu` int(11) NOT NULL DEFAULT '1' COMMENT 'Niveau du pare-feu du joueur',
  `Anti_Virus` int(11) NOT NULL DEFAULT '1' COMMENT 'Niveau de l''anti-virus du joueur',
  `Porte_Feuille` int(11) NOT NULL DEFAULT '1' COMMENT 'Niveau qui permet d''obtenir plus de revenus',
  `Scanner_Reseau` int(11) NOT NULL DEFAULT '1' COMMENT 'Fonction du jeu',
  `FW_Cracker` int(11) NOT NULL DEFAULT '1' COMMENT 'Fonction du jeu',
  `SW_Cracker` int(11) NOT NULL DEFAULT '1' COMMENT 'Fonction du jeu',
  `Generateur_de_Miner` int(11) NOT NULL DEFAULT '1' COMMENT 'Fonction du jeu',
  `Generateur_de_Backdoor` int(11) NOT NULL DEFAULT '1' COMMENT 'Fonction du jeu',
  `Carte_Reseau` int(11) NOT NULL DEFAULT '1' COMMENT 'Niveau de la carte réseau de la machine du joueur',
  `Processeur` int(11) NOT NULL DEFAULT '1' COMMENT 'Niveau du processeur de la machine du joueur',
  `Disque_Dur` int(11) NOT NULL DEFAULT '1' COMMENT 'Niveau de disque dur de la machine du joueur',
  `LOG` text NOT NULL COMMENT 'Fichier texte qui contient les diverses attaques sur la machine',
  PRIMARY KEY (`ID_Ordinateurs`),
  KEY `fk_ID_Joueurs` (`ID_Joueurs`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ordinateurs`
--

INSERT INTO `ordinateurs` (`ID_Ordinateurs`, `IP`, `ID_Joueurs`, `Pare_feu`, `Anti_Virus`, `Porte_Feuille`, `Scanner_Reseau`, `FW_Cracker`, `SW_Cracker`, `Generateur_de_Miner`, `Generateur_de_Backdoor`, `Carte_Reseau`, `Processeur`, `Disque_Dur`, `LOG`) VALUES
(1, '176.10.108.94', 1, 1, 3, 3, 1, 2, 2, 2, 1, 2, 3, 1, '====| Début des logs |====\r\n[16:59] [ALERTE] - Connexion externe en provenance de 96.80.149.142'),
(2, '147.217.78.37', 2, 2, 5, 1, 1, 2, 2, 2, 2, 1, 1, 1, '====| Début des logs |===='),
(3, '177.229.140.188', 3, 1, 1, 1, 1, 1, 1, 5, 1, 1, 1, 1, '====| Début des logs |====\r\n[16:58] [ALERTE] - Connexion externe en provenance de 96.80.149.142\r\n[16:58] [INFO] - Transfert de Generateur de Miner vers 96.80.149.142'),
(4, '62.53.158.38', 4, 1, 2, 4, 1, 1, 1, 1, 1, 1, 3, 1, '====| Début des logs |===='),
(5, '161.178.49.229', 5, 3, 2, 3, 3, 1, 1, 4, 3, 1, 1, 1, '====| Début des logs |===='),
(6, '96.80.149.142', 6, 1, 1, 10, 1, 2, 2, 4, 3, 1, 20, 1, '====| Début des logs |====\r\n[17:01] [ALERTE] - Connexion externe en provenance de 176.10.108.94\r\n[17:01] [INFO] - Transfert de Generateur de Miner vers 176.10.108.94\r\n[17:01] [INFO] - Transfert de Porte Feuille vers 176.10.108.94\r\n[17:01] [INFO] - Transfert de 110 I2C vers 176.10.108.94');

-- --------------------------------------------------------

--
-- Structure de la table `telechargements`
--

DROP TABLE IF EXISTS `telechargements`;
CREATE TABLE IF NOT EXISTS `telechargements` (
  `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permet d''identifier le téléchargement',
  `ID_Ordinateurs` int(11) NOT NULL COMMENT 'ID de la machine qui a téléchargé',
  `Logiciel` char(22) NOT NULL COMMENT 'Le nom du logiciel dans la BDD',
  `Niveau` int(11) NOT NULL COMMENT 'Le niveau du logiciel téléchargé',
  PRIMARY KEY (`ID`),
  KEY `fk_ID_Ordi_tele` (`ID_Ordinateurs`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `telechargements`
--

INSERT INTO `telechargements` (`ID`, `ID_Ordinateurs`, `Logiciel`, `Niveau`) VALUES
(1, 6, 'Generateur_de_Miner', 5),
(2, 1, 'Generateur_de_Miner', 4),
(3, 1, 'Porte_Feuille', 10);

-- --------------------------------------------------------

--
-- Structure de la table `virus`
--

DROP TABLE IF EXISTS `virus`;
CREATE TABLE IF NOT EXISTS `virus` (
  `ID_Virus` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permet d''identifier le virus',
  `ID_Ordinateurs` int(11) NOT NULL COMMENT 'Permet d''identifier la machine infectée',
  `ID_Joueurs` int(11) NOT NULL COMMENT 'Permet d''identifier le joueur qui a infecté',
  `Type_Virus` char(8) NOT NULL COMMENT 'Permet de nommer le type de virus utilisé',
  `Niveau` smallint(6) NOT NULL DEFAULT '1' COMMENT 'Défini les différents types de virus du plus gentil au plus agressif',
  PRIMARY KEY (`ID_Virus`),
  KEY `fk_ID_Joueurs_Virus` (`ID_Joueurs`),
  KEY `fk_ID_Ordinateurs` (`ID_Ordinateurs`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `virus`
--

INSERT INTO `virus` (`ID_Virus`, `ID_Ordinateurs`, `ID_Joueurs`, `Type_Virus`, `Niveau`) VALUES
(1, 3, 6, 'Miner', 4),
(2, 3, 6, 'Backdoor', 3),
(3, 1, 6, 'Miner', 4),
(4, 1, 6, 'Backdoor', 3),
(5, 6, 1, 'Miner', 2),
(6, 6, 1, 'Backdoor', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
