-- MySQL dump 10.11
--
-- Host: localhost    Database: rpgsu_stats
-- ------------------------------------------------------
-- Server version	5.0.45-community-nt

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
-- Table structure for table `game_stat`
--

DROP TABLE IF EXISTS `game_stat`;
CREATE TABLE `game_stat` (
  `user_id` mediumint(15) NOT NULL default '0',
  `npc_id` mediumint(8) NOT NULL default '0',
  `town` int(10) NOT NULL default '0',
  `stat_id` int(3) unsigned NOT NULL default '0',
  `shop_id` mediumint(9) NOT NULL default '0',
  `item_id` varchar(255) NOT NULL default '0',
  `enemy_id` varchar(15) NOT NULL default '',
  `gp` decimal(15,2) unsigned default '0.00',
  `clan_id` mediumint(10) NOT NULL default '0',
  `exp` mediumint(8) NOT NULL default '0',
  `id` int(15) unsigned NOT NULL auto_increment,
  `time` int(14) NOT NULL default '0',
  `level_user` tinyint(3) unsigned NOT NULL default '0',
  `level_enemy` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `stat_id` (`stat_id`,`level_user`,`level_enemy`),
  KEY `user_id` (`user_id`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `game_stat`
--

LOCK TABLES `game_stat` WRITE;
/*!40000 ALTER TABLE `game_stat` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_stat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_stat_view`
--

DROP TABLE IF EXISTS `game_stat_view`;
CREATE TABLE `game_stat_view` (
  `id` int(30) unsigned NOT NULL auto_increment,
  `stat_id` int(2) unsigned NOT NULL default '0',
  `npc_id` int(20) unsigned NOT NULL default '0',
  `npc_kill` int(20) unsigned NOT NULL default '0',
  `user_id` mediumint(15) unsigned NOT NULL default '0',
  `town_id` int(10) unsigned NOT NULL default '0',
  `kol` double(15,2) NOT NULL default '0.00',
  `summa` double(15,2) NOT NULL default '0.00',
  `eat_id` int(10) NOT NULL default '0',
  `substat_id` int(10) NOT NULL default '0',
  `item_name` varchar(255) NOT NULL default '',
  `shop_id` int(10) NOT NULL default '0',
  `clan_id` int(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `stat_id` (`stat_id`,`npc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `game_stat_view`
--

LOCK TABLES `game_stat_view` WRITE;
/*!40000 ALTER TABLE `game_stat_view` DISABLE KEYS */;
/*!40000 ALTER TABLE `game_stat_view` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-11-22 20:47:27
