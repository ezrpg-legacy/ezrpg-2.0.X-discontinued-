-- MySQL dump 10.13  Distrib 5.5.31, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ezrpg
-- ------------------------------------------------------
-- Server version	5.5.31-0+wheezy1

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
-- Table structure for table `attribute`
--

DROP TABLE IF EXISTS `attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attribute`
--

LOCK TABLES `attribute` WRITE;
/*!40000 ALTER TABLE `attribute` DISABLE KEYS */;
INSERT INTO `attribute` VALUES (1,'Strength'),(2,'Dexterity'),(3,'Endurance'),(4,'Intelligence'),(5,'Education'),(6,'Social Standing');
/*!40000 ALTER TABLE `attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module`
--

DROP TABLE IF EXISTS `module`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module`
--

LOCK TABLES `module` WRITE;
/*!40000 ALTER TABLE `module` DISABLE KEYS */;
INSERT INTO `module` VALUES (1,'Index'),(2,'Home'),(3,'Login'),(4,'Register');
/*!40000 ALTER TABLE `module` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_action`
--

DROP TABLE IF EXISTS `module_action`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `module_action` (
  `id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `title` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_action`
--

LOCK TABLES `module_action` WRITE;
/*!40000 ALTER TABLE `module_action` DISABLE KEYS */;
/*!40000 ALTER TABLE `module_action` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission`
--

DROP TABLE IF EXISTS `permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  `type` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission`
--

LOCK TABLES `permission` WRITE;
/*!40000 ALTER TABLE `permission` DISABLE KEYS */;
INSERT INTO `permission` VALUES (1,'hello',1),(2,'kitty',1),(4,'index',2),(5,'register',2),(6,'installer',2);
/*!40000 ALTER TABLE `permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permission_type`
--

DROP TABLE IF EXISTS `permission_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permission_type` (
  `id` int(11) NOT NULL,
  `title` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permission_type`
--

LOCK TABLES `permission_type` WRITE;
/*!40000 ALTER TABLE `permission_type` DISABLE KEYS */;
INSERT INTO `permission_type` VALUES (1,'crud'),(2,'route');
/*!40000 ALTER TABLE `permission_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Player ID',
  `title` varchar(20) DEFAULT NULL COMMENT 'Player name/alias',
  `username` varchar(40) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `salt` text NOT NULL,
  `lastActive` datetime DEFAULT NULL COMMENT 'When player was last active',
  `registered` datetime DEFAULT NULL COMMENT 'When the playered registered',
  `active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether the player is active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player`
--

LOCK TABLES `player` WRITE;
/*!40000 ALTER TABLE `player` DISABLE KEYS */;
INSERT INTO `player` VALUES (1,'Nand','nand','ferdi@nand.co.za','','','0000-00-00 00:00:00','0000-00-00 00:00:00',1),(2,'Nand','nand','nand@nand.co.za','$2a$11$ygzfDyJXDybXwLkkPKsRMevEyW7hDof0L4gh2kdWL7acEu.GNsA92','$2a$11$ygzfDyJXDybXwLkkPKsRMe','2013-07-06 22:18:09','2013-07-06 22:18:09',0),(3,'Nand','nand','nand@nand.co.za','$2a$11$63WOv.iJJKjbezqtzUQR/OgHa0GsfYlCZv6VfG1t5fNSV653Tv0b.','$2a$11$63WOv.iJJKjbezqtzUQR/O','2013-07-06 22:19:38','2013-07-06 22:19:38',0),(4,'Nand','nand','ferdi@nand.co.za','$2a$11$R8r8WrFc4gFRkcnWJq84DeMCG8kAIMwpanvr23Xfj8Ogn/4ic9Eoe','$2a$11$R8r8WrFc4gFRkcnWJq84De','2013-07-06 22:21:43','2013-07-06 22:21:43',0),(5,'Nand','nand','ferdi@nand.co.za','$2a$11$PY1/aXYyW2lbAya6uOZGJe4ipjpXAhrtEm9yh/kckaIXMFitYm2XG','$2a$11$PY1/aXYyW2lbAya6uOZGJe','2013-07-06 22:24:05','2013-07-06 22:24:05',0),(6,'Nand','nand','ferdi@nand.co.za','$2a$11$RvXd5lU1ErZosVdWYJ/dM.9snMpW7YrDk7wbgXGGUNi/soOp.nFWq','$2a$11$RvXd5lU1ErZosVdWYJ/dM.','2013-07-06 22:24:08','2013-07-06 22:24:08',0),(7,'Nand','nand','ferdi@nand.co.za','$2a$11$GCJkbb2o8HM16JrxYUbL5OrPib7clhd.ffrSfxN.DqD1wC.oZV5hO','$2a$11$GCJkbb2o8HM16JrxYUbL5O','2013-07-06 22:24:09','2013-07-06 22:24:09',0),(8,'Nand','nand','ferdi@nand.co.za','$2a$11$7ycZt7jHApyiV59LW.92KuAgZTceD/WqH1o1vyg015EbaXZyFJ93G','$2a$11$7ycZt7jHApyiV59LW.92Ku','2013-07-06 22:24:09','2013-07-06 22:24:09',0),(9,'Nand','nand','ferdi@nand.co.za','$2a$11$yXXSkIakf/ymKZO54ZawX.WfoBD6C2O0LkGni4eQcoTPS3UQTIoqK','$2a$11$yXXSkIakf/ymKZO54ZawX.','2013-07-06 22:24:10','2013-07-06 22:24:10',0),(10,'Nand','nand','ferdi@nand.co.za','$2a$11$UcUuDD3DZD2gL4ryyneGoOY129FCZHMhaq2I5qCeB8pkBYwmVLR/G','$2a$11$UcUuDD3DZD2gL4ryyneGoO','2013-07-06 22:24:10','2013-07-06 22:24:10',0),(11,'Nand','nand','ferdi@nand.co.za','$2a$11$214EodaHno4kds6pY2bHcuhu//x.txUuGrJe5hd9rLfrc1Jq7rgKi','$2a$11$214EodaHno4kds6pY2bHcu','2013-07-06 22:24:22','2013-07-06 22:24:22',0),(12,'Nand','nand','ferdi@nand.co.za','$2a$11$7pVRxOLJk0zA81ICO.4x5.CsKaQUgxAx5QvfQzr6xWdpPfVQRTgq6','$2a$11$7pVRxOLJk0zA81ICO.4x5.','2013-07-06 22:24:30','2013-07-06 22:24:30',0),(13,'Nand','nand','ferdi@nand.co.za','$2a$11$rMpd2vJLQrLvfhYD3gKK.OJFQ7k.fr/pkBo1CHcbHN2QeqCmHqMse','$2a$11$rMpd2vJLQrLvfhYD3gKK.O','2013-07-06 22:24:32','2013-07-06 22:24:32',0),(14,'Nand','nand','ferdi@nand.co.za','$2a$11$HkDeUlsLrofbmDvgd7QWxO5ibMvkqyi2m.X.SWNdcMgCdBQxGHQYa','$2a$11$HkDeUlsLrofbmDvgd7QWxO','2013-07-06 22:24:34','2013-07-06 22:24:34',0),(15,'Nand','nand','ferdi@nand.co.za','$2a$11$QjIY/lW4InvODc6xF9Itge1RX35HqG.Re7f6GR14G9jD3gmM38DwC','$2a$11$QjIY/lW4InvODc6xF9Itge','2013-07-06 22:24:35','2013-07-06 22:24:35',0),(16,'Nand','nand','ferdi@nand.co.za','$2a$11$Y2JAQ5G1EarT83xUTQ4AZe5Xj9xTtTnB5.6fXmmG3XwGa5Di9EquK','$2a$11$Y2JAQ5G1EarT83xUTQ4AZe','2013-07-06 22:24:36','2013-07-06 22:24:36',0),(17,'Miggie','miggie','miggie@mig.net','$2a$11$3hGwFnD188HHIz4eNkh1wO9NJAPRgUQ.ApM7kZlGPgq1dUyOsKXeK','$2a$11$3hGwFnD188HHIz4eNkh1wO','2013-07-06 22:35:58','2013-07-06 22:35:58',0),(18,'Apples','apples','apples@apples.net','$2a$11$DUyeZsYll1ATiGVok3/qguyQZQHmBRc2FxRqac4IAVeL227fzyEUC','$2a$11$DUyeZsYll1ATiGVok3/qgu','2013-07-06 22:36:47','2013-07-06 22:36:47',0),(19,'Apples2','apples2','apples@two.net','$2a$11$owPv/2Yb1jx/BKbZJq0rnO9e2QrZGWT8sQiG/zuXaeXmEz0ZTGjA6','$2a$11$owPv/2Yb1jx/BKbZJq0rnO','2013-07-07 13:11:40','2013-07-07 13:11:40',0);
/*!40000 ALTER TABLE `player` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_attribute`
--

DROP TABLE IF EXISTS `player_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_attribute` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `value` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_attribute`
--

LOCK TABLES `player_attribute` WRITE;
/*!40000 ALTER TABLE `player_attribute` DISABLE KEYS */;
/*!40000 ALTER TABLE `player_attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `player_role`
--

DROP TABLE IF EXISTS `player_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `player_role`
--

LOCK TABLES `player_role` WRITE;
/*!40000 ALTER TABLE `player_role` DISABLE KEYS */;
INSERT INTO `player_role` VALUES (1,0,1),(2,1,2);
/*!40000 ALTER TABLE `player_role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'guest'),(2,'root'),(3,'administrator'),(4,'moderator'),(5,'member');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permission`
--

DROP TABLE IF EXISTS `role_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permission`
--

LOCK TABLES `role_permission` WRITE;
/*!40000 ALTER TABLE `role_permission` DISABLE KEYS */;
INSERT INTO `role_permission` VALUES (1,1,4),(2,1,5),(3,1,6);
/*!40000 ALTER TABLE `role_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `setting`
--

DROP TABLE IF EXISTS `setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(20) NOT NULL,
  `value` text,
  `active` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `setting`
--

LOCK TABLES `setting` WRITE;
/*!40000 ALTER TABLE `setting` DISABLE KEYS */;
/*!40000 ALTER TABLE `setting` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-09 20:46:44
