CREATE DATABASE  IF NOT EXISTS `DigitalGallery` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `DigitalGallery`;
-- MySQL dump 10.13  Distrib 5.5.12, for Linux (x86_64)
--
-- Host: localhost    Database: DigitalGallery
-- ------------------------------------------------------
-- Server version	5.5.12

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `DigitalArt`
--

DROP TABLE IF EXISTS `DigitalArt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DigitalArt` (
  `idDigitalArt` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idAuthor` int(10) unsigned NOT NULL,
  `digitalArtName` varchar(45) NOT NULL,
  `digitalArtMedia` varchar(60) NOT NULL,
  `digitalArtPrice` decimal(10,2) unsigned NOT NULL DEFAULT '500.00',
  PRIMARY KEY (`idDigitalArt`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DigitalArt`
--

LOCK TABLES `DigitalArt` WRITE;
/*!40000 ALTER TABLE `DigitalArt` DISABLE KEYS */;
INSERT INTO `DigitalArt` VALUES (1,1,'Fracwaves','media/Fracwaves_by_Pantoja.jpg',500.00),(2,1,'Golden Petal','media/golden_petal_within_by_pantoja-d313ryk.jpg',500.00),(3,1,'Fractal','media/Round_02_Jhow_VS_Pantoja_by_Pantoja.jpg',500.00);
/*!40000 ALTER TABLE `DigitalArt` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-01 16:39:47
