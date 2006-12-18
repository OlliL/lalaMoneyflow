-- MySQL dump 10.10
--
-- Host: localhost    Database: moneyflow
-- ------------------------------------------------------
-- Server version	5.0.27

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
-- Table structure for table `capitalsources`
--

DROP TABLE IF EXISTS `capitalsources`;
CREATE TABLE `capitalsources` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` enum('current asset','long-term asset') NOT NULL default 'current asset',
  `state` enum('non cash','cash') NOT NULL default 'non cash',
  `accountnumber` bigint(20) default NULL,
  `bankcode` bigint(20) default NULL,
  `comment` varchar(255) default NULL,
  `validtil` date NOT NULL default '2999-12-31',
  `validfrom` date NOT NULL default '1970-01-01',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `contractpartners`
--

DROP TABLE IF EXISTS `contractpartners`;
CREATE TABLE `contractpartners` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `street` varchar(100) NOT NULL default '',
  `postcode` int(12) NOT NULL default '0',
  `town` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `currency` varchar(20) NOT NULL,
  `rate` float(7,2) default NULL,
  `att_default` int(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `att_default` (`att_default`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Table structure for table `moneyflows`
--

DROP TABLE IF EXISTS `moneyflows`;
CREATE TABLE `moneyflows` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `bookingdate` date NOT NULL default '0000-00-00',
  `invoicedate` date NOT NULL default '0000-00-00',
  `amount` float(7,2) NOT NULL default '0.00',
  `capitalsourceid` int(11) NOT NULL default '0',
  `contractpartnerid` int(11) NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `monthlysettlements`
--

DROP TABLE IF EXISTS `monthlysettlements`;
CREATE TABLE `monthlysettlements` (
  `id` int(11) NOT NULL auto_increment,
  `capitalsourceid` int(11) NOT NULL default '0',
  `month` tinyint(4) NOT NULL default '0',
  `year` year(4) NOT NULL default '0000',
  `amount` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `capitalsourceid` (`capitalsourceid`,`month`,`year`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;

--
-- Table structure for table `predefmoneyflows`
--

DROP TABLE IF EXISTS `predefmoneyflows`;
CREATE TABLE `predefmoneyflows` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `amount` float NOT NULL default '0',
  `capitalsourceid` int(11) NOT NULL default '0',
  `contractpartnerid` int(11) NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `name` varchar(50) NOT NULL default '',
  `value` varchar(50) default NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2006-12-18 12:23:12
