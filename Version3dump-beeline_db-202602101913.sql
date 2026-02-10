-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: beeline_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `agence`
--

DROP TABLE IF EXISTS `agence`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_ag` varchar(255) NOT NULL,
  `adresse_ag` longtext DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `horaires` longtext DEFAULT NULL,
  `banque_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_64C19AA937E080D9` (`banque_id`),
  CONSTRAINT `FK_64C19AA937E080D9` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agence`
--

LOCK TABLES `agence` WRITE;
/*!40000 ALTER TABLE `agence` DISABLE KEYS */;
/*!40000 ALTER TABLE `agence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banque`
--

DROP TABLE IF EXISTS `banque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banque` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_bq` varchar(255) NOT NULL,
  `site_web` varchar(255) DEFAULT NULL,
  `telephone_bq` varchar(20) DEFAULT NULL,
  `email_bq` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `statut` varchar(20) NOT NULL,
  `description` longtext DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banque`
--

LOCK TABLES `banque` WRITE;
/*!40000 ALTER TABLE `banque` DISABLE KEYS */;
INSERT INTO `banque` VALUES (1,'BH BANK','https://www.bhbank.com','+216 71 123 456','contact@bhbank.com','bh-logo.png','active','Banque d\'affaires et de services bancaires en Tunisie'),(2,'Attijariwafa Bank','https://www.attijariwafabank.com.tn','+216 71 962 000','contact@attijariwafabank.com.tn','attijariwafa-logo.png','active','Banque de premier plan en Tunisie et en Afrique du Nord'),(3,'BIAT','https://www.biat.com.tn','+216 71 340 000','info@biat.com.tn','biat-logo.png','active','Banque Internationale Arabe de Tunisie - Leader du secteur bancaire');
/*!40000 ALTER TABLE `banque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `condition_offre`
--

DROP TABLE IF EXISTS `condition_offre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `condition_offre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offre_id` int(11) NOT NULL,
  `taux_special` double DEFAULT NULL,
  `montant_seuil` decimal(15,2) DEFAULT NULL,
  `duree_max` int(11) DEFAULT NULL,
  `condit_num` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E35D62644CC8505A` (`offre_id`),
  CONSTRAINT `FK_E35D62644CC8505A` FOREIGN KEY (`offre_id`) REFERENCES `offre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `condition_offre`
--

LOCK TABLES `condition_offre` WRITE;
/*!40000 ALTER TABLE `condition_offre` DISABLE KEYS */;
/*!40000 ALTER TABLE `condition_offre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20260210181121','2026-02-10 19:11:28',705);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document`
--

DROP TABLE IF EXISTS `document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `financement_id` int(11) NOT NULL,
  `nom_fichier` varchar(255) NOT NULL,
  `type_document` varchar(50) NOT NULL,
  `chemin_stockage` varchar(255) NOT NULL,
  `statut_verification` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D8698A76A737ED74` (`financement_id`),
  CONSTRAINT `FK_D8698A76A737ED74` FOREIGN KEY (`financement_id`) REFERENCES `financement` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document`
--

LOCK TABLES `document` WRITE;
/*!40000 ALTER TABLE `document` DISABLE KEYS */;
/*!40000 ALTER TABLE `document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `establishment`
--

DROP TABLE IF EXISTS `establishment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `establishment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `establishment`
--

LOCK TABLES `establishment` WRITE;
/*!40000 ALTER TABLE `establishment` DISABLE KEYS */;
/*!40000 ALTER TABLE `establishment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `financement`
--

DROP TABLE IF EXISTS `financement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `financement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `montant_demande` decimal(15,2) NOT NULL,
  `duree_mois` int(11) NOT NULL,
  `objet_financement` longtext NOT NULL,
  `statut` varchar(20) NOT NULL,
  `date_demande` datetime NOT NULL,
  `date_reponse` datetime DEFAULT NULL,
  `commentaire_agent` longtext DEFAULT NULL,
  `client_id` int(11) NOT NULL,
  `banque_id` int(11) NOT NULL,
  `offre_id` int(11) DEFAULT NULL,
  `type_dmd` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_59895F5619EB6921` (`client_id`),
  KEY `IDX_59895F5637E080D9` (`banque_id`),
  KEY `IDX_59895F564CC8505A` (`offre_id`),
  CONSTRAINT `FK_59895F5619EB6921` FOREIGN KEY (`client_id`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_59895F5637E080D9` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`),
  CONSTRAINT `FK_59895F564CC8505A` FOREIGN KEY (`offre_id`) REFERENCES `offre` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `financement`
--

LOCK TABLES `financement` WRITE;
/*!40000 ALTER TABLE `financement` DISABLE KEYS */;
/*!40000 ALTER TABLE `financement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750` (`queue_name`,`available_at`,`delivered_at`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offre`
--

DROP TABLE IF EXISTS `offre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `type_offre` varchar(100) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `taux` decimal(5,2) DEFAULT NULL,
  `active` tinyint(4) NOT NULL,
  `banque_id` int(11) NOT NULL,
  `montant_min` decimal(15,2) DEFAULT NULL,
  `montant_max` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_AF86866F37E080D9` (`banque_id`),
  CONSTRAINT `FK_AF86866F37E080D9` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offre`
--

LOCK TABLES `offre` WRITE;
/*!40000 ALTER TABLE `offre` DISABLE KEYS */;
INSERT INTO `offre` VALUES (1,'DFFS','DFSFS','promotion_speciale','2026-02-23','2026-02-24',0.10,1,3,NULL,NULL);
/*!40000 ALTER TABLE `offre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile`
--

DROP TABLE IF EXISTS `profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `niveau_experience` varchar(100) DEFAULT NULL,
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`preferences`)),
  `photo` varchar(255) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `specialite` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8157AA0FFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_8157AA0FFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile`
--

LOCK TABLES `profile` WRITE;
/*!40000 ALTER TABLE `profile` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendez_vous`
--

DROP TABLE IF EXISTS `rendez_vous`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rendez_vous` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_rdv` date NOT NULL,
  `heure_rdv` time NOT NULL,
  `statut` varchar(20) NOT NULL,
  `qr_code` longtext DEFAULT NULL,
  `ticket_reference` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `client_id` int(11) NOT NULL,
  `banque_id` int(11) NOT NULL,
  `agence_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `duree` int(11) DEFAULT NULL,
  `priorite` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_65E8AA0A34999359` (`ticket_reference`),
  KEY `IDX_65E8AA0A19EB6921` (`client_id`),
  KEY `IDX_65E8AA0A37E080D9` (`banque_id`),
  KEY `IDX_65E8AA0AD725330D` (`agence_id`),
  KEY `IDX_65E8AA0AED5CA9E6` (`service_id`),
  CONSTRAINT `FK_65E8AA0A19EB6921` FOREIGN KEY (`client_id`) REFERENCES `utilisateur` (`id`),
  CONSTRAINT `FK_65E8AA0A37E080D9` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`),
  CONSTRAINT `FK_65E8AA0AD725330D` FOREIGN KEY (`agence_id`) REFERENCES `agence` (`id`),
  CONSTRAINT `FK_65E8AA0AED5CA9E6` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendez_vous`
--

LOCK TABLES `rendez_vous` WRITE;
/*!40000 ALTER TABLE `rendez_vous` DISABLE KEYS */;
/*!40000 ALTER TABLE `rendez_vous` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service`
--

DROP TABLE IF EXISTS `service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom_service` varchar(255) NOT NULL,
  `description` longtext DEFAULT NULL,
  `duree_estimee` int(11) DEFAULT NULL,
  `disponible` tinyint(4) NOT NULL,
  `banque_id` int(11) NOT NULL,
  `priorite_defaut` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E19D9AD237E080D9` (`banque_id`),
  CONSTRAINT `FK_E19D9AD237E080D9` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service`
--

LOCK TABLES `service` WRITE;
/*!40000 ALTER TABLE `service` DISABLE KEYS */;
INSERT INTO `service` VALUES (1,'BLA BLA BLA BLA','QSDGJHQSDUIFGNQUISNDUINV',30,1,3,NULL);
/*!40000 ALTER TABLE `service` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `statut_compte` varchar(20) NOT NULL,
  `banque_id` int(11) DEFAULT NULL,
  `cin` varchar(20) DEFAULT NULL,
  `adresse` longtext DEFAULT NULL,
  `code_postal` varchar(10) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`),
  KEY `IDX_1D1C63B337E080D9` (`banque_id`),
  CONSTRAINT `FK_1D1C63B337E080D9` FOREIGN KEY (`banque_id`) REFERENCES `banque` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (1,'mehdimejri15@gmail.com','[\"ROLE_CLIENT\"]','$2y$13$PJD740RyCkbFOeeEjviTxegyWKYxLlsUpKUwS6/9NGAGP/8m29x4C','Mejri','Mehdi','321412','active',1,NULL,NULL,NULL,NULL),(4,'az@gmail.com','[\"ROLE_AGENT\"]','$2y$13$InHeMSVrR7MaOh0APjDVouG0AIhQrsTpRZqKZpFiyoS8AGHUCX0He','Mejri','Mehdi','321412','active',3,NULL,NULL,NULL,NULL),(5,'admin@beeline.com','[\"ROLE_ADMIN\"]','$2y$13$F8JjZoIYEM4jhMNa.xd4F.XNrrtNQgUxV6M.3R7C8VgfCD9bgKwTq','Admin','System','+216 00 000 000','active',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'beeline_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-10 19:14:00
