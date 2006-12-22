-- MySQL dump 10.10
--
-- Host: localhost    Database: moneyflow
-- ------------------------------------------------------
-- Server version	5.0.27

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES latin1 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `att_new` tinyint(1) unsigned NOT NULL,
  `perm_login` tinyint(1) unsigned NOT NULL,
  `perm_admin` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mur_i_01` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mur';

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `userid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `value` varchar(50) default NULL,
  PRIMARY KEY  (`name`,`userid`),
  KEY `mse_mur_pk` (`userid`),
  CONSTRAINT `mse_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mse';

--
-- Table structure for table `capitalsources`
--

DROP TABLE IF EXISTS `capitalsources`;
CREATE TABLE `capitalsources` (
  `userid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` enum('current asset','long-term asset') NOT NULL default 'current asset',
  `state` enum('non cash','cash') NOT NULL default 'non cash',
  `accountnumber` bigint(20) default NULL,
  `bankcode` bigint(20) default NULL,
  `comment` varchar(255) default NULL,
  `validtil` date NOT NULL default '2999-12-31',
  `validfrom` date NOT NULL default '1970-01-01',
  PRIMARY KEY  (`id`,`userid`),
  KEY `mcs_i_01` (`userid`),
  CONSTRAINT `mcs_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcs';

--
-- Table structure for table `contractpartners`
--

DROP TABLE IF EXISTS `contractpartners`;
CREATE TABLE `contractpartners` (
  `userid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `street` varchar(100) NOT NULL default '',
  `postcode` int(12) NOT NULL default '0',
  `town` varchar(100) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`userid`),
  UNIQUE KEY `mcp_i_01` (`userid`,`name`),
  CONSTRAINT `mcp_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcp';

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `currency` varchar(20) NOT NULL,
  `rate` float(11,5) default NULL,
  `att_default` tinyint(1) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mcu_i_02` (`currency`),
  UNIQUE KEY `mcu_i_01` (`att_default`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcu';

--
-- Table structure for table `moneyflows`
--

DROP TABLE IF EXISTS `moneyflows`;
CREATE TABLE `moneyflows` (
  `userid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  `bookingdate` date NOT NULL default '0000-00-00',
  `invoicedate` date NOT NULL default '0000-00-00',
  `amount` float(8,2) NOT NULL default '0.00',
  `capitalsourceid` int(10) unsigned NOT NULL,
  `contractpartnerid` int(10) unsigned NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`userid`),
  KEY `mmf_mcp_pk` (`contractpartnerid`,`userid`),
  KEY `mmf_i_01` (`userid`,`bookingdate`),
  KEY `mmf_mcs_pk` (`capitalsourceid`,`userid`),
  CONSTRAINT `mmf_mcs_pk` FOREIGN KEY (`capitalsourceid`, `userid`) REFERENCES `capitalsources` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mmf_mcp_pk` FOREIGN KEY (`contractpartnerid`, `userid`) REFERENCES `contractpartners` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mmf_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mmf';

--
-- Table structure for table `monthlysettlements`
--

DROP TABLE IF EXISTS `monthlysettlements`;
CREATE TABLE `monthlysettlements` (
  `userid` int(10) unsigned NOT NULL,
  `id` int(10) NOT NULL auto_increment,
  `capitalsourceid` int(10) unsigned NOT NULL,
  `month` tinyint(4) NOT NULL default '0',
  `year` year(4) NOT NULL default '0000',
  `amount` float(8,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`,`userid`),
  UNIQUE KEY `mms_i_01` (`userid`,`month`,`year`,`capitalsourceid`),
  KEY `mms_mcs_pk` (`capitalsourceid`,`userid`),
  CONSTRAINT `mms_mcs_pk` FOREIGN KEY (`capitalsourceid`, `userid`) REFERENCES `capitalsources` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mms_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mms';

--
-- Table structure for table `predefmoneyflows`
--

DROP TABLE IF EXISTS `predefmoneyflows`;
CREATE TABLE `predefmoneyflows` (
  `userid` int(10) unsigned NOT NULL,
  `id` int(10) unsigned NOT NULL auto_increment,
  `amount` float(8,2) NOT NULL default '0.00',
  `capitalsourceid` int(10) unsigned NOT NULL,
  `contractpartnerid` int(10) unsigned NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`userid`),
  KEY `mpm_mur_pk` (`userid`),
  KEY `mpm_mcp_pk` (`contractpartnerid`,`userid`),
  KEY `mpm_mcs_pk` (`capitalsourceid`,`userid`),
  CONSTRAINT `mpm_mcs_pk` FOREIGN KEY (`capitalsourceid`, `userid`) REFERENCES `capitalsources` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mpm_mcp_pk` FOREIGN KEY (`contractpartnerid`, `userid`) REFERENCES `contractpartners` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mpm_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mpm';

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `language` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mla_i_01` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mla';

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
CREATE TABLE `templates` (
  `name` varchar(50) NOT NULL,
  `textid` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`name`,`textid`),
  KEY `mtm_mtx_pk` (`textid`),
  CONSTRAINT `mtm_mtx_pk` FOREIGN KEY (`textid`) REFERENCES `text` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtm';

--
-- Table structure for table `text`
--

DROP TABLE IF EXISTS `text`;
CREATE TABLE `text` (
  `id` int(10) unsigned NOT NULL,
  `languageid` int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `type` enum('t','m','e') NOT NULL,
  PRIMARY KEY  (`id`,`languageid`,`type`),
  KEY `mte_mla_pk` (`languageid`),
  CONSTRAINT `mte_mla_pk` FOREIGN KEY (`languageid`) REFERENCES `languages` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtx';
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2006-12-21 23:59:40
INSERT INTO `currencies` VALUES (1,'EUR',1.00000,1),(2,'DM',1.95583,NULL);
INSERT INTO `languages` VALUES (2,'deutsch'),(1,'english');
INSERT INTO `text` VALUES (1,1,'sources of capital','t'),(1,1,'January','m'),(1,1,'No sources of capital where created in the system!','e'),(1,2,'Kapitalquellen','t'),(1,2,'Januar','m'),(1,2,'Es wurden keine Kapitalquellen im System angelegt!','e'),(2,1,'contractual partners','t'),(2,1,'February','m'),(2,1,'You may not delete a source of capital while it is referenced by a flow of money!','e'),(2,2,'Vertragspartner','t'),(2,2,'Februar','m'),(2,2,'Es dürfen keine Kapitalquellen gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e'),(3,1,'predefined flows of money','t'),(3,1,'March','m'),(3,1,'There are flows of money for this source of capital outside the period of validitiy you''ve choosen!','e'),(3,2,'vordefinierte Geldbewegungen','t'),(3,2,'März','m'),(3,2,'Es existieren Geldbewegungen für diese Kapitalquelle ausserhalb des gwählten Gültigkeitszeitraums','e'),(4,1,'monthly balances','t'),(4,1,'April','m'),(4,1,'The source of capital you''ve choosen is not valid on the booking date you''ve specified!','e'),(4,2,'Monatsabschlüsse','t'),(4,2,'April','m'),(4,2,'Das Buchungsdatum liegt nicht im Gültigkeitszeitraum der gewählten Kapitalquelle!','e'),(5,1,'reports','t'),(5,1,'May','m'),(5,1,'No contractual partners where created in the system!','e'),(5,2,'Reports','t'),(5,2,'Mai','m'),(5,2,'Es wurden keine Vertragspartner im System angelegt!','e'),(6,1,'trends','t'),(6,1,'June','m'),(6,1,'You may not delete a contractual partner who is still referenced by a flow of money!','e'),(6,2,'Trends','t'),(6,2,'Juni','m'),(6,2,'Es dürfen keine Vertragspartner gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e'),(7,1,'search for flows of money','t'),(7,1,'July','m'),(7,1,'The selected currency to display does not exist in the system!','e'),(7,2,'Suche nach Geldbewegungen','t'),(7,2,'Juli','m'),(7,2,'Die zur Anzeige ausgewählte Währung existiert im System nicht!','e'),(8,1,'add a flow of money','t'),(8,1,'August','m'),(8,1,'The currency to display is not specified in the settings!','e'),(8,2,'Geldbewegung hinzufügen','t'),(8,2,'August','m'),(8,2,'Es wurde keine Währung zur Anzeige in den Einstellungen angegeben!','e'),(9,1,'report','t'),(9,1,'September','m'),(9,1,'The field ''source of capital'' must not be empty!','e'),(9,2,'Report','t'),(9,2,'September','m'),(9,2,'Das Feld ''Kapitalquelle'' darf nicht leer sein!','e'),(10,1,'add source of capital','t'),(10,1,'October','m'),(10,1,'The field ''contractual partner'' must not be empty!','e'),(10,2,'Kapitalquelle hinzufügen','t'),(10,2,'Oktober','m'),(10,2,'Das Feld ''Vertragspartner'' darf nicht leer sein!','e'),(11,1,'add contractual partner','t'),(11,1,'November','m'),(11,1,'The field ''invoice date'' has to be in the format YYYY-MM-DD!','e'),(11,2,'Vertragspartner hinzufügen','t'),(11,2,'November','m'),(11,2,'Das Feld ''Rechnungsdatum'' muss dem Format YYYY-MM-DD entsprechen!','e'),(12,1,'add predefined flow of money','t'),(12,1,'December','m'),(12,1,'The field ''booking date'' has to be in the format YYYY-MM-DD!','e'),(12,2,'vordef. Geldbew. hinzufügen','t'),(12,2,'Dezember','m'),(12,2,'Das Feld ''Buchungsdatum'' muss dem Format YYYY-MM-DD entsprechen!','e'),(13,1,'logout','t'),(13,1,'The field ''comment'' must not be empty!','e'),(13,2,'Abmelden','t'),(13,2,'Das Feld ''Kommentar'' darf nicht leer sein!','e'),(14,1,'shortcuts','t'),(14,1,'The amount ''A1A'' is in a format which is not readable by the system!','e'),(14,2,'Shortcuts','t'),(14,2,'Der Betrag ''A1A'' ist in einem vom System nicht lesbaren Format!','e'),(15,1,'edit flow of money','t'),(15,1,'It was nothing marked to add!','e'),(15,2,'Geldbewegung bearbeiten','t'),(15,2,'Es wurde nichts zum hinzufügen markiert!','e'),(16,1,'booking date','t'),(16,1,'The specified username or password are invalid!','e'),(16,2,'Buchungsdatum','t'),(16,2,'Der angegebene Benutzername oder das Password sind falsch!','e'),(17,1,'invoice date','t'),(17,1,'The selected language to display does not exist in the system!','e'),(17,2,'Rechnungsdatum','t'),(17,2,'Die zur Anzeige ausgewählte Sprache existiert im System nicht!','e'),(18,1,'amount','t'),(18,1,'The language to display is not specified in the settings!','e'),(18,2,'Betrag','t'),(18,2,'Es wurde keine Sprache zur Anzeige in den Einstellungen angegeben!','e'),(19,1,'source of capital','t'),(19,1,'The passwords are not matching!','e'),(19,2,'Kapitalquelle','t'),(19,2,'Die Passwörter sind unterschiedlich!','e'),(20,1,'contractual partner','t'),(20,1,'Your account has been locked!','e'),(20,2,'Vertragspartner','t'),(20,2,'Ihr Benutzerkonto wurde gesperrt!','e'),(21,1,'comment','t'),(21,1,'You must specifiy an username!','e'),(21,2,'Kommentar','t'),(21,2,'Sie müssen einen Benutzernamen angeben!','e'),(22,1,'save','t'),(22,1,'You must specifiy a password!','e'),(22,2,'Speichern','t'),(22,2,'Sie müssen ein Passwort angeben!','e'),(23,1,'cancel','t'),(23,2,'Abbrechen','t'),(24,1,'delete flow of money','t'),(24,2,'Geldbewegung löschen','t'),(25,1,'yes','t'),(25,2,'Ja','t'),(26,1,'no','t'),(26,2,'Nein','t'),(27,1,'Do you really want to delete this moneyflow?','t'),(27,2,'Wollen sie diese Geldbewegung wirklich löschen?','t'),(28,1,'all','t'),(28,2,'Alle','t'),(29,1,'add','t'),(29,2,'Hinzufügen','t'),(30,1,'type','t'),(30,2,'Typ','t'),(31,1,'state','t'),(31,2,'Status','t'),(32,1,'account number','t'),(32,2,'Kontonummer','t'),(33,1,'bankcode','t'),(33,2,'Bankleitzahl','t'),(34,1,'valid from','t'),(34,2,'gültig von','t'),(35,1,'valid til','t'),(35,2,'gültig bis','t'),(36,1,'edit','t'),(36,2,'bearbeiten','t'),(37,1,'delete','t'),(37,2,'löschen','t'),(38,1,'edit source of capital','t'),(38,2,'Kapitalquelle bearbeiten','t'),(39,1,'delete source of capital','t'),(39,2,'Kapitalquelle löschen','t'),(40,1,'Do you really want to delete this source of capital?','t'),(40,2,'Wollen sie diese Kapitalquelle wirklich löschen?','t'),(41,1,'name','t'),(41,2,'Name','t'),(42,1,'street','t'),(42,2,'Straße','t'),(43,1,'postcode','t'),(43,2,'Postleitzahl','t'),(44,1,'town','t'),(44,2,'Stadt','t'),(45,1,'country','t'),(45,2,'Land','t'),(46,1,'edit contractual partner','t'),(46,2,'Vertragspartner bearbeiten','t'),(47,1,'Do you really want to delete this contractual partner?','t'),(47,2,'Wollen sie diesen Vertragspartner wirklich löschen','t'),(48,1,'delete contractual partner','t'),(48,2,'Vertragspartner löschen','t'),(49,1,'edit predefined flow of money','t'),(49,2,'vordefinierte Geldbewegung bearbeiten','t'),(50,1,'add predefined flow of money','t'),(50,2,'vordefinierte Geldbewegung hinzufügen','t'),(51,1,'delete predefined flow of money','t'),(51,2,'vordefinierte Geldbewegung löschen','t'),(52,1,'Do you really want to delete this predefined flow of money?','t'),(52,2,'Wollen sie diese vordefinierte Geldbewegung wirklich löschen?','t'),(53,1,'monthly balance','t'),(53,2,'Monatsabschluss','t'),(54,1,'edit monthly balance','t'),(54,2,'Monatsabschluss bearbeiten','t'),(55,1,'add monthly balance','t'),(55,2,'Monatsabschluss hinzufügen','t'),(56,1,'month','t'),(56,2,'Monat','t'),(57,1,'year','t'),(57,2,'Jahr','t'),(58,1,'reload','t'),(58,2,'neu laden','t'),(59,1,'Do you really want to delete this monthly balance?','t'),(59,2,'Wollen sie diesen Monatsabschluss wirklich löschen?','t'),(60,1,'delete monthly balance','t'),(60,2,'Monatsabschluss löschen','t'),(61,1,'flow of money','t'),(61,2,'Geldbewegung','t'),(62,1,'amount begin','t'),(62,2,'Anfangsbetrag','t'),(63,1,'fixed end','t'),(63,2,'Endbetrag (fix)','t'),(64,1,'calculated end','t'),(64,2,'Endbetrag (errechnet)','t'),(65,1,'difference','t'),(65,2,'Differenz','t'),(66,1,'fixed asset','t'),(66,2,'Gewinn (fix)','t'),(67,1,'calculated asset','t'),(67,2,'Gewinn (errechnet)','t'),(68,1,'Summary','t'),(68,2,'Zusammenfassung','t'),(69,1,'start date','t'),(69,2,'Startdatum','t'),(70,1,'end date','t'),(70,2,'Enddatum','t'),(71,1,'show','t'),(71,2,'anzeigen','t'),(72,1,'searched field','t'),(72,2,'Suchfeld','t'),(73,1,'searchstring','t'),(73,2,'Suchtext','t'),(74,1,'type of date','t'),(74,2,'Datumstyp','t'),(75,1,'special features','t'),(75,2,'spezielle Features','t'),(76,1,'equal','t'),(76,2,'gleich','t'),(77,1,'case sensitive','t'),(77,2,'Groß-, Kleinschreibung beachten','t'),(78,1,'regular expression','t'),(78,2,'regulärer Ausdruck','t'),(79,1,'only negative amounts','t'),(79,2,'nur negative Beträge','t'),(80,1,'group by','t'),(80,2,'gruppieren nach','t'),(81,1,'booking year','t'),(81,2,'Buchungsjahr','t'),(82,1,'booking month','t'),(82,2,'Buchungsmonat','t'),(83,1,'search','t'),(83,2,'Suchen','t'),(84,1,'please log in...','t'),(84,2,'Bitte melden sie sich an...','t'),(85,1,'username','t'),(85,2,'Benutzername','t'),(86,1,'password','t'),(86,2,'Passwort','t'),(87,1,'stay logged in','t'),(87,2,'angemeldet bleiben','t'),(88,1,'login','t'),(88,2,'Anmelden','t'),(89,1,'personal settings','t'),(89,2,'persönliche Einstellungen','t'),(90,1,'displayed language','t'),(90,2,'angezeigte Sprache','t'),(91,1,'displayed currency','t'),(91,2,'angezeigte Währung','t'),(92,1,'password (again)','t'),(92,2,'Passwort (Wdhlg.)','t'),(93,1,'system settings','t'),(93,2,'Systemeinstellungen','t'),(94,1,'users','t'),(94,2,'Benutzerkonten','t'),(95,1,'administration','t'),(95,2,'Administration','t'),(96,1,'login','t'),(96,2,'Anmeldung','t'),(97,1,'admin','t'),(97,2,'Administrator','t'),(98,1,'new','t'),(98,2,'neu','t'),(99,1,'edit user','t'),(99,2,'Benutzer bearbeiten','t'),(100,1,'add user','t'),(100,2,'Benutzer hinzufügen','t'),(101,1,'delete user','t'),(101,2,'Benutzer löschen','t'),(102,1,'Do you really want to delete this user?','t'),(102,2,'Wollen sie diesen Benutzer wirklich löschen? ','t');
INSERT INTO `templates` VALUES ('display_header.tpl',1),('display_list_capitalsources.tpl',1),('display_header.tpl',2),('display_list_contractpartners.tpl',2),('display_header.tpl',3),('display_list_predefmoneyflows.tpl',3),('display_header.tpl',4),('display_list_monthlysettlements.tpl',4),('display_header.tpl',5),('display_list_reports.tpl',5),('display_header.tpl',6),('display_plot_trends.tpl',6),('display_header.tpl',7),('display_search.tpl',7),('display_add_moneyflow.tpl',8),('display_header.tpl',8),('display_header.tpl',9),('display_edit_capitalsource.tpl',10),('display_header.tpl',10),('display_edit_contractpartner.tpl',11),('display_header.tpl',11),('display_header.tpl',12),('display_header.tpl',13),('display_header.tpl',14),('display_edit_moneyflow.tpl',15),('display_add_moneyflow.tpl',16),('display_delete_moneyflow.tpl',16),('display_edit_moneyflow.tpl',16),('display_generate_report.tpl',16),('display_search.tpl',16),('display_add_moneyflow.tpl',17),('display_delete_moneyflow.tpl',17),('display_edit_moneyflow.tpl',17),('display_generate_report.tpl',17),('display_add_moneyflow.tpl',18),('display_delete_moneyflow.tpl',18),('display_delete_monthlysettlement.tpl',18),('display_delete_predefmoneyflow.tpl',18),('display_edit_moneyflow.tpl',18),('display_edit_monthlysettlement.tpl',18),('display_edit_predefmoneyflow.tpl',18),('display_generate_report.tpl',18),('display_list_monthlysettlements.tpl',18),('display_list_predefmoneyflows.tpl',18),('display_search.tpl',18),('display_add_moneyflow.tpl',19),('display_delete_moneyflow.tpl',19),('display_delete_monthlysettlement.tpl',19),('display_delete_predefmoneyflow.tpl',19),('display_edit_moneyflow.tpl',19),('display_edit_monthlysettlement.tpl',19),('display_edit_predefmoneyflow.tpl',19),('display_generate_report.tpl',19),('display_list_monthlysettlements.tpl',19),('display_list_predefmoneyflows.tpl',19),('display_plot_trends.tpl',19),('display_add_moneyflow.tpl',20),('display_delete_moneyflow.tpl',20),('display_delete_predefmoneyflow.tpl',20),('display_edit_moneyflow.tpl',20),('display_edit_predefmoneyflow.tpl',20),('display_generate_report.tpl',20),('display_list_predefmoneyflows.tpl',20),('display_search.tpl',20),('display_add_moneyflow.tpl',21),('display_delete_capitalsource.tpl',21),('display_delete_moneyflow.tpl',21),('display_delete_predefmoneyflow.tpl',21),('display_edit_capitalsource.tpl',21),('display_edit_moneyflow.tpl',21),('display_edit_predefmoneyflow.tpl',21),('display_generate_report.tpl',21),('display_list_capitalsources.tpl',21),('display_list_predefmoneyflows.tpl',21),('display_search.tpl',21),('display_add_moneyflow.tpl',22),('display_edit_capitalsource.tpl',22),('display_edit_contractpartner.tpl',22),('display_edit_moneyflow.tpl',22),('display_edit_monthlysettlement.tpl',22),('display_edit_predefmoneyflow.tpl',22),('display_edit_user.tpl',22),('display_personal_settings.tpl',22),('display_system_settings.tpl',22),('display_edit_capitalsource.tpl',23),('display_edit_contractpartner.tpl',23),('display_edit_moneyflow.tpl',23),('display_edit_monthlysettlement.tpl',23),('display_edit_predefmoneyflow.tpl',23),('display_edit_user.tpl',23),('display_delete_moneyflow.tpl',24),('display_delete_capitalsource.tpl',25),('display_delete_contractpartner.tpl',25),('display_delete_moneyflow.tpl',25),('display_delete_monthlysettlement.tpl',25),('display_delete_predefmoneyflow.tpl',25),('display_delete_user.tpl',25),('display_edit_user.tpl',25),('display_list_users.tpl',25),('display_delete_capitalsource.tpl',26),('display_delete_contractpartner.tpl',26),('display_delete_moneyflow.tpl',26),('display_delete_monthlysettlement.tpl',26),('display_delete_predefmoneyflow.tpl',26),('display_delete_user.tpl',26),('display_edit_user.tpl',26),('display_list_users.tpl',26),('display_delete_moneyflow.tpl',27),('display_list_capitalsources.tpl',28),('display_list_contractpartners.tpl',28),('display_list_predefmoneyflows.tpl',28),('display_list_users.tpl',28),('display_list_capitalsources.tpl',29),('display_list_contractpartners.tpl',29),('display_list_monthlysettlements.tpl',29),('display_list_predefmoneyflows.tpl',29),('display_list_users.tpl',29),('display_delete_capitalsource.tpl',30),('display_edit_capitalsource.tpl',30),('display_generate_report.tpl',30),('display_list_capitalsources.tpl',30),('display_delete_capitalsource.tpl',31),('display_edit_capitalsource.tpl',31),('display_generate_report.tpl',31),('display_list_capitalsources.tpl',31),('display_delete_capitalsource.tpl',32),('display_edit_capitalsource.tpl',32),('display_list_capitalsources.tpl',32),('display_delete_capitalsource.tpl',33),('display_edit_capitalsource.tpl',33),('display_list_capitalsources.tpl',33),('display_delete_capitalsource.tpl',34),('display_edit_capitalsource.tpl',34),('display_list_capitalsources.tpl',34),('display_delete_capitalsource.tpl',35),('display_edit_capitalsource.tpl',35),('display_list_capitalsources.tpl',35),('display_generate_report.tpl',36),('display_list_capitalsources.tpl',36),('display_list_contractpartners.tpl',36),('display_list_monthlysettlements.tpl',36),('display_list_predefmoneyflows.tpl',36),('display_list_users.tpl',36),('display_generate_report.tpl',37),('display_list_capitalsources.tpl',37),('display_list_contractpartners.tpl',37),('display_list_monthlysettlements.tpl',37),('display_list_predefmoneyflows.tpl',37),('display_list_users.tpl',37),('display_edit_capitalsource.tpl',38),('display_delete_capitalsource.tpl',39),('display_delete_capitalsource.tpl',40),('display_delete_contractpartner.tpl',41),('display_edit_contractpartner.tpl',41),('display_list_contractpartners.tpl',41),('display_delete_contractpartner.tpl',42),('display_edit_contractpartner.tpl',42),('display_list_contractpartners.tpl',42),('display_delete_contractpartner.tpl',43),('display_edit_contractpartner.tpl',43),('display_list_contractpartners.tpl',43),('display_delete_contractpartner.tpl',44),('display_edit_contractpartner.tpl',44),('display_list_contractpartners.tpl',44),('display_delete_contractpartner.tpl',45),('display_edit_contractpartner.tpl',45),('display_list_contractpartners.tpl',45),('display_edit_contractpartner.tpl',46),('display_delete_contractpartner.tpl',47),('display_delete_contractpartner.tpl',48),('display_edit_predefmoneyflow.tpl',49),('display_edit_predefmoneyflow.tpl',50),('display_delete_predefmoneyflow.tpl',51),('display_delete_predefmoneyflow.tpl',52),('display_edit_monthlysettlement.tpl',53),('display_list_monthlysettlements.tpl',53),('display_edit_monthlysettlement.tpl',54),('display_edit_monthlysettlement.tpl',55),('display_edit_monthlysettlement.tpl',56),('display_generate_report.tpl',56),('display_search.tpl',56),('display_edit_monthlysettlement.tpl',57),('display_generate_report.tpl',57),('display_search.tpl',57),('display_edit_monthlysettlement.tpl',58),('display_delete_monthlysettlement.tpl',59),('display_delete_monthlysettlement.tpl',60),('display_generate_report.tpl',61),('display_generate_report.tpl',62),('display_generate_report.tpl',63),('display_generate_report.tpl',64),('display_generate_report.tpl',65),('display_generate_report.tpl',66),('display_generate_report.tpl',67),('display_generate_report.tpl',68),('display_plot_trends.tpl',69),('display_search.tpl',69),('display_plot_trends.tpl',70),('display_search.tpl',70),('display_plot_trends.tpl',71),('display_search.tpl',72),('display_search.tpl',73),('display_search.tpl',74),('display_search.tpl',75),('display_search.tpl',76),('display_search.tpl',77),('display_search.tpl',78),('display_search.tpl',79),('display_search.tpl',80),('display_search.tpl',81),('display_search.tpl',82),('display_search.tpl',83),('display_login_user.tpl',84),('display_delete_user.tpl',85),('display_edit_user.tpl',85),('display_list_users.tpl',85),('display_login_user.tpl',85),('display_edit_user.tpl',86),('display_login_user.tpl',86),('display_personal_settings.tpl',86),('display_login_user.tpl',87),('display_login_user.tpl',88),('display_header.tpl',89),('display_personal_settings.tpl',89),('display_personal_settings.tpl',90),('display_system_settings.tpl',90),('display_personal_settings.tpl',91),('display_system_settings.tpl',91),('display_edit_user.tpl',92),('display_personal_settings.tpl',92),('display_header.tpl',93),('display_system_settings.tpl',93),('display_header.tpl',94),('display_list_users.tpl',94),('display_header.tpl',95),('display_delete_user.tpl',96),('display_edit_user.tpl',96),('display_list_users.tpl',96),('display_delete_user.tpl',97),('display_edit_user.tpl',97),('display_list_users.tpl',97),('display_delete_user.tpl',98),('display_edit_user.tpl',98),('display_list_users.tpl',98),('display_edit_user.tpl',99),('display_edit_user.tpl',100),('display_delete_user.tpl',101),('display_delete_user.tpl',102);
INSERT INTO settings VALUES (0,'displayed_currency','1'),(0,'displayed_language','1');
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET id=0 WHERE username='';
