-- MySQL dump 10.13  Distrib 5.5.42, for Linux (x86_64)
--
-- Host: localhost    Database: devarapp_client_lookbook
-- ------------------------------------------------------
-- Server version	5.5.42-cll

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
-- Table structure for table `attrib_ref`
--

DROP TABLE IF EXISTS `attrib_ref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attrib_ref` (
  `attrib_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `attrib_id` int(10) NOT NULL,
  `attrib_value` text,
  PRIMARY KEY (`attrib_ref_id`),
  FULLTEXT KEY `attrib_value` (`attrib_value`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attrib_ref`
--

LOCK TABLES `attrib_ref` WRITE;
/*!40000 ALTER TABLE `attrib_ref` DISABLE KEYS */;
INSERT INTO `attrib_ref` VALUES (1,1434,1,'30'),(2,1434,1,'32'),(3,1434,1,'34'),(4,1434,1,'36'),(5,1434,2,'#90EE90'),(6,1434,2,'#F08080'),(7,1434,2,'#808080'),(8,1439,1,'5'),(9,1439,1,'6'),(10,1439,1,'7'),(11,1439,1,'8'),(12,1438,1,'30'),(13,1438,1,'32'),(14,1438,1,'34'),(15,1438,2,'#ab8094'),(16,1438,2,'#008080'),(17,1438,2,'#008080'),(18,1435,1,'10'),(19,1435,1,'15'),(20,1435,1,'20'),(21,1437,2,'#90EE90'),(22,1437,2,'#FFA500'),(23,1437,2,'#b49369'),(24,1434,2,'#000000'),(28,1440,2,'#800000'),(26,1433,2,'#000000'),(27,1432,2,'#000000');
/*!40000 ALTER TABLE `attrib_ref` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attrib_ref_old`
--

DROP TABLE IF EXISTS `attrib_ref_old`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attrib_ref_old` (
  `attrib_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `attrib_id` int(10) NOT NULL,
  `attrib_value` text NOT NULL,
  PRIMARY KEY (`attrib_ref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attrib_ref_old`
--

LOCK TABLES `attrib_ref_old` WRITE;
/*!40000 ALTER TABLE `attrib_ref_old` DISABLE KEYS */;
INSERT INTO `attrib_ref_old` VALUES (1,1434,1,'[\"30\",\"32\",\"34\",\"36\"]'),(2,1434,2,'[\"#000000\",\"#C1C1C1\",\"#FFFFFF\"]'),(3,1439,1,'[\"5\",\"6\",\"7\",\"8\"]'),(4,1438,1,'[\"30\",\"32\",\"34\",\"36\"]'),(5,1438,2,'[\"#000000\",\"#C1C1C1\",\"#FFFFFF\"]'),(6,1435,1,'[\"10\",\"15\",\"20\"]'),(7,1437,2,'[\"#F3F3F3\",\"#C1C1C1\",\"#FFFFFF\"]');
/*!40000 ALTER TABLE `attrib_ref_old` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cat_ref`
--

DROP TABLE IF EXISTS `cat_ref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cat_ref` (
  `cat_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_parent_id` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  PRIMARY KEY (`cat_ref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cat_ref`
--

LOCK TABLES `cat_ref` WRITE;
/*!40000 ALTER TABLE `cat_ref` DISABLE KEYS */;
INSERT INTO `cat_ref` VALUES (1,0,1),(2,0,2),(3,1,3),(4,1,4),(5,2,8),(6,2,5),(7,2,6),(8,0,7);
/*!40000 ALTER TABLE `cat_ref` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_details` (
  `order_details_id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `prod_id` int(10) NOT NULL,
  `prod_name` varchar(256) NOT NULL,
  `prod_price` decimal(10,2) NOT NULL,
  `prod_quantity` decimal(10,2) NOT NULL,
  `prod_attribs` text NOT NULL,
  PRIMARY KEY (`order_details_id`)
) ENGINE=InnoDB AUTO_INCREMENT=207 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(2,1,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(3,2,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#FFA500\"}'),(4,3,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#90EE90\"}'),(5,4,1435,'Studs Rhinestones Bracelet',17.00,2.00,'{\"size\":\"10\"}'),(6,5,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(7,6,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#b49369\"}'),(8,6,1435,'Studs Rhinestones Bracelet',17.00,2.00,''),(9,7,1437,'Detachable Faux Fur Coat',159.99,3.00,'{\"color\":\"#FFA500\"}'),(10,8,1435,'Studs Rhinestones Bracelet',17.00,4.00,'{\"size\":\"10\"}'),(11,9,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#90EE90\"}'),(12,10,1437,'Detachable Faux Fur Coat',159.99,4.00,'{\"color\":\"#FFA500\"}'),(13,11,1435,'Studs Rhinestones Bracelet',17.00,5.00,'{\"size\":\"20\"}'),(14,12,1439,'Pyramid PU Bracelet',19.00,1.00,''),(15,13,1435,'Studs Rhinestones Bracelet',17.00,5.00,'{\"size\":\"10\"}'),(16,14,1437,'Detachable Faux Fur Coat',159.99,3.00,'{\"color\":\"#90EE90\"}'),(17,14,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"20\"}'),(18,15,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"34\"}'),(19,16,1437,'Detachable Faux Fur Coat',159.99,6.00,'{\"color\":\"#FFA500\"}'),(20,16,1439,'Pyramid PU Bracelet',19.00,5.00,'{\"size\":\"7\"}'),(21,17,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(22,18,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#b49369\"}'),(23,19,1435,'Studs Rhinestones Bracelet',17.00,2.00,'{\"size\":\"15\"}'),(24,20,1435,'Studs Rhinestones Bracelet',17.00,4.00,'{\"size\":\"20\"}'),(25,20,1435,'Studs Rhinestones Bracelet',17.00,4.00,'{\"size\":\"15\"}'),(26,20,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#b49369\"}'),(27,21,1435,'Studs Rhinestones Bracelet',17.00,7.00,'{\"size\":\"20\"}'),(28,22,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#b49369\"}'),(29,23,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(30,24,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(31,25,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"15\"}'),(32,25,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#90EE90\"}'),(33,25,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(34,26,1434,'Double-Breasted Coat',74.00,3.00,'{\"color\":\"#808080\",\"size\":\"36\"}'),(35,27,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#FFA500\"}'),(36,28,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#90EE90\"}'),(37,28,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"20\"}'),(38,29,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#FFA500\"}'),(39,30,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#b49369\"}'),(40,30,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#FFA500\"}'),(41,31,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"15\"}'),(42,31,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"30\"}'),(43,32,1439,'Pyramid PU Bracelet',19.00,10.00,'{\"size\":\"7\"}'),(44,33,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(45,34,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"15\"}'),(46,35,1435,'Studs Rhinestones Bracelet',17.00,2.00,'{\"size\":\"20\"}'),(47,36,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(48,37,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(49,38,1433,'Open Back Lace Dress',0.00,1.00,'{\"color\":\"#000000\"}'),(50,39,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(51,40,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(52,41,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(53,42,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(54,43,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(55,43,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(56,44,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(57,45,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"20\"}'),(58,46,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(59,47,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(60,48,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#008080\",\"size\":\"30\"}'),(61,49,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(62,50,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(63,50,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(64,51,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(65,51,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(66,52,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(67,52,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#90EE90\",\"size\":\"30\"}'),(68,53,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(69,53,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#90EE90\",\"size\":\"36\"}'),(70,54,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(71,54,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#90EE90\",\"size\":\"36\"}'),(72,55,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(73,56,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(74,57,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(75,58,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(76,58,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"30\"}'),(77,58,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(78,59,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(79,59,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"30\"}'),(80,59,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(81,60,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(82,60,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"30\"}'),(83,60,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(84,61,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(85,61,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"30\"}'),(86,61,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(87,62,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(88,63,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"30\"}'),(89,64,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(90,64,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(91,65,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(92,66,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(93,67,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#000000\",\"size\":\"30\"}'),(94,68,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(95,69,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"8\"}'),(96,70,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#90EE90\",\"size\":\"30\"}'),(97,71,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(98,71,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"32\"}'),(99,72,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"34\"}'),(100,73,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(101,74,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#90EE90\",\"size\":\"30\"}'),(102,75,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(103,75,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(104,76,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(105,77,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#008080\",\"size\":\"30\"}'),(106,78,1435,'Studs Rhinestones Bracelet',0.00,1.00,'{\"size\":\"10\"}'),(107,79,1434,'Double-Breasted Coat',74.00,6.00,'{\"color\":\"#90EE90\",\"size\":\"34\"}'),(108,79,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(109,80,1434,'Double-Breasted Coat',74.00,6.00,'{\"color\":\"#90EE90\",\"size\":\"34\"}'),(110,80,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(111,80,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"32\"}'),(112,81,1434,'Double-Breasted Coat',74.00,6.00,'{\"color\":\"#90EE90\",\"size\":\"34\"}'),(113,81,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"6\"}'),(114,81,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"32\"}'),(115,82,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"36\"}'),(116,83,1439,'Pyramid PU Bracelet',19.00,3.00,'{\"size\":\"7\"}'),(117,84,1439,'Pyramid PU Bracelet',19.00,2.00,'{\"size\":\"6\"}'),(118,85,1434,'Double-Breasted Coat',74.00,2.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(119,85,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#008080\",\"size\":\"32\"}'),(120,86,1439,'Pyramid PU Bracelet',19.00,9.00,'{\"size\":\"7\"}'),(121,87,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#F08080\",\"size\":\"36\"}'),(122,88,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(123,88,1434,'Double-Breasted Coat',74.00,5.00,'{\"color\":\"#000000\",\"size\":\"30\"}'),(124,89,1439,'Pyramid PU Bracelet',19.00,10.00,'{\"size\":\"5\"}'),(125,90,1439,'Pyramid PU Bracelet',19.00,5.00,'{\"size\":\"7\"}'),(126,90,1435,'Studs Rhinestones Bracelet',17.00,2.00,'{\"size\":\"10\"}'),(127,91,1438,'Vintage Circle Shades',15.00,4.00,'{\"color\":\"#008080\",\"size\":\"34\"}'),(128,92,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(129,93,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(130,94,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(131,95,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"7\"}'),(132,95,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#808080\",\"size\":\"30\"}'),(133,96,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(134,97,1434,'Double-Breasted Coat',74.00,2.00,'{\"color\":\"#90EE90\",\"size\":\"30\"}'),(135,97,1438,'Vintage Circle Shades',15.00,2.00,'{\"color\":\"#008080\",\"size\":\"30\"}'),(136,98,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#008080\",\"size\":\"30\"}'),(137,99,1438,'Vintage Circle Shades',15.00,10.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(138,100,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(139,101,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(140,102,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(141,103,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(142,104,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(143,105,1434,'Double-Breasted Coat',74.00,1.00,'{\"color\":\"#90EE90\",\"size\":\"30\"}'),(144,106,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(145,107,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(146,108,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(147,109,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(148,110,1439,'Pyramid PU Bracelet',19.00,1.00,'{\"size\":\"5\"}'),(149,111,1438,'Vintage Circle Shades',15.00,1.00,''),(150,112,1438,'Vintage Circle Shades',15.00,1.00,''),(151,113,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(152,114,1437,'Detachable Faux Fur Coat',159.99,1.00,'{\"color\":\"#90EE90\"}'),(153,115,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(154,116,1435,'Studs Rhinestones Bracelet',17.00,1.00,'{\"size\":\"10\"}'),(155,117,1438,'Vintage Circle Shades',15.00,1.00,'{\"color\":\"#ab8094\",\"size\":\"30\"}'),(156,118,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#90EE90\",\"size\":\"30\"}'),(157,119,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(158,120,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(159,121,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(160,122,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(161,123,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(162,124,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(163,125,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(164,126,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(165,127,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(166,128,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(167,129,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(168,130,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(169,131,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(170,132,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(171,133,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(172,134,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(173,135,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(174,136,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(175,137,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(176,138,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(177,139,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(178,140,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(179,141,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(180,142,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(181,143,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(182,144,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(183,145,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(184,146,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(185,147,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(186,148,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(187,149,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(188,150,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(189,151,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(190,152,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(191,153,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(192,154,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(193,155,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(194,156,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(195,157,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(196,158,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(197,159,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(198,160,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(199,161,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(200,162,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(201,163,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(202,164,1434,'Double-Breasted Coat',74.00,8.00,'{\"color\":\"#808080\",\"size\":\"34\"}'),(203,165,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#90EE90\"}'),(204,166,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#90EE90\"}'),(205,167,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#90EE90\"}'),(206,168,1437,'Detachable Faux Fur Coat',159.99,2.00,'{\"color\":\"#90EE90\"}');
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `order_id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `order_created_date` datetime NOT NULL,
  `order_updated_date` datetime NOT NULL,
  `order_status` tinyint(1) NOT NULL,
  `order_sales_tax` decimal(10,2) NOT NULL,
  `order_cart_total` decimal(10,2) NOT NULL,
  `order_shipping_cost` decimal(10,2) NOT NULL,
  `order_shipping_addr1` varchar(256) NOT NULL,
  `order_shipping_addr2` varchar(256) NOT NULL,
  `order_shipping_city` varchar(100) NOT NULL,
  `order_shipping_state` varchar(100) NOT NULL,
  `order_shipping_zip` varchar(100) NOT NULL,
  `order_shipping_country` varchar(100) NOT NULL,
  `order_shipping_method` varchar(256) NOT NULL,
  `order_session_id` varchar(256) NOT NULL,
  `user_pay_method_id` int(10) NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=169 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,13,38.56,'2014-09-29 07:10:35','2014-09-29 07:10:37',1,2.56,36.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','ef52O88WySyHGBrVpm3snVrq6qfxNDuL3efSk225',1),(2,136,342.73,'2014-09-29 07:13:09','2014-09-29 07:13:21',1,22.75,319.98,0.00,'296 S. Merkle rd','','Bexley','Oh','43209','US','','uWrXTxtTfJYcQu1C2OyPmwy5KNmCqUdVtga7n2Yg',1),(3,13,171.37,'2014-09-29 07:23:22','2014-09-29 07:23:24',1,11.38,159.99,0.00,'1120 Sherborne ln','','Poeell','Oh','43065','US','','vyHtqsdNyvQTUAhoM9lU2veqjt5E3i99BTOVyHrU',1),(4,13,36.42,'2014-09-29 07:29:43','2014-09-29 07:29:46',1,2.42,34.00,0.00,'1120 sherborne ln','','Powell','Oh','43065','US','','vyHtqsdNyvQTUAhoM9lU2veqjt5E3i99BTOVyHrU',1),(5,13,20.35,'2014-09-29 21:42:05','2014-09-29 21:44:10',1,1.35,19.00,0.00,'1120 Sherborne ln','','Columbus','Oh','43065','US','','BJAWm3VD4Jhv8u0Zf3j5FVzKo9sJT1alDLbC6D7A',1),(6,9,353.98,'2014-09-30 01:39:48','2014-09-30 01:39:48',0,0.00,353.98,0.00,'123rty','rtyuty','tyyu','tuy','yryui','US','','KXaufBYs9GvZtRxAYwLRdacXebCoSdWcpmkHdp0W',0),(7,9,521.82,'2014-09-30 04:27:55','2014-09-30 04:27:56',1,41.85,479.97,0.00,'ameerpet','a1','hyd','Ok','533120','india','','DKGM0YVjH8fT0shO4tNIRnDiiXBVqlp76ekhyHBu',1),(8,9,73.93,'2014-09-30 04:32:42','2014-09-30 04:32:44',1,5.93,68.00,0.00,'ameerpet','a1','hyd','Ok','533120','india','','8aO5BNypP1yYbJZGpQGJYxLWe2XEdjltoCKtks52',1),(9,9,173.94,'2014-09-30 04:39:16','2014-09-30 04:39:17',1,13.95,159.99,0.00,'ameerpet','a1','hyd','Ok','533120','india','','uoCkdx2gBrHuovZHcUVRzuwkUEmOx4LZulX9s6lD',1),(10,9,695.76,'2014-09-30 05:02:14','2014-09-30 05:02:15',1,55.80,639.96,0.00,'ameerpet','a1','hyd','Ok','533120','india','','kX5stGHZeki6UHp9NWvzkoUHGXHwc7PALzTwMKTv',1),(11,9,92.41,'2014-09-30 05:29:39','2014-09-30 05:29:40',1,7.41,85.00,0.00,'ameerpet','a1','hyd','Ok','533120','india','','m9g7dq4Sdbmofk6a5sFvsdXznqPgR7VFYYGjxRb4',1),(12,13,20.35,'2014-09-30 07:22:43','2014-09-30 07:22:46',1,1.35,19.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','PF7Q6yate1QbcsrxDNFD8NGWdB84yndYlMXq4J9L',1),(13,9,92.41,'2014-10-01 04:06:18','2014-10-01 04:06:20',1,7.41,85.00,0.00,'Ayodhyaramapuram','Dno18-5-20','Samalkot','Ok','533440','India','','GjW3Hxobd5XpZR9zKbYAwRkfD56xjUW3d8El8z9K',1),(14,9,532.30,'2014-10-01 04:28:58','2014-10-01 04:31:18',1,35.33,496.97,0.00,'Ameepet','Adw','Cgf','Oh','123jk','UK','','L5BjmeFP189rgLRET8WbpvHrWxoVjyaSIkwPIH1W',1),(15,9,79.26,'2014-10-01 04:41:24','2014-10-01 04:41:26',1,5.26,74.00,0.00,'Ameepet','Adw','Cgf','Oh','123jk','UK','','Otibqr0IjI73ctU9DWKxdA1gHaanUhSFGFY1kodk',1),(16,9,1054.94,'2014-10-01 04:56:13','2014-10-01 05:04:06',2,0.00,1054.94,0.00,'Sr nagar','Fgg','Hyd','Ol','123fg','US','','dmjNDcgJqWal4FT6YQSYZTJ83L1u6Xnh9lVznuF2',2),(17,9,18.48,'2014-10-01 05:21:06','2014-10-01 05:23:09',2,1.48,17.00,0.00,'Ayodhyaramapuram','Dno18-5-20','Samalkot','Ok','533440','India','','BHIMXLb1bNuG759SL5YKGBWM81nAhktrUJrZd74K',2),(18,9,171.37,'2014-10-01 05:25:37','2014-10-01 05:25:38',1,11.38,159.99,0.00,'Ameepet','Adw','Cgf','Oh','123jk','UK','','BHIMXLb1bNuG759SL5YKGBWM81nAhktrUJrZd74K',1),(19,13,36.42,'2014-10-06 10:37:43','2014-10-06 10:37:47',1,2.42,34.00,0.00,'1215 Polaris PKWay','','Columbus','Oh','43240','US','','Zh5rOqQleAHux6UsK76cnF9yW1LoKUgoPqHykON0',2),(20,9,495.74,'2014-10-08 02:15:02','2014-10-08 02:15:05',1,39.76,455.98,0.00,'Ayodhyaramapuram','Dno18-5-20','Samalkot','Ok','533440','India','','I4rOW0US0Io1dNgCgGnYh5c4hF9DsYGWOpRjhID3',1),(21,9,129.38,'2014-10-08 02:17:54','2014-10-08 02:18:17',1,10.38,119.00,0.00,'Ayodhyaramapuram','Dno18-5-20','Samalkot','Ok','533440','India','','I4rOW0US0Io1dNgCgGnYh5c4hF9DsYGWOpRjhID3',1),(22,9,173.94,'2014-10-08 02:45:25','2014-10-08 02:45:26',1,13.95,159.99,0.00,'Ayodhyaramapuram','Dno18-5-20','Samalkot','Ok','533440','India','','KppwYnR9FX8WwZUmgboHmIrbwIvQJHFQ6AZSkHEI',1),(23,9,20.66,'2014-10-09 02:26:44','2014-10-09 02:26:46',1,1.66,19.00,0.00,'Ayodhyaramapuram','Dno18-5-20','Samalkot','Ok','533440','India','','XzOTEJ6L6PD2wPUyusEZgvkQHPkNagqy6kD3RpAN',2),(24,9,20.35,'2014-10-09 03:17:16','2014-10-09 03:17:18',1,1.35,19.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','jpUeTB0dryTzFOWS1IwI6Xt1qnpZerEq5v2XPkkF',1),(25,9,379.15,'2014-10-09 04:11:10','2014-10-09 05:16:43',2,25.17,353.98,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','UVi0kBRrDtADKNBFZqyOg6uyXhE1lqjiY8kqouiX',2),(26,9,237.78,'2014-10-09 04:49:06','2014-10-09 04:49:07',1,15.78,222.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','zTdhO1eONeopTTIxH3NyyJ8aZQ3Oi86NQfhVBBj7',2),(27,9,173.94,'2014-10-09 05:30:56','2014-10-09 05:30:56',0,13.95,159.99,0.00,'Vh','Gh','Hyd','Ok','533440','UK','','nsv6roS18jfG0ykK53EFcGs01SgO4qUbaz9wajQt',0),(28,9,210.91,'2014-10-10 22:03:40','2014-10-10 22:10:53',1,16.92,193.99,0.00,'Vh','Gh','Hyd','Ok','533440','UK','','fayWe4AT5yb2x09lby3AqDhl3uSlBw4vhOcDZ8Bq',1),(29,13,171.37,'2014-10-12 21:06:06','2014-10-12 21:06:08',1,11.38,159.99,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','yHuCG2ncrzL0m3M11zCzUGQHMKkRNLXaaI5cfZlF',1),(30,9,347.88,'2014-10-12 23:00:30','2014-10-12 23:00:31',1,27.90,319.98,0.00,'Vh','Gh','Hyd','Ok','533440','UK','','fiEace5FxW89euUgwprEgRpvE31D2QjhSzmqi6Mj',1),(31,9,97.47,'2014-10-13 02:59:35','2014-10-13 03:00:03',2,6.47,91.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','wZ1igym5P6eKPnuz0KQ24VVq3q7XEwTvXuFrV5GA',2),(32,9,203.51,'2014-10-13 04:39:43','2014-10-13 04:41:06',2,13.51,190.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','qpvIpWUlb0h1x1Frxt69LcT5bBKHdTQSILICszxQ',2),(33,9,20.35,'2014-10-13 05:27:36','2014-10-13 05:28:05',2,1.35,19.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','KGS7gpTt9ILrXfejaSO11rSelYNruOw5KEzjcSye',2),(34,13,18.21,'2014-10-13 10:03:53','2014-10-13 10:03:57',1,1.21,17.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','BrReSmSEnWECpAunSwLBrXFHd5z18yxQ8KWLN0ro',1),(35,9,36.42,'2014-10-14 01:00:27','2014-10-14 01:00:28',1,2.42,34.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','WBhEmxYoOU42OHGbgkTefVT6RJuXfJE10c18ttNv',1),(36,9,20.35,'2014-10-14 03:41:42','2014-10-14 03:41:44',1,1.35,19.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','Ssy1bdpvdcv95JQYwtBYcXZ2WBa7IkyOiH1gZEnY',1),(37,9,20.35,'2014-10-14 06:05:39','2014-10-14 06:05:40',1,1.35,19.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','XrSLVZv5X2MfFKNRKfo7iVWw3wVb8jHExacD94Oa',2),(38,13,29.99,'2014-10-14 07:28:41','2014-10-14 07:28:42',1,1.99,28.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','Sdqf64h2J2QFrptZ0Aon86vU6pl03l3kB6lX8TxG',1),(39,9,20.35,'2014-10-14 23:10:10','2014-10-14 23:27:37',0,1.35,19.00,0.00,'Hi','Fhhj','Hyd','Oh','Er123','US','','L7Sr7jyRMbLh3PtACku3d68Be5ScaMHhaIUTQxCC',1),(40,9,20.66,'2014-10-15 04:22:49','2014-10-15 04:22:53',1,1.66,19.00,0.00,'ew','sd3','hyd','ok','123er','US','','G6P1anri1Y27S961VlETEgRMtz6vOjwUWkppTHpQ',1),(41,9,20.66,'2014-10-15 21:43:59','2014-10-15 21:44:01',1,1.66,19.00,0.00,'ew','sd3','hyd','ok','123er','US','','OVOclsj3gUVaCWL0MYiWljr4cWfObrh1hPUOFeGw',1),(42,9,20.66,'2014-10-15 22:28:03','2014-10-15 22:28:05',1,1.66,19.00,0.00,'ew','sd3','hyd','ok','123er','US','','C2D7a5sEl1LXkGA52KsHbCwOhpNRN9col6tma8iR',1),(43,9,41.31,'2014-10-24 22:49:04','2014-10-24 22:49:09',1,3.31,38.00,0.00,'ew','sd3','hyd','ok','123er','US','','Zp8PGEUqGk4HW9sA93iFKmBEG46d0Tqwyb2XSZKt',1),(44,9,20.35,'2014-11-04 22:52:25','2014-11-04 22:52:25',0,1.35,19.00,0.00,'Jhf','Hhh','Jj','Oh','Fjhgjj','US','','nQDF3wdfvKnGHOMVt90yyOeuvv6ayIWHVt44lFSz',0),(45,9,18.21,'2014-11-05 01:47:59','2014-11-05 01:47:59',0,1.21,17.00,0.00,'Jhf','Hhh','Jj','Oh','Fjhgjj','US','','y3y4oJc6XrZKfAXbY8q6K0lNdupcwkENPcxEeLFD',0),(46,13,20.35,'2014-11-20 09:11:48','2014-11-20 09:11:50',1,1.35,19.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','Gv0iI0Aed7mGWf4ge0YXz5VJtmjqwEnhWGHt3j5O',1),(47,13,18.21,'2014-11-20 10:18:11','2014-11-20 10:18:11',0,1.21,17.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','t3yKqbgJ0LwFYavQShuNvgW04JknVOBAQhEXUeeS',0),(48,9,16.31,'2014-12-08 22:23:30','2014-12-08 22:23:31',1,1.31,15.00,0.00,'ew','sd31','hyd','ok','123er','US','','aejmsap60YpsmGg9acdgx1P6w36kZDOxEyUshG89',1),(49,9,20.66,'2014-12-08 22:36:48','2014-12-08 22:36:50',1,1.66,19.00,0.00,'ew','sd31','hyd','ok','123er','US','','yqJd4FDE8E8mWL3v3tV81rcVDj7MGz8biaHzJr1w',1),(50,9,101.11,'2014-12-09 00:18:11','2014-12-09 00:18:12',1,8.11,93.00,0.00,'ew','sd31','hyd','ok','123er','US','','Jj3sH2iOnTRek7YPJUtGrKQ6IdCjm2ZrcRjpNSGt',1),(51,9,101.11,'2014-12-09 00:21:39','2014-12-09 00:21:40',1,8.11,93.00,0.00,'ew','sd31','hyd','ok','123er','US','','vgGciNb65gIwlLrOfxCZx2BtDj85lQF9QNJUKNND',1),(52,9,101.11,'2014-12-09 00:43:18','2014-12-09 00:43:20',1,8.11,93.00,0.00,'ew','sd31','hyd','ok','123er','US','','Fgpodl56YKS7sQlyTivrZXxIycr337UUWJG1YCMo',1),(53,9,101.11,'2014-12-09 00:46:56','2014-12-09 00:46:58',1,8.11,93.00,0.00,'ew','sd31','hyd','ok','123er','US','','Fgpodl56YKS7sQlyTivrZXxIycr337UUWJG1YCMo',1),(54,9,101.11,'2014-12-09 00:47:29','2014-12-09 00:47:30',1,8.11,93.00,0.00,'ew','sd31','hyd','ok','123er','US','','Fgpodl56YKS7sQlyTivrZXxIycr337UUWJG1YCMo',1),(55,9,80.45,'2014-12-09 01:43:39','2014-12-09 01:43:40',1,6.45,74.00,0.00,'ew','sd31','hyd','ok','123er','US','','v82xJZq5QGp2cbhxj2gLZtYoyfXNC5etbqn2oSBs',1),(56,9,20.66,'2014-12-09 01:48:17','2014-12-09 01:48:23',1,1.66,19.00,0.00,'ew','sd31','hyd','ok','123er','US','','aTNUAPCQOTQrD4aCJtcLQrraLD1NppoRB6EfuRBf',1),(57,9,20.66,'2014-12-09 01:49:07','2014-12-09 01:52:13',1,1.66,19.00,0.00,'ew','sd31','hyd','ok','123er','US','','aTNUAPCQOTQrD4aCJtcLQrraLD1NppoRB6EfuRBf',2),(58,9,181.56,'2014-12-09 01:51:46','2014-12-09 01:51:49',1,14.56,167.00,0.00,'ew','sd31','hyd','ok','123er','US','','aTNUAPCQOTQrD4aCJtcLQrraLD1NppoRB6EfuRBf',2),(59,9,181.56,'2014-12-09 01:53:30','2014-12-09 01:53:35',1,14.56,167.00,0.00,'ew','sd31','hyd','ok','123er','US','','aTNUAPCQOTQrD4aCJtcLQrraLD1NppoRB6EfuRBf',1),(60,9,181.56,'2014-12-09 01:55:41','2014-12-09 01:58:18',1,14.56,167.00,0.00,'ew','sd31','hyd','ok','123er','US','','aTNUAPCQOTQrD4aCJtcLQrraLD1NppoRB6EfuRBf',1),(61,9,181.56,'2014-12-09 02:02:56','2014-12-09 02:03:00',1,14.56,167.00,0.00,'ew','sd31','hyd','ok','123er','US','','6YK5cWhvF1d1sOBMnRZmZ6bxvZGkQa9hpGX5F6YJ',1),(62,9,20.35,'2014-12-09 03:30:13','2014-12-09 03:31:43',1,1.35,19.00,0.00,'ew','sd31','hyd','Oh','123er','US','','aVgiyYWuaBZnE3Kp3wqG3WfTtbvVNMipA4Ueelg9',1),(63,9,79.26,'2014-12-09 03:33:01','2014-12-09 03:33:02',1,5.26,74.00,0.00,'ew','sd31','hyd','Oh','123er','US','','aVgiyYWuaBZnE3Kp3wqG3WfTtbvVNMipA4Ueelg9',1),(64,9,99.61,'2014-12-09 04:23:09','2014-12-09 04:23:11',1,6.61,93.00,0.00,'ew','sd31','hyd','Oh','123er','US','','NUfnSLk5QT0z0tw7AVSTtFNtGY7y2al6CgMJrKYw',1),(65,9,79.26,'2014-12-09 04:39:05','2014-12-09 04:39:08',1,5.26,74.00,0.00,'ew','sd31','hyd','Oh','123er','US','','kAUlhkzZlPPlSe4aPCW7vmOLPw5R0KpvSWzKwzzV',1),(66,9,20.35,'2014-12-09 05:14:21','2014-12-09 05:14:22',1,1.35,19.00,0.00,'ew','sd31','hyd','Oh','123er','US','','BW8fSn0ar7VQyZOwdy1pwQl2H9yuPlXOu2n5Qxhz',1),(67,9,79.26,'2014-12-09 05:53:12','2014-12-09 05:53:14',1,5.26,74.00,0.00,'ew','sd31','hyd','Oh','123er','US','','aRHqIsjci1fWOfqAvooro08s3LzwLfpqKjDBZ6VN',1),(68,9,20.35,'2014-12-09 21:54:54','2014-12-09 21:54:56',1,1.35,19.00,0.00,'ew','sd31','hyd','Oh','123er','US','','frhtsKrQ57ONBPbEJshNp61W46dIfRNn12F8s1br',1),(69,9,20.35,'2014-12-09 22:01:22','2014-12-09 22:01:24',1,1.35,19.00,0.00,'ew','sd31','hyd','Oh','123er','US','','7vL4TiMC80XC1Js68Bcs6zLTnKt6Z3Ses1eBn8nU',1),(70,9,79.26,'2014-12-09 22:16:00','2014-12-09 22:16:01',1,5.26,74.00,0.00,'ew','sd31','hyd','Oh','123er','US','','qZcdGSRUHmnxjhjudKtQ1xkR7kKJVcC0Z6rOLZCO',1),(71,9,99.61,'2014-12-09 22:17:19','2014-12-09 22:17:20',1,6.61,93.00,0.00,'ew','sd31','hyd','Oh','123er','US','','qZcdGSRUHmnxjhjudKtQ1xkR7kKJVcC0Z6rOLZCO',1),(72,9,16.07,'2014-12-09 22:26:53','2014-12-09 22:26:56',1,1.07,15.00,0.00,'ew','sd31','hyd','Oh','123er','US','','dUQd51EeVJUQpIGgv2oavdOeyjaztgJPGBnRwNh4',1),(73,9,20.35,'2014-12-09 22:49:31','2014-12-09 22:49:33',1,1.35,19.00,0.00,'ew','sd31','hyd','Oh','123er','US','','oxCQUo0c2v6m88Nm2RvG9iqeuYeKExkuoVRM56vL',1),(74,9,79.26,'2014-12-09 22:50:20','2014-12-09 22:50:21',1,5.26,74.00,0.00,'ew','sd31','hyd','Oh','123er','US','','oxCQUo0c2v6m88Nm2RvG9iqeuYeKExkuoVRM56vL',1),(75,9,38.56,'2014-12-10 02:47:52','2014-12-10 02:49:05',1,2.56,36.00,0.00,'ew','sd31','hyd','Oh','123er','US','','ZJssh4oQlfREfhBZNAT6lJDeeioLgNVAmq7AM0wU',1),(76,9,79.26,'2014-12-10 02:56:46','2014-12-10 03:03:12',2,5.26,74.00,0.00,'ew','sd31','hyd','Oh','123','US','','HqTIxJTlm6qsvWmFPirckh5z49LpHuq8pNhGM1sb',2),(77,9,16.07,'2014-12-10 03:02:27','2014-12-10 03:02:30',1,1.07,15.00,0.00,'ew','sd31','hyd','Oh','123','US','','HqTIxJTlm6qsvWmFPirckh5z49LpHuq8pNhGM1sb',1),(78,13,18.21,'2014-12-10 11:02:52','2014-12-10 11:02:55',1,1.21,17.00,0.00,'1120 sherborne ln','','Powell','Oh ','43043','US','','FtpDfzBxbAtc7NOVVmpMx2tmPM1vgRK5t5h0OPab',1),(79,6,489.62,'2014-12-11 05:07:19','2014-12-11 05:07:29',1,26.62,463.00,0.00,'aftrg','','city6','Dc','sd','US','','tC9RIF7jqA5EnK7ssnNPTj5qWBFqBBRWGV90TMFc',1),(80,6,519.68,'2014-12-11 05:25:16','2014-12-11 05:25:17',1,41.68,478.00,0.00,'ad6','','ciyu789','Ok','yygt','US','','hbWujFA12MS88NcVOMBu0Ox6f9WBtZIqdjDpuFuC',1),(81,6,519.68,'2014-12-11 05:25:53','2014-12-11 05:25:54',1,41.68,478.00,0.00,'ad6','','ciyu789','Ok','yygt','US','','hbWujFA12MS88NcVOMBu0Ox6f9WBtZIqdjDpuFuC',1),(82,6,78.26,'2014-12-14 21:52:21','2014-12-14 21:52:30',1,4.26,74.00,0.00,'aftrg','','city6','Dc','sd','US','','GHI22aukVC6beSgOglM9AQuap4bVEIgQN8XKZ1si',1),(83,6,61.97,'2014-12-15 00:32:49','2014-12-15 00:32:52',1,4.97,57.00,0.00,'ad6','','ciyu789','Ok','yygt','US','','kTlAKVeIFHbV9YiCJOeFgCsziUuuRLbMLYy5r5Jn',1),(84,6,38.00,'2014-12-15 03:18:03','2014-12-15 03:18:06',1,0.00,38.00,0.00,'aftrg','','city6','OL','sd','US','','xzuSbWFqdmRpDs7htrsg2Jor4gIoJaCy2bOO7GJx',1),(85,6,163.00,'2014-12-15 03:21:20','2014-12-15 03:21:21',1,0.00,163.00,0.00,'aftrg','','city6','OL','sd','US','','xzuSbWFqdmRpDs7htrsg2Jor4gIoJaCy2bOO7GJx',1),(86,6,183.29,'2014-12-15 03:32:38','2014-12-15 03:32:39',1,12.29,171.00,0.00,'hi','ddd','dd','MN','9890MN','US','','xhus895oFPAwxm1dqLxfZYtdBzjbekgn5vxm6bDk',1),(87,6,80.03,'2014-12-15 05:17:25','2014-12-15 05:17:27',1,6.03,74.00,0.00,'add1','addrs2','Texas','TX','tx90','US','','uV3r4ShHHEtbVbOIfkDv1gf9RgPGFQaIriRt28gz',1),(88,6,422.92,'2014-12-15 23:17:53','2014-12-15 23:27:24',2,33.92,389.00,0.00,'add2','aaa','city3','OK','ok89','US','','8NtQLTogZC47yCItpBB6avdlyIwVqEo24YuBWGpo',2),(89,9,203.51,'2014-12-15 23:18:47','2014-12-15 23:18:50',1,13.51,190.00,0.00,'ew','sd31','hyd','Oh','123','US','','jnaxj5jLi2cKuJJigDBuJLX8OAzsu1rSWS02Sjx4',1),(90,9,138.17,'2014-12-15 23:22:00','2014-12-15 23:22:41',1,9.17,129.00,0.00,'ew','sd31','hyd','Oh','123','US','','jnaxj5jLi2cKuJJigDBuJLX8OAzsu1rSWS02Sjx4',1),(91,6,64.89,'2014-12-15 23:32:55','2014-12-15 23:34:42',2,4.89,60.00,0.00,'add1','addrs2','Texas','TX','tx90','US','','VvMltpkuv4alh282sfGP498alPLmU0aU0ouDuz0I',2),(92,6,78.26,'2014-12-15 23:59:23','2014-12-15 23:59:44',1,4.26,74.00,0.00,'kukatpally','','hyderabad','DC','dc455','US','','VP1Tl8hXf17DkKMvRWnufjNldxMxstlBs3ilWGT1',1),(93,6,20.09,'2014-12-16 00:41:39','2014-12-16 00:41:41',1,1.09,19.00,0.00,'add3','ff','hyd','Dc','dc456','Uk','','aDvCGk6x1X29cikaPtaAYH73r04NwYKvMTiDLxeN',1),(94,6,20.09,'2014-12-17 02:07:40','2014-12-17 02:08:09',2,1.09,19.00,0.00,'add1','add2','city1','Dc','dc66','US','','YzB2xr9p0fPh5iT1vkwbeZ0k6zuoJcDll0LX97yF',2),(95,6,98.35,'2014-12-17 02:09:00','2014-12-17 02:10:13',2,5.35,93.00,0.00,'add1','add2','city1','Dc','dc66','US','','YzB2xr9p0fPh5iT1vkwbeZ0k6zuoJcDll0LX97yF',2),(96,9,20.35,'2014-12-21 23:18:56','2014-12-21 23:18:58',1,1.35,19.00,0.00,'ew','sd31','hyd','Oh','123','US','','zBWmF4Y5omjc67O01q8Ao9361GaReibTelCCvhCv',1),(97,9,190.66,'2014-12-22 00:31:01','2014-12-22 00:31:02',1,12.66,178.00,0.00,'ew','sd31','hyd','Oh','123','US','','DxKxr4PPNc9rGGmbf7dFxwZv4FPob6tLy4mUDKla',1),(98,9,16.07,'2015-01-06 05:39:44','2015-01-06 05:41:04',1,1.07,15.00,0.00,'ew','sd31','hyd','Oh','123','US','','rjkVtsKCsfFx35wozRjaa47kvQMQ5s9yYAEOI5Ko',1),(99,9,160.67,'2015-01-06 05:53:16','2015-01-06 05:53:16',0,10.67,150.00,0.00,'ew','sd31','hyd','Oh','123','US','','yWwCxI6Skkg01Gyo4QzFylonx8P7rVxl64v0a9NB',0),(100,9,16.07,'2015-01-12 07:02:15','2015-01-12 07:02:15',0,1.07,15.00,0.00,'Add1','','Col','OH','123','US','','drqnEJtDZOKcV9li0Fl55DfWpyEec4T1ogi8KNVf',0),(101,9,16.22,'2015-01-20 01:06:43','2015-01-20 01:08:42',2,1.22,15.00,0.00,'Schumburg','bishop ct hub','chicago','IL','60194','USA','null','Jl3Ezrw5yHZCJkGkbjHpfzAEy60OKOW47lQcrp6P',2),(102,9,16.22,'2015-01-20 01:06:43','2015-01-20 01:08:42',2,1.22,15.00,0.00,'Schumburg','bishop ct hub','chicago','IL','60194','USA','null','Jl3Ezrw5yHZCJkGkbjHpfzAEy60OKOW47lQcrp6P',2),(103,9,16.22,'2015-01-20 01:06:43','2015-01-20 01:06:43',0,1.22,15.00,0.00,'Schumburg','bishop ct hub','chicago','IL','60194','USA','null','UDaBlCmqyULFLKt1GdjiFYYlA1VodhRsxYozSmjE',0),(104,9,16.22,'2015-01-20 01:06:43','2015-01-20 01:06:43',0,1.22,15.00,0.00,'Schumburg','bishop ct hub','chicago','IL','60194','USA','null','UDaBlCmqyULFLKt1GdjiFYYlA1VodhRsxYozSmjE',0),(105,9,80.04,'2015-01-20 05:54:53','2015-01-20 05:54:57',1,6.04,74.00,0.00,'Schumburg','bishop ct','chicago','IL','60194','USA','null','saT8hNDVQgDeoBPkKT62JtsuWCyel8lDz4V2p11p',0),(106,9,20.35,'2015-01-21 23:38:31','2015-01-21 23:38:31',0,1.35,19.00,0.00,'Add1','','Col','OH','1234','US','null','9gFjGE9iaBjxI1KDET4F7VANcmn44jP54TT7rRJS',0),(107,9,20.35,'2015-01-21 23:38:40','2015-01-21 23:38:40',0,1.35,19.00,0.00,'Add1','','Col','OH','1234','US','','Y6QKUeXkQIZf14096VcJ87So7cVxdnCZH1iGkBPa',0),(108,9,20.35,'2015-01-21 23:41:33','2015-01-21 23:41:33',0,1.35,19.00,0.00,'Add1','','Col','OH','1234','US','','Y6QKUeXkQIZf14096VcJ87So7cVxdnCZH1iGkBPa',0),(109,9,20.35,'2015-01-21 23:41:39','2015-01-21 23:41:39',0,1.35,19.00,0.00,'Add1','','Col','OH','1234','US','null','cB713YJ2ZAhLKBaaIUsqGHZSFfunb3T2U9bvvqSB',0),(110,9,20.35,'2015-01-21 23:41:40','2015-01-21 23:41:40',0,1.35,19.00,0.00,'Add1','','Col','OH','1234','US','null','f5N1eJx4TDIYSGH5xTqH7PRAVSJ8bX9NDxE4ZgVq',0),(111,9,16.07,'2015-01-29 04:34:10','2015-01-29 04:34:10',0,1.07,15.00,0.00,'','','','','','','null','3uXhdudDSYd84CgGCTgdQ0EuUwcNAozJiSMdduyt',0),(112,9,16.07,'2015-01-29 04:34:13','2015-01-29 04:34:13',0,1.07,15.00,0.00,'','','','','','','null','9OFyINoLGRfx1DnS6fkuERkko7TckkJqdZqPQ4Vu',0),(113,10,16.28,'2015-01-31 04:44:09','2015-01-31 04:44:09',0,1.28,15.00,0.00,'Addr','','col','AL','1234','US','null','9SZJsw22t3xGOBijzfERDM5XVbLOmqhNab4Rn1Mc',0),(114,10,173.61,'2015-01-31 04:45:51','2015-01-31 04:45:51',0,13.62,159.99,0.00,'Addr','','col','AL','1234','US','null','ccIaNDy2IAwlufZ61fGJGY5YkppIXGrX3eY2rCj0',0),(115,10,18.45,'2015-01-31 05:02:33','2015-01-31 05:02:33',0,1.45,17.00,0.00,'Addr','','col','AL','1234','US','null','uDknX7Y8pPPbfv9KlITDg0vKJN345B4y5qNXlDOu',0),(116,10,18.45,'2015-01-31 05:02:33','2015-01-31 05:02:33',0,1.45,17.00,0.00,'Addr','','col','AL','1234','US','null','uDknX7Y8pPPbfv9KlITDg0vKJN345B4y5qNXlDOu',0),(117,9,16.07,'2015-02-05 04:23:02','2015-02-05 04:23:31',0,1.07,15.00,0.00,'test','address','city','Oh','88390','US','null','uwnwUvCGDGVw4R5Uj2Ee9rsGaXQgxcZYwPNSnLxk',0),(118,9,634.09,'2015-02-05 05:35:24','2015-02-09 12:41:50',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','EFyHfmE1XoZr0WCHdnJAMaRK2wjm5eB82TPJpOww',0),(119,9,634.09,'2015-02-09 12:48:11','2015-02-09 12:48:11',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','sBUanUx4Mkyh5kyRopCjYivwiRYj7kW5UlXgjbEP',0),(120,9,634.09,'2015-02-09 12:48:11','2015-02-09 12:48:11',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','sBUanUx4Mkyh5kyRopCjYivwiRYj7kW5UlXgjbEP',0),(121,9,634.09,'2015-02-09 12:48:11','2015-02-09 12:48:11',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','sBUanUx4Mkyh5kyRopCjYivwiRYj7kW5UlXgjbEP',0),(122,9,634.09,'2015-02-09 12:48:11','2015-02-09 12:48:11',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','sBUanUx4Mkyh5kyRopCjYivwiRYj7kW5UlXgjbEP',0),(123,9,634.09,'2015-02-09 12:48:11','2015-02-09 12:48:11',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','sBUanUx4Mkyh5kyRopCjYivwiRYj7kW5UlXgjbEP',0),(124,9,634.09,'2015-02-09 12:48:11','2015-02-09 12:48:11',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','sBUanUx4Mkyh5kyRopCjYivwiRYj7kW5UlXgjbEP',0),(125,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(126,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(127,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(128,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(129,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(130,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(131,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(132,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(133,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(134,9,634.09,'2015-02-09 13:06:13','2015-02-09 13:06:13',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','IN2eQmKxcpANM8JAPpwXJI1UEIAWVU2Eh3M6c3Jm',0),(135,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(136,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(137,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(138,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(139,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(140,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(141,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(142,9,634.09,'2015-02-09 13:15:04','2015-02-09 13:15:04',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','CgEqIGczT66Q3p2ZGDqAigwErPtIwA6GNL6PZXaj',0),(143,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(144,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(145,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(146,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(147,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(148,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(149,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(150,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(151,9,634.09,'2015-02-09 13:21:24','2015-02-09 13:21:24',0,42.09,592.00,0.00,'test','address','city','Oh','88390','US','null','A33TwEPXP1xvfV0AnNZ8irmz4fxNZnna46DJJrfM',0),(152,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(153,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(154,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(155,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(156,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(157,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(158,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(159,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(160,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(161,9,643.62,'2015-02-09 13:21:48','2015-02-09 13:21:48',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','EpEimfuF3lVCDIJuCvmD00v8bXnDtQHOvroBNTB6',0),(162,9,643.62,'2015-02-09 13:22:29','2015-02-09 13:22:29',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','2SN5MDsihedorwwgvcEbLeAjAPgmCfImIVqxau57',0),(163,9,643.62,'2015-02-09 13:22:29','2015-02-09 13:22:29',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','2SN5MDsihedorwwgvcEbLeAjAPgmCfImIVqxau57',0),(164,9,643.62,'2015-02-09 13:22:29','2015-02-09 13:22:50',0,51.62,592.00,0.00,'schumburg','bishop ct','Chicago','Ok','1234','US','null','BuyDFAWGsufnweoJD4qynzkmYAejGoqomzhbvjtt',0),(165,6,347.21,'2015-02-23 01:09:34','2015-02-23 01:09:34',0,27.23,319.98,0.00,'Arizona','block c','Arozon','AL','75768','US','null','JDzmLv0lGj5vRNWIh69m0gfhalfDCOPfcRFGnpN8',0),(166,6,347.21,'2015-02-23 01:09:34','2015-02-23 01:09:34',0,27.23,319.98,0.00,'Arizona','block c','Arozon','AL','75768','US','null','JDzmLv0lGj5vRNWIh69m0gfhalfDCOPfcRFGnpN8',0),(167,6,347.21,'2015-02-23 01:09:34','2015-02-23 01:09:34',0,27.23,319.98,0.00,'Arizona','block c','Arozon','AL','75768','US','null','JDzmLv0lGj5vRNWIh69m0gfhalfDCOPfcRFGnpN8',0),(168,6,347.21,'2015-02-23 01:09:34','2015-02-23 01:09:34',0,27.23,319.98,0.00,'Arizona','block c','Arozon','AL','75768','US','null','JDzmLv0lGj5vRNWIh69m0gfhalfDCOPfcRFGnpN8',0);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_invoice`
--

DROP TABLE IF EXISTS `payment_invoice`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_invoice` (
  `pay_invoice_id` int(10) NOT NULL AUTO_INCREMENT,
  `pay_inv_number` varchar(100) NOT NULL,
  `pay_inv_date` datetime NOT NULL,
  `order_id` int(10) NOT NULL,
  `pay_inv_status` tinyint(1) NOT NULL,
  `user_id` int(10) NOT NULL,
  PRIMARY KEY (`pay_invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_invoice`
--

LOCK TABLES `payment_invoice` WRITE;
/*!40000 ALTER TABLE `payment_invoice` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_invoice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_methods`
--

DROP TABLE IF EXISTS `payment_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_methods` (
  `pay_method_id` int(10) NOT NULL AUTO_INCREMENT,
  `pay_method_name` varchar(256) NOT NULL,
  PRIMARY KEY (`pay_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_methods`
--

LOCK TABLES `payment_methods` WRITE;
/*!40000 ALTER TABLE `payment_methods` DISABLE KEYS */;
INSERT INTO `payment_methods` VALUES (1,'Save Order'),(2,'Paypal');
/*!40000 ALTER TABLE `payment_methods` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_attrib`
--

DROP TABLE IF EXISTS `prod_attrib`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_attrib` (
  `attrib_id` int(10) NOT NULL AUTO_INCREMENT,
  `attrib_name` varchar(200) NOT NULL,
  PRIMARY KEY (`attrib_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_attrib`
--

LOCK TABLES `prod_attrib` WRITE;
/*!40000 ALTER TABLE `prod_attrib` DISABLE KEYS */;
INSERT INTO `prod_attrib` VALUES (1,'Size'),(2,'Color');
/*!40000 ALTER TABLE `prod_attrib` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_cat`
--

DROP TABLE IF EXISTS `prod_cat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_cat` (
  `cat_id` int(10) NOT NULL AUTO_INCREMENT,
  `cat_name` text,
  `cat_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cat_id`),
  FULLTEXT KEY `cat_name` (`cat_name`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_cat`
--

LOCK TABLES `prod_cat` WRITE;
/*!40000 ALTER TABLE `prod_cat` DISABLE KEYS */;
INSERT INTO `prod_cat` VALUES (1,'Men',1),(2,'Women',1),(3,'T-Shirt',1),(4,'Jeans',1),(5,'Tops',1),(6,'Shoes',1),(7,'Accessories',1),(8,'Men Jeans',1);
/*!40000 ALTER TABLE `prod_cat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_cat_ref`
--

DROP TABLE IF EXISTS `prod_cat_ref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_cat_ref` (
  `prod_cat_ref_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `cat_id` int(10) NOT NULL,
  `prod_status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`prod_cat_ref_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_cat_ref`
--

LOCK TABLES `prod_cat_ref` WRITE;
/*!40000 ALTER TABLE `prod_cat_ref` DISABLE KEYS */;
INSERT INTO `prod_cat_ref` VALUES (1,1434,4,1),(2,1439,6,1),(3,1438,4,1),(4,1435,7,1),(5,1437,2,1);
/*!40000 ALTER TABLE `prod_cat_ref` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prod_tags`
--

DROP TABLE IF EXISTS `prod_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prod_tags` (
  `prod_tag_id` int(10) NOT NULL AUTO_INCREMENT,
  `prod_id` int(10) NOT NULL,
  `prod_tag_name` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`prod_tag_id`),
  FULLTEXT KEY `prod_tag_name` (`prod_tag_name`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prod_tags`
--

LOCK TABLES `prod_tags` WRITE;
/*!40000 ALTER TABLE `prod_tags` DISABLE KEYS */;
INSERT INTO `prod_tags` VALUES (1,1434,'Women'),(2,1434,'Jeans'),(3,1439,'Shoes'),(4,1439,'Women'),(5,1438,'Accessories'),(6,1438,'Men'),(7,1435,'Accessories'),(8,1437,'Women'),(9,1433,'Women'),(11,1432,'Accessories'),(12,1440,'Women');
/*!40000 ALTER TABLE `prod_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shipping_methods` (
  `ship_method_id` int(10) NOT NULL AUTO_INCREMENT,
  `ship_method_name` varchar(256) NOT NULL,
  `ship_method_details` varchar(256) NOT NULL,
  `ship_method_rate` varchar(100) NOT NULL,
  PRIMARY KEY (`ship_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_methods`
--

LOCK TABLES `shipping_methods` WRITE;
/*!40000 ALTER TABLE `shipping_methods` DISABLE KEYS */;
INSERT INTO `shipping_methods` VALUES (1,'General Shipping','5-6 Business Days','{\"P\":3}'),(2,'Express Shipping','3-5 Business Days','{\"A\":20.00}'),(3,'Overnight Shipping','1-2 Business Days','{\"P\":2}');
/*!40000 ALTER TABLE `shipping_methods` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-11  3:06:36
