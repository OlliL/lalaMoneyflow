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
  `att_default` int(1) default NULL,
  PRIMARY KEY  (`id`),
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
  `capitalsourceid` int(10) unsigned NOT NULL default '0',
  `contractpartnerid` int(10) unsigned NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`userid`),
  KEY `mmf_mcs_pk` (`capitalsourceid`,`userid`),
  KEY `mmf_mcp_pk` (`contractpartnerid`,`userid`),
  KEY `mmf_i_01` (`userid`,`bookingdate`),
  CONSTRAINT `mmf_mcp_pk` FOREIGN KEY (`contractpartnerid`, `userid`) REFERENCES `contractpartners` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mmf_mcs_pk` FOREIGN KEY (`capitalsourceid`, `userid`) REFERENCES `capitalsources` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mmf_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mmf';

--
-- Table structure for table `monthlysettlements`
--

DROP TABLE IF EXISTS `monthlysettlements`;
CREATE TABLE `monthlysettlements` (
  `userid` int(10) unsigned NOT NULL,
  `id` int(10) NOT NULL auto_increment,
  `capitalsourceid` int(10) unsigned NOT NULL default '0',
  `month` tinyint(4) NOT NULL default '0',
  `year` year(4) NOT NULL default '0000',
  `amount` float(8,2) NOT NULL default '0.00',
  PRIMARY KEY  (`id`,`userid`),
  UNIQUE KEY `mms_i_01` (`userid`,`month`,`year`,`capitalsourceid`),
  KEY `mms_mcs_pk` (`capitalsourceid`,`userid`),
  CONSTRAINT `mse_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
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
  `capitalsourceid` int(10) unsigned NOT NULL default '0',
  `contractpartnerid` int(10) unsigned NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`,`userid`),
  KEY `mpm_mur_pk` (`userid`),
  KEY `mpm_mcs_pk` (`capitalsourceid`,`userid`),
  KEY `mpm_mcp_pk` (`contractpartnerid`,`userid`),
  CONSTRAINT `mpm_mcp_pk` FOREIGN KEY (`contractpartnerid`, `userid`) REFERENCES `contractpartners` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mpm_mcs_pk` FOREIGN KEY (`capitalsourceid`, `userid`) REFERENCES `capitalsources` (`id`, `userid`) ON UPDATE CASCADE,
  CONSTRAINT `mpm_mur_pk` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mpm';

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `userid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `value` varchar(50) default NULL,
  PRIMARY KEY  (`name`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mse';

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
  `language` varchar(2) NOT NULL,
  `text` varchar(255) NOT NULL,
  `type` enum('t','m','e') NOT NULL,
  PRIMARY KEY  (`id`,`language`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtx';

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `mur_i_01` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mur';

--
-- Temporary table structure for view `vw_template_text`
--

DROP TABLE IF EXISTS `vw_template_text`;
/*!50001 DROP VIEW IF EXISTS `vw_template_text`*/;
/*!50001 CREATE TABLE `vw_template_text` (
  `userid` int(10) unsigned,
  `name` varchar(50),
  `variable` varbinary(16),
  `text` varchar(255)
) */;

--
-- Temporary table structure for view `vw_text`
--

DROP TABLE IF EXISTS `vw_text`;
/*!50001 DROP VIEW IF EXISTS `vw_text`*/;
/*!50001 CREATE TABLE `vw_text` (
  `id` int(10) unsigned,
  `text` varchar(255),
  `type` enum('t','m','e'),
  `userid` int(10) unsigned
) */;

--
-- Final view structure for view `vw_template_text`
--

/*!50001 DROP TABLE IF EXISTS `vw_template_text`*/;
/*!50001 DROP VIEW IF EXISTS `vw_template_text`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_template_text` AS select `b`.`userid` AS `userid`,`a`.`name` AS `name`,concat(_latin1'TEXT_',`b`.`id`) AS `variable`,`b`.`text` AS `text` from (`templates` `a` join `vw_text` `b`) where ((`a`.`textid` = `b`.`id`) and (`b`.`type` = _latin1't')) */;

--
-- Final view structure for view `vw_text`
--

/*!50001 DROP TABLE IF EXISTS `vw_text`*/;
/*!50001 DROP VIEW IF EXISTS `vw_text`*/;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_text` AS select `a`.`id` AS `id`,`a`.`text` AS `text`,`a`.`type` AS `type`,`b`.`userid` AS `userid` from (`text` `a` join `settings` `b`) where ((`a`.`language` = `b`.`value`) and (`b`.`name` = _latin1'displayed_language')) */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2006-12-20 19:59:37
INSERT INTO `currencies` VALUES (1,'EUR',1.00000,1),(2,'DM',1.95583,NULL);
INSERT INTO `text` VALUES (1,'de','Kapitalquellen','t'),(1,'de','Januar','m'),(1,'de','Es wurden keine Kapitalquellen im System angelegt!','e'),(1,'en','sources of capital','t'),(1,'en','January','m'),(1,'en','No sources of capital where created in the system!','e'),(2,'de','Vertragspartner','t'),(2,'de','Februar','m'),(2,'de','Es dürfen keine Kapitalquellen gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e'),(2,'en','contractual partners','t'),(2,'en','February','m'),(2,'en','You may not delete a source of capital while it is referenced by a flow of money!','e'),(3,'de','vordefinierte Geldbewegungen','t'),(3,'de','März','m'),(3,'de','Es existieren Geldbewegungen für diese Kapitalquelle ausserhalb des gwählten Gültigkeitszeitraums','e'),(3,'en','predefined flows of money','t'),(3,'en','March','m'),(3,'en','There are flows of money for this source of capital outside the period of validitiy you\'ve choosen!','e'),(4,'de','Monatsabschlüsse','t'),(4,'de','April','m'),(4,'de','Das Buchungsdatum liegt nicht im Gültigkeitszeitraum der gewählten Kapitalquelle!','e'),(4,'en','monthly balances','t'),(4,'en','April','m'),(4,'en','The source of capital you\'ve choosen is not valid on the booking date you\'ve specified!','e'),(5,'de','Reports','t'),(5,'de','Mai','m'),(5,'de','Es wurden keine Vertragspartner im System angelegt!','e'),(5,'en','reports','t'),(5,'en','May','m'),(5,'en','No contractual partners where created in the system!','e'),(6,'de','Trends','t'),(6,'de','Juni','m'),(6,'de','Es dürfen keine Vertragspartner gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e'),(6,'en','trends','t'),(6,'en','June','m'),(6,'en','You may not delete a contractual partner who is still referenced by a flow of money!','e'),(7,'de','Suche nach Geldbewegungen','t'),(7,'de','Juli','m'),(7,'de','Die zur Anzeige ausgewählte Währung existiert im System nicht!','e'),(7,'en','search for flows of money','t'),(7,'en','July','m'),(7,'en','The selected currency to display does not exist in the system!','e'),(8,'de','Geldbewegung hinzufügen','t'),(8,'de','August','m'),(8,'de','Es wurde keine Währung zur Anzeige in dein Einstellungen angegeben!','e'),(8,'en','add a flow of money','t'),(8,'en','August','m'),(8,'en','The currency to display is not specified in the settings!','e'),(9,'de','Report','t'),(9,'de','September','m'),(9,'de','Das Feld \'Kapitalquelle\' darf nicht leer sein!','e'),(9,'en','report','t'),(9,'en','September','m'),(9,'en','The field \'source of capital\' must not be empty!','e'),(10,'de','Kapitalquelle hinzufügen','t'),(10,'de','Oktober','m'),(10,'de','Das Feld \'Vertragspartner\' darf nicht leer sein!','e'),(10,'en','add source of capital','t'),(10,'en','October','m'),(10,'en','The field \'contractual partner\' must not be empty!','e'),(11,'de','Vertragspartner hinzufügen','t'),(11,'de','November','m'),(11,'de','Das Feld \'Rechnungsdatum\' muss dem Format YYYY-MM-DD entsprechen!','e'),(11,'en','add contractual partner','t'),(11,'en','November','m'),(11,'en','The field \'invoice date\' has to be in the format YYYY-MM-DD!','e'),(12,'de','vordef. Geldbew. hinzufügen','t'),(12,'de','Dezember','m'),(12,'de','Das Feld \'Buchungsdatum\' muss dem Format YYYY-MM-DD entsprechen!','e'),(12,'en','add predefined flow of money','t'),(12,'en','December','m'),(12,'en','The field \'booking date\' has to be in the format YYYY-MM-DD!','e'),(13,'de','Abmelden','t'),(13,'de','Das Feld \'Kommentar\' darf nicht leer sein!','e'),(13,'en','logout','t'),(13,'en','The field \'comment\' must not be empty!','e'),(14,'de','Shortcuts','t'),(14,'de','Der Betrag \'A1A\' ist in einem vom System nicht lesbaren Format!','e'),(14,'en','shortcuts','t'),(14,'en','The amount \'A1A\' is in a format which is not readable by the system!','e'),(15,'de','Geldbewegung bearbeiten','t'),(15,'de','Es wurde nichts zum hinzufügen markiert!','e'),(15,'en','edit flow of money','t'),(15,'en','It was nothing marked to add!','e'),(16,'de','Buchungsdatum','t'),(16,'de','Der angegebene Benutzername oder das Password sind falsch!','e'),(16,'en','booking date','t'),(16,'en','The specified username or password are invalid!','e'),(17,'de','Rechnungsdatum','t'),(17,'en','invoice date','t'),(18,'de','Betrag','t'),(18,'en','amount','t'),(19,'de','Kapitalquelle','t'),(19,'en','source of capital','t'),(20,'de','Vertragspartner','t'),(20,'en','contractual partner','t'),(21,'de','Kommentar','t'),(21,'en','comment','t'),(22,'de','Speichern','t'),(22,'en','save','t'),(23,'de','Abbrechen','t'),(23,'en','cancel','t'),(24,'de','Geldbewegung löschen','t'),(24,'en','delete flow of money','t'),(25,'de','Ja','t'),(25,'en','yes','t'),(26,'de','Nein','t'),(26,'en','no','t'),(27,'de','Wollen sie diese Geldbewegung wirklich löschen?','t'),(27,'en','Do you really want to delete this moneyflow?','t'),(28,'de','Alle','t'),(28,'en','all','t'),(29,'de','Hinzufügen','t'),(29,'en','add','t'),(30,'de','Typ','t'),(30,'en','type','t'),(31,'de','Status','t'),(31,'en','state','t'),(32,'de','Kontonummer','t'),(32,'en','account number','t'),(33,'de','Bankleitzahl','t'),(33,'en','bankcode','t'),(34,'de','gültig von','t'),(34,'en','valid from','t'),(35,'de','gültig bis','t'),(35,'en','valid til','t'),(36,'de','bearbeiten','t'),(36,'en','edit','t'),(37,'de','löschen','t'),(37,'en','delete','t'),(38,'de','Kapitalquelle bearbeiten','t'),(38,'en','edit source of capital','t'),(39,'de','Kapitalquelle löschen','t'),(39,'en','delete source of capital','t'),(40,'de','Wollen sie diese Kapitalquelle wirklich löschen?','t'),(40,'en','Do you really want to delete this source of capital?','t'),(41,'de','Name','t'),(41,'en','name','t'),(42,'de','Straße','t'),(42,'en','street','t'),(43,'de','Postleitzahl','t'),(43,'en','postcode','t'),(44,'de','Stadt','t'),(44,'en','town','t'),(45,'de','Land','t'),(45,'en','country','t'),(46,'de','Vertragspartner bearbeiten','t'),(46,'en','edit contractual partner','t'),(47,'de','Wollen sie diesen Vertragspartner wirklich löschen','t'),(47,'en','Do you really want to delete this contractual partner?','t'),(48,'de','Vertragspartner löschen','t'),(48,'en','delete contractual partner','t'),(49,'de','vordefinierte Geldbewegung bearbeiten','t'),(49,'en','edit predefined flow of money','t'),(50,'de','vordefinierte Geldbewegung hinzufügen','t'),(50,'en','add predefined flow of money','t'),(51,'de','vordefinierte Geldbewegung löschen','t'),(51,'en','delete predefined flow of money','t'),(52,'de','Wollen sie diese vordefinierte Geldbewegung wirklich löschen?','t'),(52,'en','Do you really want to delete this predefined flow of money?','t'),(53,'de','Monatsabschluss','t'),(53,'en','monthly balance','t'),(54,'de','Monatsabschluss bearbeiten','t'),(54,'en','edit monthly balance','t'),(55,'de','Monatsabschluss hinzufügen','t'),(55,'en','add monthly balance','t'),(56,'de','Monat','t'),(56,'en','month','t'),(57,'de','Jahr','t'),(57,'en','year','t'),(58,'de','neu laden','t'),(58,'en','reload','t'),(59,'de','Wollen sie diesen Monatsabschluss wirklich löschen?','t'),(59,'en','Do you really want to delete this monthly balance?','t'),(60,'de','Monatsabschluss löschen','t'),(60,'en','delete monthly balance','t'),(61,'de','Geldbewegung','t'),(61,'en','flow of money','t'),(62,'de','Anfangsbetrag','t'),(62,'en','amount begin','t'),(63,'de','Endbetrag (fix)','t'),(63,'en','fixed end','t'),(64,'de','Endbetrag (errechnet)','t'),(64,'en','calculated end','t'),(65,'de','Differenz','t'),(65,'en','difference','t'),(66,'de','Gewinn (fix)','t'),(66,'en','fixed asset','t'),(67,'de','Gewinn (errechnet)','t'),(67,'en','calculated asset','t'),(68,'de','Zusammenfassung','t'),(68,'en','Summary','t'),(69,'de','Startdatum','t'),(69,'en','start date','t'),(70,'de','Enddatum','t'),(70,'en','end date','t'),(71,'de','anzeigen','t'),(71,'en','show','t'),(72,'de','Suchfeld','t'),(72,'en','searched field','t'),(73,'de','Suchtext','t'),(73,'en','searchstring','t'),(74,'de','Datumstyp','t'),(74,'en','type of date','t'),(75,'de','spezielle Features','t'),(75,'en','special features','t'),(76,'de','gleich','t'),(76,'en','equal','t'),(77,'de','Groß-, Kleinschreibung beachten','t'),(77,'en','case sensitive','t'),(78,'de','regulärer Ausdruck','t'),(78,'en','regular expression','t'),(79,'de','nur negative Beträge','t'),(79,'en','only negative amounts','t'),(80,'de','gruppieren nach','t'),(80,'en','group by','t'),(81,'de','Buchungsjahr','t'),(81,'en','booking year','t'),(82,'de','Buchungsmonat','t'),(82,'en','booking month','t'),(83,'de','Suchen','t'),(83,'en','search','t'),(84,'de','Bitte melden sie sich an...','t'),(84,'en','please log in...','t'),(85,'de','Benutzername','t'),(85,'en','username','t'),(86,'de','Passwort','t'),(86,'en','password','t'),(87,'de','angemeldet bleiben','t'),(87,'en','stay logged in','t'),(88,'de','Anmelden','t'),(88,'en','login','t');
INSERT INTO `templates` VALUES ('display_header.tpl',1),('display_list_capitalsources.tpl',1),('display_header.tpl',2),('display_list_contractpartners.tpl',2),('display_header.tpl',3),('display_list_predefmoneyflows.tpl',3),('display_header.tpl',4),('display_list_monthlysettlements.tpl',4),('display_header.tpl',5),('display_list_reports.tpl',5),('display_header.tpl',6),('display_plot_trends.tpl',6),('display_header.tpl',7),('display_search.tpl',7),('display_add_moneyflow.tpl',8),('display_header.tpl',8),('display_header.tpl',9),('display_edit_capitalsource.tpl',10),('display_header.tpl',10),('display_edit_contractpartner.tpl',11),('display_header.tpl',11),('display_header.tpl',12),('display_header.tpl',13),('display_header.tpl',14),('display_edit_moneyflow.tpl',15),('display_add_moneyflow.tpl',16),('display_delete_moneyflow.tpl',16),('display_edit_moneyflow.tpl',16),('display_generate_report.tpl',16),('display_search.tpl',16),('display_add_moneyflow.tpl',17),('display_delete_moneyflow.tpl',17),('display_edit_moneyflow.tpl',17),('display_generate_report.tpl',17),('display_add_moneyflow.tpl',18),('display_delete_moneyflow.tpl',18),('display_delete_monthlysettlement.tpl',18),('display_delete_predefmoneyflow.tpl',18),('display_edit_moneyflow.tpl',18),('display_edit_monthlysettlement.tpl',18),('display_edit_predefmoneyflow.tpl',18),('display_generate_report.tpl',18),('display_list_monthlysettlements.tpl',18),('display_list_predefmoneyflows.tpl',18),('display_search.tpl',18),('display_add_moneyflow.tpl',19),('display_delete_moneyflow.tpl',19),('display_delete_monthlysettlement.tpl',19),('display_delete_predefmoneyflow.tpl',19),('display_edit_moneyflow.tpl',19),('display_edit_monthlysettlement.tpl',19),('display_edit_predefmoneyflow.tpl',19),('display_generate_report.tpl',19),('display_list_monthlysettlements.tpl',19),('display_list_predefmoneyflows.tpl',19),('display_plot_trends.tpl',19),('display_add_moneyflow.tpl',20),('display_delete_moneyflow.tpl',20),('display_delete_predefmoneyflow.tpl',20),('display_edit_moneyflow.tpl',20),('display_edit_predefmoneyflow.tpl',20),('display_generate_report.tpl',20),('display_list_predefmoneyflows.tpl',20),('display_search.tpl',20),('display_add_moneyflow.tpl',21),('display_delete_capitalsource.tpl',21),('display_delete_moneyflow.tpl',21),('display_delete_predefmoneyflow.tpl',21),('display_edit_capitalsource.tpl',21),('display_edit_moneyflow.tpl',21),('display_edit_predefmoneyflow.tpl',21),('display_generate_report.tpl',21),('display_list_capitalsources.tpl',21),('display_list_predefmoneyflows.tpl',21),('display_search.tpl',21),('display_add_moneyflow.tpl',22),('display_edit_capitalsource.tpl',22),('display_edit_contractpartner.tpl',22),('display_edit_moneyflow.tpl',22),('display_edit_monthlysettlement.tpl',22),('display_edit_predefmoneyflow.tpl',22),('display_edit_capitalsource.tpl',23),('display_edit_contractpartner.tpl',23),('display_edit_moneyflow.tpl',23),('display_edit_monthlysettlement.tpl',23),('display_edit_predefmoneyflow.tpl',23),('display_delete_moneyflow.tpl',24),('display_delete_capitalsource.tpl',25),('display_delete_contractpartner.tpl',25),('display_delete_moneyflow.tpl',25),('display_delete_monthlysettlement.tpl',25),('display_delete_predefmoneyflow.tpl',25),('display_delete_capitalsource.tpl',26),('display_delete_contractpartner.tpl',26),('display_delete_moneyflow.tpl',26),('display_delete_monthlysettlement.tpl',26),('display_delete_predefmoneyflow.tpl',26),('display_delete_moneyflow.tpl',27),('display_list_capitalsources.tpl',28),('display_list_contractpartners.tpl',28),('display_list_predefmoneyflows.tpl',28),('display_list_capitalsources.tpl',29),('display_list_contractpartners.tpl',29),('display_list_monthlysettlements.tpl',29),('display_list_predefmoneyflows.tpl',29),('display_delete_capitalsource.tpl',30),('display_edit_capitalsource.tpl',30),('display_generate_report.tpl',30),('display_list_capitalsources.tpl',30),('display_delete_capitalsource.tpl',31),('display_edit_capitalsource.tpl',31),('display_generate_report.tpl',31),('display_list_capitalsources.tpl',31),('display_delete_capitalsource.tpl',32),('display_edit_capitalsource.tpl',32),('display_list_capitalsources.tpl',32),('display_delete_capitalsource.tpl',33),('display_edit_capitalsource.tpl',33),('display_list_capitalsources.tpl',33),('display_delete_capitalsource.tpl',34),('display_edit_capitalsource.tpl',34),('display_list_capitalsources.tpl',34),('display_delete_capitalsource.tpl',35),('display_edit_capitalsource.tpl',35),('display_list_capitalsources.tpl',35),('display_generate_report.tpl',36),('display_list_capitalsources.tpl',36),('display_list_contractpartners.tpl',36),('display_list_monthlysettlements.tpl',36),('display_list_predefmoneyflows.tpl',36),('display_generate_report.tpl',37),('display_list_capitalsources.tpl',37),('display_list_contractpartners.tpl',37),('display_list_monthlysettlements.tpl',37),('display_list_predefmoneyflows.tpl',37),('display_edit_capitalsource.tpl',38),('display_delete_capitalsource.tpl',39),('display_delete_capitalsource.tpl',40),('display_delete_contractpartner.tpl',41),('display_edit_contractpartner.tpl',41),('display_list_contractpartners.tpl',41),('display_delete_contractpartner.tpl',42),('display_edit_contractpartner.tpl',42),('display_list_contractpartners.tpl',42),('display_delete_contractpartner.tpl',43),('display_edit_contractpartner.tpl',43),('display_list_contractpartners.tpl',43),('display_delete_contractpartner.tpl',44),('display_edit_contractpartner.tpl',44),('display_list_contractpartners.tpl',44),('display_delete_contractpartner.tpl',45),('display_edit_contractpartner.tpl',45),('display_list_contractpartners.tpl',45),('display_edit_contractpartner.tpl',46),('display_delete_contractpartner.tpl',47),('display_delete_contractpartner.tpl',48),('display_edit_predefmoneyflow.tpl',49),('display_edit_predefmoneyflow.tpl',50),('display_delete_predefmoneyflow.tpl',51),('display_delete_predefmoneyflow.tpl',52),('display_edit_monthlysettlement.tpl',53),('display_list_monthlysettlements.tpl',53),('display_edit_monthlysettlement.tpl',54),('display_edit_monthlysettlement.tpl',55),('display_edit_monthlysettlement.tpl',56),('display_generate_report.tpl',56),('display_search.tpl',56),('display_edit_monthlysettlement.tpl',57),('display_generate_report.tpl',57),('display_search.tpl',57),('display_edit_monthlysettlement.tpl',58),('display_delete_monthlysettlement.tpl',59),('display_delete_monthlysettlement.tpl',60),('display_generate_report.tpl',61),('display_generate_report.tpl',62),('display_generate_report.tpl',63),('display_generate_report.tpl',64),('display_generate_report.tpl',65),('display_generate_report.tpl',66),('display_generate_report.tpl',67),('display_generate_report.tpl',68),('display_plot_trends.tpl',69),('display_search.tpl',69),('display_plot_trends.tpl',70),('display_search.tpl',70),('display_plot_trends.tpl',71),('display_search.tpl',72),('display_search.tpl',73),('display_search.tpl',74),('display_search.tpl',75),('display_search.tpl',76),('display_search.tpl',77),('display_search.tpl',78),('display_search.tpl',79),('display_search.tpl',80),('display_search.tpl',81),('display_search.tpl',82),('display_search.tpl',83),('display_login_user.tpl',84),('display_login_user.tpl',85),('display_login_user.tpl',86),('display_login_user.tpl',87),('display_login_user.tpl',88);
INSERT INTO `settings` VALUES (1,'displayed_currency','1'),(1,'displayed_language','en');
