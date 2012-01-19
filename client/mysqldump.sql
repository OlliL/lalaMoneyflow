-- MySQL dump 10.13  Distrib 5.5.20, for FreeBSD9.0 (amd64)
--
-- Host: localhost    Database: moneyflow
-- ------------------------------------------------------
-- Server version	5.5.20-log

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

DROP TABLE IF EXISTS users;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE users (
  userid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  att_new tinyint(1) unsigned NOT NULL,
  perm_login tinyint(1) unsigned NOT NULL,
  perm_admin tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (userid),
  UNIQUE KEY mur_i_01 (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mur';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS settings;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE settings (
  mur_userid int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`name`,mur_userid),
  KEY mse_mur_pk (mur_userid),
  CONSTRAINT mse_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mse';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `capitalsources`
--

DROP TABLE IF EXISTS capitalsources;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE capitalsources (
  mur_userid int(10) unsigned NOT NULL,
  capitalsourceid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('1','2') NOT NULL DEFAULT '1',
  state enum('1','2') NOT NULL DEFAULT '1',
  accountnumber bigint(20) DEFAULT NULL,
  bankcode bigint(20) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  validtil date NOT NULL DEFAULT '2999-12-31',
  validfrom date NOT NULL DEFAULT '1970-01-01',
  att_group_use tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (capitalsourceid),
  KEY mcs_i_01 (mur_userid),
  CONSTRAINT mcs_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcs';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contractpartners`
--

DROP TABLE IF EXISTS contractpartners;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE contractpartners (
  mur_userid int(10) unsigned NOT NULL,
  contractpartnerid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  street varchar(100) NOT NULL DEFAULT '',
  postcode int(12) NOT NULL DEFAULT '0',
  town varchar(100) NOT NULL DEFAULT '',
  country varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (contractpartnerid),
  UNIQUE KEY mcp_i_01 (mur_userid,`name`),
  CONSTRAINT mcp_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcp';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS currencies;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE currencies (
  currencyid int(10) unsigned NOT NULL AUTO_INCREMENT,
  currency varchar(20) NOT NULL,
  att_default tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (currencyid),
  UNIQUE KEY mcu_i_01 (currency)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcu';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `currencyrates`
--

DROP TABLE IF EXISTS currencyrates;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE currencyrates (
  mcu_currencyid int(10) unsigned NOT NULL,
  rate float(11,5) NOT NULL,
  validfrom date NOT NULL,
  validtil date NOT NULL,
  PRIMARY KEY (mcu_currencyid,validfrom),
  UNIQUE KEY mcr_i_01 (mcu_currencyid,validtil),
  KEY mcr_mcu_pk (mcu_currencyid),
  CONSTRAINT mcr_mcu_pk FOREIGN KEY (mcu_currencyid) REFERENCES currencies (currencyid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcr';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `moneyflows`
--

DROP TABLE IF EXISTS moneyflows;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE moneyflows (
  mur_userid int(10) unsigned NOT NULL,
  moneyflowid int(10) unsigned NOT NULL AUTO_INCREMENT,
  bookingdate date NOT NULL DEFAULT '0000-00-00',
  invoicedate date NOT NULL DEFAULT '0000-00-00',
  amount float(8,2) NOT NULL DEFAULT '0.00',
  mcs_capitalsourceid int(10) unsigned NOT NULL,
  mcp_contractpartnerid int(10) unsigned NOT NULL,
  `comment` varchar(100) NOT NULL DEFAULT '',
  private tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (moneyflowid,mur_userid),
  KEY mmf_i_01 (mur_userid,bookingdate),
  KEY mmf_mcs_pk (mcs_capitalsourceid),
  CONSTRAINT mmf_mcs_pk FOREIGN KEY (mcs_capitalsourceid) REFERENCES capitalsources (capitalsourceid) ON UPDATE CASCADE,
  CONSTRAINT mmf_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mmf';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `monthlysettlements`
--

DROP TABLE IF EXISTS monthlysettlements;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE monthlysettlements (
  mur_userid int(10) unsigned NOT NULL,
  monthlysettlementid int(10) unsigned NOT NULL AUTO_INCREMENT,
  mcs_capitalsourceid int(10) unsigned NOT NULL,
  `month` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `year` year(4) NOT NULL DEFAULT '0000',
  amount float(8,2) NOT NULL DEFAULT '0.00',
  movement_calculated float(8,2) DEFAULT NULL,
  PRIMARY KEY (monthlysettlementid,mur_userid),
  UNIQUE KEY mms_i_01 (mur_userid,`month`,`year`,mcs_capitalsourceid),
  KEY mms_mcs_pk (mcs_capitalsourceid),
  CONSTRAINT mms_mcs_pk FOREIGN KEY (mcs_capitalsourceid) REFERENCES capitalsources (capitalsourceid) ON UPDATE CASCADE,
  CONSTRAINT mms_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mms';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `predefmoneyflows`
--

DROP TABLE IF EXISTS predefmoneyflows;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE predefmoneyflows (
  mur_userid int(10) unsigned NOT NULL,
  predefmoneyflowid int(10) unsigned NOT NULL AUTO_INCREMENT,
  amount float(8,2) NOT NULL DEFAULT '0.00',
  mcs_capitalsourceid int(10) unsigned NOT NULL,
  mcp_contractpartnerid int(10) unsigned NOT NULL,
  `comment` varchar(100) NOT NULL DEFAULT '',
  createdate date NOT NULL,
  once_a_month tinyint(1) unsigned NOT NULL DEFAULT '0',
  last_used date DEFAULT NULL,
  PRIMARY KEY (predefmoneyflowid,mur_userid),
  KEY mpm_mur_pk (mur_userid),
  KEY mpm_mcs_pk (mcs_capitalsourceid),
  CONSTRAINT mpm_mcs_pk FOREIGN KEY (mcs_capitalsourceid) REFERENCES capitalsources (capitalsourceid) ON UPDATE CASCADE,
  CONSTRAINT mpm_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mpm';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS languages;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE languages (
  languageid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(10) NOT NULL,
  PRIMARY KEY (languageid),
  UNIQUE KEY mla_i_01 (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mla';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS templates;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE templates (
  templatename varchar(50) NOT NULL,
  PRIMARY KEY (templatename)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mte';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `templatevalues`
--

DROP TABLE IF EXISTS templatevalues;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE templatevalues (
  mte_templatename varchar(50) NOT NULL,
  mtx_textid int(10) unsigned NOT NULL,
  PRIMARY KEY (mte_templatename,mtx_textid),
  KEY mtv_mtx_pk (mtx_textid),
  KEY mtv_mte_pk (mte_templatename),
  CONSTRAINT mtv_mte_pk FOREIGN KEY (mte_templatename) REFERENCES templates (templatename) ON UPDATE CASCADE,
  CONSTRAINT mtv_mtx_pk FOREIGN KEY (mtx_textid) REFERENCES text (textid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtv';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `text`
--

DROP TABLE IF EXISTS text;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `text` (
  textid int(10) unsigned NOT NULL,
  mla_languageid int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `type` enum('e','t','m','g','d') NOT NULL DEFAULT 't',
  PRIMARY KEY (textid,mla_languageid),
  KEY mtx_mla_pk (mla_languageid),
  CONSTRAINT mtx_mla_pk FOREIGN KEY (mla_languageid) REFERENCES `languages` (languageid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtx';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS domains;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE domains (
  domain varchar(30) NOT NULL,
  PRIMARY KEY (domain)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mdm';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `domainvalues`
--

DROP TABLE IF EXISTS domainvalues;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE domainvalues (
  mdm_domain varchar(30) NOT NULL,
  `value` varchar(3) NOT NULL,
  mtx_textid int(10) unsigned NOT NULL,
  PRIMARY KEY (mdm_domain,`value`),
  KEY mdv_mtx_pk (mtx_textid),
  CONSTRAINT mdv_mdm_pk FOREIGN KEY (mdm_domain) REFERENCES domains (domain) ON UPDATE CASCADE,
  CONSTRAINT mdv_mtx_pk FOREIGN KEY (mtx_textid) REFERENCES text (textid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mdv';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `imp_data`
--

DROP TABLE IF EXISTS imp_data;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE imp_data (
  dataid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` varchar(10) NOT NULL,
  amount varchar(20) NOT NULL,
  `source` varchar(100) NOT NULL,
  partner varchar(100) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (dataid)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mid';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `imp_mapping_source`
--

DROP TABLE IF EXISTS imp_mapping_source;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE imp_mapping_source (
  source_from varchar(100) NOT NULL,
  source_to varchar(100) NOT NULL,
  UNIQUE KEY mis_i_01 (source_from)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mis';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `imp_mapping_partner`
--

DROP TABLE IF EXISTS imp_mapping_partner;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE imp_mapping_partner (
  partner_from varchar(100) NOT NULL,
  partner_to varchar(100) NOT NULL,
  UNIQUE KEY mip_i_01 (partner_from)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mip';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmp_data_formats`
--

DROP TABLE IF EXISTS cmp_data_formats;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE cmp_data_formats (
  formatid int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  startline varchar(255) NOT NULL,
  delimiter varchar(1) NOT NULL,
  pos_date tinyint(2) NOT NULL,
  pos_partner tinyint(2) DEFAULT NULL,
  pos_amount tinyint(2) NOT NULL,
  pos_comment tinyint(2) DEFAULT NULL,
  fmt_date varchar(10) NOT NULL,
  fmt_amount_decimal varchar(1) NOT NULL,
  fmt_amount_thousand varchar(1) DEFAULT NULL,
  pos_partner_alt tinyint(2) DEFAULT NULL,
  pos_partner_alt_pos_key tinyint(2) DEFAULT NULL,
  pos_partner_alt_keyword varchar(255) DEFAULT NULL,
  PRIMARY KEY (formatid),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcf';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS groups;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE groups (
  groupid int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (groupid),
  UNIQUE KEY mgr_i_01 (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mgr';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS user_groups;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE user_groups (
  mur_userid int(10) unsigned NOT NULL,
  mgr_groupid int(10) unsigned NOT NULL,
  PRIMARY KEY (mur_userid,mgr_groupid),
  KEY mug_mgr_pk (mgr_groupid),
  CONSTRAINT mug_mgr_pk FOREIGN KEY (mgr_groupid) REFERENCES `groups` (groupid) ON UPDATE CASCADE,
  CONSTRAINT mug_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mug';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-01-19 22:21:24
INSERT INTO currencies VALUES (1,'EUR',1);
INSERT INTO currencies VALUES (2,'DM',0);
INSERT INTO currencyrates VALUES (1,1.00000,'1970-01-01','2999-12-31');
INSERT INTO currencyrates VALUES (2,1.95583,'1970-01-01','2999-12-31');
INSERT INTO languages VALUES (2,'deutsch');
INSERT INTO languages VALUES (1,'english');
INSERT INTO text VALUES (1,1,'sources of capital','t');
INSERT INTO text VALUES (1,2,'Kapitalquellen','t');
INSERT INTO text VALUES (2,1,'contractual partners','t');
INSERT INTO text VALUES (2,2,'Vertragspartner','t');
INSERT INTO text VALUES (3,1,'predefined flows of money','t');
INSERT INTO text VALUES (3,2,'vordefinierte Geldbewegungen','t');
INSERT INTO text VALUES (4,1,'monthly balances','t');
INSERT INTO text VALUES (4,2,'Monatsabschlüsse','t');
INSERT INTO text VALUES (5,1,'reports','t');
INSERT INTO text VALUES (5,2,'Reports','t');
INSERT INTO text VALUES (6,1,'trends','t');
INSERT INTO text VALUES (6,2,'Trends','t');
INSERT INTO text VALUES (7,1,'search for flows of money','t');
INSERT INTO text VALUES (7,2,'Suche nach Geldbewegungen','t');
INSERT INTO text VALUES (8,1,'add a flow of money','t');
INSERT INTO text VALUES (8,2,'Geldbewegung hinzufügen','t');
INSERT INTO text VALUES (9,1,'report','t');
INSERT INTO text VALUES (9,2,'Report','t');
INSERT INTO text VALUES (10,1,'add source of capital','t');
INSERT INTO text VALUES (10,2,'Kapitalquelle hinzufügen','t');
INSERT INTO text VALUES (11,1,'add contractual partner','t');
INSERT INTO text VALUES (11,2,'Vertragspartner hinzufügen','t');
INSERT INTO text VALUES (12,1,'add predefined flow of money','t');
INSERT INTO text VALUES (12,2,'vordef. Geldbew. hinzufügen','t');
INSERT INTO text VALUES (13,1,'logout','t');
INSERT INTO text VALUES (13,2,'Abmelden','t');
INSERT INTO text VALUES (14,1,'shortcuts','t');
INSERT INTO text VALUES (14,2,'Shortcuts','t');
INSERT INTO text VALUES (15,1,'edit flow of money','t');
INSERT INTO text VALUES (15,2,'Geldbewegung bearbeiten','t');
INSERT INTO text VALUES (16,1,'booking date','t');
INSERT INTO text VALUES (16,2,'Buchungsdatum','t');
INSERT INTO text VALUES (17,1,'invoice date','t');
INSERT INTO text VALUES (17,2,'Rechnungsdatum','t');
INSERT INTO text VALUES (18,1,'amount','t');
INSERT INTO text VALUES (18,2,'Betrag','t');
INSERT INTO text VALUES (19,1,'source of capital','t');
INSERT INTO text VALUES (19,2,'Kapitalquelle','t');
INSERT INTO text VALUES (21,1,'comment','t');
INSERT INTO text VALUES (21,2,'Kommentar','t');
INSERT INTO text VALUES (22,1,'save','t');
INSERT INTO text VALUES (22,2,'Speichern','t');
INSERT INTO text VALUES (23,1,'cancel','t');
INSERT INTO text VALUES (23,2,'Abbrechen','t');
INSERT INTO text VALUES (24,1,'delete flow of money','t');
INSERT INTO text VALUES (24,2,'Geldbewegung löschen','t');
INSERT INTO text VALUES (25,1,'yes','t');
INSERT INTO text VALUES (25,2,'Ja','t');
INSERT INTO text VALUES (26,1,'no','t');
INSERT INTO text VALUES (26,2,'Nein','t');
INSERT INTO text VALUES (27,1,'Do you really want to delete this moneyflow?','t');
INSERT INTO text VALUES (27,2,'Wollen sie diese Geldbewegung wirklich löschen?','t');
INSERT INTO text VALUES (28,1,'all','t');
INSERT INTO text VALUES (28,2,'Alle','t');
INSERT INTO text VALUES (29,1,'add','t');
INSERT INTO text VALUES (29,2,'Hinzufügen','t');
INSERT INTO text VALUES (30,1,'type','t');
INSERT INTO text VALUES (30,2,'Typ','t');
INSERT INTO text VALUES (31,1,'state','t');
INSERT INTO text VALUES (31,2,'Status','t');
INSERT INTO text VALUES (32,1,'account number','t');
INSERT INTO text VALUES (32,2,'Kontonummer','t');
INSERT INTO text VALUES (33,1,'bankcode','t');
INSERT INTO text VALUES (33,2,'Bankleitzahl','t');
INSERT INTO text VALUES (34,1,'valid from','t');
INSERT INTO text VALUES (34,2,'gültig von','t');
INSERT INTO text VALUES (35,1,'valid til','t');
INSERT INTO text VALUES (35,2,'gültig bis','t');
INSERT INTO text VALUES (36,1,'edit','t');
INSERT INTO text VALUES (36,2,'bearbeiten','t');
INSERT INTO text VALUES (37,1,'delete','t');
INSERT INTO text VALUES (37,2,'löschen','t');
INSERT INTO text VALUES (38,1,'edit source of capital','t');
INSERT INTO text VALUES (38,2,'Kapitalquelle bearbeiten','t');
INSERT INTO text VALUES (39,1,'delete source of capital','t');
INSERT INTO text VALUES (39,2,'Kapitalquelle löschen','t');
INSERT INTO text VALUES (40,1,'Do you really want to delete this source of capital?','t');
INSERT INTO text VALUES (40,2,'Wollen sie diese Kapitalquelle wirklich löschen?','t');
INSERT INTO text VALUES (41,1,'name','t');
INSERT INTO text VALUES (41,2,'Name','t');
INSERT INTO text VALUES (42,1,'street','t');
INSERT INTO text VALUES (42,2,'Straße','t');
INSERT INTO text VALUES (43,1,'postcode','t');
INSERT INTO text VALUES (43,2,'Postleitzahl','t');
INSERT INTO text VALUES (44,1,'town','t');
INSERT INTO text VALUES (44,2,'Stadt','t');
INSERT INTO text VALUES (45,1,'country','t');
INSERT INTO text VALUES (45,2,'Land','t');
INSERT INTO text VALUES (46,1,'edit contractual partner','t');
INSERT INTO text VALUES (46,2,'Vertragspartner bearbeiten','t');
INSERT INTO text VALUES (47,1,'Do you really want to delete this contractual partner?','t');
INSERT INTO text VALUES (47,2,'Wollen sie diesen Vertragspartner wirklich löschen','t');
INSERT INTO text VALUES (48,1,'delete contractual partner','t');
INSERT INTO text VALUES (48,2,'Vertragspartner löschen','t');
INSERT INTO text VALUES (49,1,'edit predefined flow of money','t');
INSERT INTO text VALUES (49,2,'vordefinierte Geldbewegung bearbeiten','t');
INSERT INTO text VALUES (51,1,'delete predefined flow of money','t');
INSERT INTO text VALUES (51,2,'vordefinierte Geldbewegung löschen','t');
INSERT INTO text VALUES (52,1,'Do you really want to delete this predefined flow of money?','t');
INSERT INTO text VALUES (52,2,'Wollen sie diese vordefinierte Geldbewegung wirklich löschen?','t');
INSERT INTO text VALUES (53,1,'monthly balance','t');
INSERT INTO text VALUES (53,2,'Monatsabschluss','t');
INSERT INTO text VALUES (54,1,'edit monthly balance','t');
INSERT INTO text VALUES (54,2,'Monatsabschluss bearbeiten','t');
INSERT INTO text VALUES (55,1,'add monthly balance','t');
INSERT INTO text VALUES (55,2,'Monatsabschluss hinzufügen','t');
INSERT INTO text VALUES (56,1,'month','t');
INSERT INTO text VALUES (56,2,'Monat','t');
INSERT INTO text VALUES (57,1,'year','t');
INSERT INTO text VALUES (57,2,'Jahr','t');
INSERT INTO text VALUES (58,1,'reload','t');
INSERT INTO text VALUES (58,2,'neu laden','t');
INSERT INTO text VALUES (59,1,'Do you really want to delete this monthly balance?','t');
INSERT INTO text VALUES (59,2,'Wollen sie diesen Monatsabschluss wirklich löschen?','t');
INSERT INTO text VALUES (60,1,'delete monthly balance','t');
INSERT INTO text VALUES (60,2,'Monatsabschluss löschen','t');
INSERT INTO text VALUES (61,1,'flow of money','t');
INSERT INTO text VALUES (61,2,'Geldbewegung','t');
INSERT INTO text VALUES (62,1,'amount begin','t');
INSERT INTO text VALUES (62,2,'Anfangsbetrag','t');
INSERT INTO text VALUES (63,1,'fixed end','t');
INSERT INTO text VALUES (63,2,'Endbetrag (fix)','t');
INSERT INTO text VALUES (64,1,'calculated end','t');
INSERT INTO text VALUES (64,2,'Endbetrag (errechnet)','t');
INSERT INTO text VALUES (65,1,'difference','t');
INSERT INTO text VALUES (65,2,'Differenz','t');
INSERT INTO text VALUES (66,1,'fixed asset','t');
INSERT INTO text VALUES (66,2,'Gewinn (fix)','t');
INSERT INTO text VALUES (67,1,'calculated asset','t');
INSERT INTO text VALUES (67,2,'Gewinn (errechnet)','t');
INSERT INTO text VALUES (68,1,'Summary','t');
INSERT INTO text VALUES (68,2,'Zusammenfassung','t');
INSERT INTO text VALUES (69,1,'start date','t');
INSERT INTO text VALUES (69,2,'Startdatum','t');
INSERT INTO text VALUES (70,1,'end date','t');
INSERT INTO text VALUES (70,2,'Enddatum','t');
INSERT INTO text VALUES (71,1,'show','t');
INSERT INTO text VALUES (71,2,'anzeigen','t');
INSERT INTO text VALUES (72,1,'searched field','t');
INSERT INTO text VALUES (72,2,'Suchfeld','t');
INSERT INTO text VALUES (73,1,'searchstring','t');
INSERT INTO text VALUES (73,2,'Suchtext','t');
INSERT INTO text VALUES (74,1,'type of date','t');
INSERT INTO text VALUES (74,2,'Datumstyp','t');
INSERT INTO text VALUES (75,1,'special features','t');
INSERT INTO text VALUES (75,2,'spezielle Features','t');
INSERT INTO text VALUES (76,1,'equal','t');
INSERT INTO text VALUES (76,2,'gleich','t');
INSERT INTO text VALUES (77,1,'case sensitive','t');
INSERT INTO text VALUES (77,2,'Groß-, Kleinschreibung beachten','t');
INSERT INTO text VALUES (78,1,'regular expression','t');
INSERT INTO text VALUES (78,2,'regulärer Ausdruck','t');
INSERT INTO text VALUES (79,1,'only negative amounts','t');
INSERT INTO text VALUES (79,2,'nur negative Beträge','t');
INSERT INTO text VALUES (80,1,'1st grouping criteria','t');
INSERT INTO text VALUES (80,2,'1. Gruppierungskriterium','t');
INSERT INTO text VALUES (81,1,'booking year','t');
INSERT INTO text VALUES (81,2,'Buchungsjahr','t');
INSERT INTO text VALUES (82,1,'booking month','t');
INSERT INTO text VALUES (82,2,'Buchungsmonat','t');
INSERT INTO text VALUES (83,1,'search','t');
INSERT INTO text VALUES (83,2,'Suchen','t');
INSERT INTO text VALUES (84,1,'please log in...','t');
INSERT INTO text VALUES (84,2,'Bitte melden sie sich an...','t');
INSERT INTO text VALUES (85,1,'username','t');
INSERT INTO text VALUES (85,2,'Benutzername','t');
INSERT INTO text VALUES (86,1,'password','t');
INSERT INTO text VALUES (86,2,'Passwort','t');
INSERT INTO text VALUES (87,1,'stay logged in','t');
INSERT INTO text VALUES (87,2,'angemeldet bleiben','t');
INSERT INTO text VALUES (88,1,'login','t');
INSERT INTO text VALUES (88,2,'Anmelden','t');
INSERT INTO text VALUES (89,1,'personal settings','t');
INSERT INTO text VALUES (89,2,'persönliche Einstellungen','t');
INSERT INTO text VALUES (90,1,'displayed language','t');
INSERT INTO text VALUES (90,2,'angezeigte Sprache','t');
INSERT INTO text VALUES (91,1,'displayed currency','t');
INSERT INTO text VALUES (91,2,'angezeigte Währung','t');
INSERT INTO text VALUES (92,1,'password (again)','t');
INSERT INTO text VALUES (92,2,'Passwort (Wdhlg.)','t');
INSERT INTO text VALUES (93,1,'system settings','t');
INSERT INTO text VALUES (93,2,'Systemeinstellungen','t');
INSERT INTO text VALUES (94,1,'users','t');
INSERT INTO text VALUES (94,2,'Benutzerkonten','t');
INSERT INTO text VALUES (95,1,'administration','t');
INSERT INTO text VALUES (95,2,'Administration','t');
INSERT INTO text VALUES (96,1,'login allowed','t');
INSERT INTO text VALUES (96,2,'Anmeldung erlaubt','t');
INSERT INTO text VALUES (97,1,'admin','t');
INSERT INTO text VALUES (97,2,'Administrator','t');
INSERT INTO text VALUES (98,1,'new','t');
INSERT INTO text VALUES (98,2,'neu','t');
INSERT INTO text VALUES (99,1,'edit user','t');
INSERT INTO text VALUES (99,2,'Benutzer bearbeiten','t');
INSERT INTO text VALUES (100,1,'add user','t');
INSERT INTO text VALUES (100,2,'Benutzer hinzufügen','t');
INSERT INTO text VALUES (101,1,'delete user','t');
INSERT INTO text VALUES (101,2,'Benutzer löschen','t');
INSERT INTO text VALUES (102,1,'Do you really want to delete this user?','t');
INSERT INTO text VALUES (102,2,'Wollen sie diesen Benutzer wirklich löschen? ','t');
INSERT INTO text VALUES (103,1,'2nd grouping criteria','t');
INSERT INTO text VALUES (103,2,'2. Gruppierungskriterium','t');
INSERT INTO text VALUES (104,1,'sort by','t');
INSERT INTO text VALUES (104,2,'Sortieren nach','t');
INSERT INTO text VALUES (105,1,'grouping','t');
INSERT INTO text VALUES (105,2,'Gruppierung','t');
INSERT INTO text VALUES (106,1,'currencies','t');
INSERT INTO text VALUES (106,2,'Währungen','t');
INSERT INTO text VALUES (107,1,'currency','t');
INSERT INTO text VALUES (107,2,'Währung','t');
INSERT INTO text VALUES (108,1,'rate','t');
INSERT INTO text VALUES (108,2,'Faktor','t');
INSERT INTO text VALUES (109,1,'default currency','t');
INSERT INTO text VALUES (109,2,'Standardwährung','t');
INSERT INTO text VALUES (110,1,'currency rates','t');
INSERT INTO text VALUES (110,2,'Währungsfaktoren','t');
INSERT INTO text VALUES (111,1,'edit currency rate','t');
INSERT INTO text VALUES (111,2,'Währungsfaktor editieren','t');
INSERT INTO text VALUES (112,1,'add currency rate','t');
INSERT INTO text VALUES (112,2,'Währungsfaktor hinzufügen','t');
INSERT INTO text VALUES (113,1,'delete currency rate','t');
INSERT INTO text VALUES (113,2,'Währungsfaktor löschen','t');
INSERT INTO text VALUES (114,1,'Do you really like to delete this Currency Rate?','t');
INSERT INTO text VALUES (114,2,'Wollen Sie wirklich diesen Währungsfaktor löschen?','t');
INSERT INTO text VALUES (115,1,'add currency','t');
INSERT INTO text VALUES (115,2,'Währung hinzufügen','t');
INSERT INTO text VALUES (116,1,'edit currency','t');
INSERT INTO text VALUES (116,2,'Währung bearbeiten','t');
INSERT INTO text VALUES (117,1,'delete currency','t');
INSERT INTO text VALUES (117,2,'Währung löschen','t');
INSERT INTO text VALUES (118,1,'Do you really want to delete this Currency?','t');
INSERT INTO text VALUES (118,2,'Wollen Sie wirklich diese Währung löschen?','t');
INSERT INTO text VALUES (119,1,'No sources of capital where created in the system!','e');
INSERT INTO text VALUES (119,2,'Es wurden keine Kapitalquellen im System angelegt!','e');
INSERT INTO text VALUES (120,1,'You may not delete a source of capital while it is referenced by a flow of money!','e');
INSERT INTO text VALUES (120,2,'Es dürfen keine Kapitalquellen gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e');
INSERT INTO text VALUES (121,1,'There are flows of money for this source of capital outside the period of validitiy you''ve choosen!','e');
INSERT INTO text VALUES (121,2,'Es existieren Geldbewegungen für diese Kapitalquelle ausserhalb des gwählten Gültigkeitszeitraums','e');
INSERT INTO text VALUES (122,1,'The source of capital you''ve choosen is not valid on the booking date you''ve specified!','e');
INSERT INTO text VALUES (122,2,'Das Buchungsdatum liegt nicht im Gültigkeitszeitraum der gewählten Kapitalquelle!','e');
INSERT INTO text VALUES (123,1,'No contractual partners where created in the system!','e');
INSERT INTO text VALUES (123,2,'Es wurden keine Vertragspartner im System angelegt!','e');
INSERT INTO text VALUES (124,1,'You may not delete a contractual partner who is still referenced by a flow of money!','e');
INSERT INTO text VALUES (124,2,'Es dürfen keine Vertragspartner gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e');
INSERT INTO text VALUES (125,1,'The selected currency to display does not exist in the system!','e');
INSERT INTO text VALUES (125,2,'Die zur Anzeige ausgewählte Währung existiert im System nicht!','e');
INSERT INTO text VALUES (126,1,'The currency to display is not specified in the settings!','e');
INSERT INTO text VALUES (126,2,'Es wurde keine Währung zur Anzeige in den Einstellungen angegeben!','e');
INSERT INTO text VALUES (127,1,'The field ''source of capital'' must not be empty!','e');
INSERT INTO text VALUES (127,2,'Das Feld ''Kapitalquelle'' darf nicht leer sein!','e');
INSERT INTO text VALUES (128,1,'The field ''contractual partner'' must not be empty!','e');
INSERT INTO text VALUES (128,2,'Das Feld ''Vertragspartner'' darf nicht leer sein!','e');
INSERT INTO text VALUES (129,1,'The field ''invoice date'' has to be in the format A1A and must be a valid date!','e');
INSERT INTO text VALUES (129,2,'Das Feld ''Rechnungsdatum'' muss dem Format A1A entsprechen und ein gültiges Datum sein!','e');
INSERT INTO text VALUES (130,1,'The field ''booking date'' has to be in the format A1A and must be a valid date!','e');
INSERT INTO text VALUES (130,2,'Das Feld ''Buchungsdatum'' muss dem Format A1A entsprechen und ein gültiges Datum sein!','e');
INSERT INTO text VALUES (131,1,'The field ''comment'' must not be empty!','e');
INSERT INTO text VALUES (131,2,'Das Feld ''Kommentar'' darf nicht leer sein!','e');
INSERT INTO text VALUES (132,1,'The amount ''A1A'' is in a format which is not readable by the system!','e');
INSERT INTO text VALUES (132,2,'Der Betrag ''A1A'' ist in einem vom System nicht lesbaren Format!','e');
INSERT INTO text VALUES (133,1,'It was nothing marked to add!','e');
INSERT INTO text VALUES (133,2,'Es wurde nichts zum hinzufügen markiert!','e');
INSERT INTO text VALUES (134,1,'The specified username or password are invalid!','e');
INSERT INTO text VALUES (134,2,'Der angegebene Benutzername oder das Password sind falsch!','e');
INSERT INTO text VALUES (135,1,'The selected language to display does not exist in the system!','e');
INSERT INTO text VALUES (135,2,'Die zur Anzeige ausgewählte Sprache existiert im System nicht!','e');
INSERT INTO text VALUES (136,1,'The language to display is not specified in the settings!','e');
INSERT INTO text VALUES (136,2,'Es wurde keine Sprache zur Anzeige in den Einstellungen angegeben!','e');
INSERT INTO text VALUES (137,1,'The passwords are not matching!','e');
INSERT INTO text VALUES (137,2,'Die Passwörter sind unterschiedlich!','e');
INSERT INTO text VALUES (138,1,'Your account has been locked!','e');
INSERT INTO text VALUES (138,2,'Ihr Benutzerkonto wurde gesperrt!','e');
INSERT INTO text VALUES (139,1,'You must specifiy an username!','e');
INSERT INTO text VALUES (139,2,'Sie müssen einen Benutzernamen angeben!','e');
INSERT INTO text VALUES (140,1,'You must specifiy a password!','e');
INSERT INTO text VALUES (140,2,'Sie müssen ein Passwort angeben!','e');
INSERT INTO text VALUES (141,1,'No search criteria specified!','e');
INSERT INTO text VALUES (141,2,'Keine Suchkriterien angegeben!','e');
INSERT INTO text VALUES (142,1,'At least one grouping criteria has to be specified!','e');
INSERT INTO text VALUES (142,2,'Mindestens ein Gruppierungskriterium muss angegeben werden!','e');
INSERT INTO text VALUES (143,1,'No matching data found!','e');
INSERT INTO text VALUES (143,2,'Keine passenden Daten gefunden!','e');
INSERT INTO text VALUES (144,1,'At least one currency must be the default currency!','e');
INSERT INTO text VALUES (144,2,'Mindestens eine Währung muss vom Typ Standardwährung sein!','e');
INSERT INTO text VALUES (145,1,'You cannot delete the default currency!','e');
INSERT INTO text VALUES (145,2,'Die Standardwährung kann nicht gelöscht werden!','e');
INSERT INTO text VALUES (146,1,'There exists already a currency rate with the same startingdate!','e');
INSERT INTO text VALUES (146,2,'Es existiert bereits ein Währungsfaktor mit dem selben Startdatum!','e');
INSERT INTO text VALUES (147,1,'The dateformat is not correct please use A1A!','e');
INSERT INTO text VALUES (147,2,'Das Datumsformat ist nicht korrekt bitte benutzen Sie A1A!','e');
INSERT INTO text VALUES (148,1,'A currency rate must not start in the past!','e');
INSERT INTO text VALUES (148,2,'Ein Währungsfaktor darf nicht in der Vergangenheit starten!','e');
INSERT INTO text VALUES (149,1,'Rate must not be empty!','e');
INSERT INTO text VALUES (149,2,'Der Faktor darf nicht leer sein!','e');
INSERT INTO text VALUES (150,1,'Rate must be a number!','e');
INSERT INTO text VALUES (150,2,'Der Faktor muß eine Zahl sein!','e');
INSERT INTO text VALUES (151,1,'There belongs data to this user! Deleting the user will also delete all his data. Are you sure to delete the user?','e');
INSERT INTO text VALUES (151,2,'Der Benutzer hat bereits Daten im System. Wenn Sie diesen Benutzer löschen, werden auch alle seine Daten gelöscht. Sind Sie sicher?','e');
INSERT INTO text VALUES (152,1,'You have to change your password!','e');
INSERT INTO text VALUES (152,2,'Sie müssen Ihr Passwort ändern!','e');
INSERT INTO text VALUES (153,1,'An error occured while deleting the user. The user got not deleted!','e');
INSERT INTO text VALUES (153,2,'Während der Benutzerlöschung trat ein Fehler auf. Der Benutzer wurde nicht gelöscht!','e');
INSERT INTO text VALUES (154,1,'There exists already a moneyflow for this month!','e');
INSERT INTO text VALUES (154,2,'Für diesen Monat existiert bereits ein Monatsabschluss','e');
INSERT INTO text VALUES (155,1,'January','m');
INSERT INTO text VALUES (155,2,'Januar','m');
INSERT INTO text VALUES (156,1,'February','m');
INSERT INTO text VALUES (156,2,'Februar','m');
INSERT INTO text VALUES (157,1,'March','m');
INSERT INTO text VALUES (157,2,'März','m');
INSERT INTO text VALUES (158,1,'April','m');
INSERT INTO text VALUES (158,2,'April','m');
INSERT INTO text VALUES (159,1,'May','m');
INSERT INTO text VALUES (159,2,'Mai','m');
INSERT INTO text VALUES (160,1,'June','m');
INSERT INTO text VALUES (160,2,'Juni','m');
INSERT INTO text VALUES (161,1,'July','m');
INSERT INTO text VALUES (161,2,'Juli','m');
INSERT INTO text VALUES (162,1,'August','m');
INSERT INTO text VALUES (162,2,'August','m');
INSERT INTO text VALUES (163,1,'September','m');
INSERT INTO text VALUES (163,2,'September','m');
INSERT INTO text VALUES (164,1,'October','m');
INSERT INTO text VALUES (164,2,'Oktober','m');
INSERT INTO text VALUES (165,1,'November','m');
INSERT INTO text VALUES (165,2,'November','m');
INSERT INTO text VALUES (166,1,'December','m');
INSERT INTO text VALUES (166,2,'Dezember','m');
INSERT INTO text VALUES (168,1,'amount trends of selected capitalsources','g');
INSERT INTO text VALUES (168,2,'Vermögenstrend der ausgewählten Kapitalquellen','g');
INSERT INTO text VALUES (169,1,'starting from ','g');
INSERT INTO text VALUES (169,2,'von ','g');
INSERT INTO text VALUES (170,1,' until ','g');
INSERT INTO text VALUES (170,2,' bis ','g');
INSERT INTO text VALUES (171,1,'month/year','g');
INSERT INTO text VALUES (171,2,'Monat/Jahr','g');
INSERT INTO text VALUES (172,1,'amount','g');
INSERT INTO text VALUES (172,2,'Betrag','g');
INSERT INTO text VALUES (173,1,'current asset','d');
INSERT INTO text VALUES (173,2,'Umlaufvermögen','d');
INSERT INTO text VALUES (174,1,'long-term asset','d');
INSERT INTO text VALUES (174,2,'Anlagevermögen','d');
INSERT INTO text VALUES (175,1,'non cash','d');
INSERT INTO text VALUES (175,2,'unbar','d');
INSERT INTO text VALUES (176,1,'cash','d');
INSERT INTO text VALUES (176,2,'bar','d');
INSERT INTO text VALUES (177,1,'maximum number of rows to choose automaticly the \"all\" display','t');
INSERT INTO text VALUES (177,2,'maximale Anzahl der Zeilen zur automatischen Wahl der \"Alle\" Anzeige','t');
INSERT INTO text VALUES (178,1,'date format','t');
INSERT INTO text VALUES (178,2,'Datumsformat','t');
INSERT INTO text VALUES (179,1,'day','t');
INSERT INTO text VALUES (179,2,'Tag','t');
INSERT INTO text VALUES (180,1,'The date format you have choosen is not valid!','e');
INSERT INTO text VALUES (180,2,'Das von Ihnen gewählte Datumsformat ist nicht gültig','e');
INSERT INTO text VALUES (181,1,'The contractual partner is not valid on the specified date','e');
INSERT INTO text VALUES (181,2,'Der Vertragspartner ist nicht gültig am angegebnen Datum','e');
INSERT INTO text VALUES (182,1,'languages','t');
INSERT INTO text VALUES (182,2,'Sprachen','t');
INSERT INTO text VALUES (183,1,'language','t');
INSERT INTO text VALUES (183,2,'Sprache','t');
INSERT INTO text VALUES (184,1,'edit language','t');
INSERT INTO text VALUES (184,2,'Sprache bearbeiten','t');
INSERT INTO text VALUES (185,1,'add language','t');
INSERT INTO text VALUES (185,2,'Sprache hinzufügen','t');
INSERT INTO text VALUES (186,1,'source','t');
INSERT INTO text VALUES (186,2,'Vorlage','t');
INSERT INTO text VALUES (187,1,'compare data','t');
INSERT INTO text VALUES (187,2,'Datenvergleich','t');
INSERT INTO text VALUES (188,1,'filename','t');
INSERT INTO text VALUES (188,2,'Dateiname','t');
INSERT INTO text VALUES (189,1,'format','t');
INSERT INTO text VALUES (189,2,'Format','t');
INSERT INTO text VALUES (190,1,'continue','t');
INSERT INTO text VALUES (190,2,'Weiter','t');
INSERT INTO text VALUES (191,1,'fileupload failed','e');
INSERT INTO text VALUES (191,2,'Dateiupload fehlgeschlagen','e');
INSERT INTO text VALUES (192,1,'source','t');
INSERT INTO text VALUES (192,2,'Quelle','t');
INSERT INTO text VALUES (193,1,'file','t');
INSERT INTO text VALUES (193,2,'Datei','t');
INSERT INTO text VALUES (194,1,'lalaMoneyFlow','t');
INSERT INTO text VALUES (194,2,'lalaMoneyFlow','t');
INSERT INTO text VALUES (195,1,'matching data:','t');
INSERT INTO text VALUES (195,2,'passende Daten:','t');
INSERT INTO text VALUES (196,1,'matching data but with wrong capitalsource:','t');
INSERT INTO text VALUES (196,2,'passende Daten jedoch mit falscher Kapitalquelle:','t');
INSERT INTO text VALUES (197,1,'Data the export-file contains, but not the database:','t');
INSERT INTO text VALUES (197,2,'Daten die in der Export-Datei, aber nicht in der Datenbank, enthalten sind:','t');
INSERT INTO text VALUES (198,1,'Data the database contains, but not the export-file:','t');
INSERT INTO text VALUES (198,2,'Daten die in der Datenbank, aber nicht in der Export-Datei, enthalten sind:','t');
INSERT INTO text VALUES (199,1,'The specified file is not parseable! Maybe you''ve selected the wrong format or file?','e');
INSERT INTO text VALUES (199,2,'Die angegebene Datei konnte nicht geparsed werden! Eventuell haben Sie eine falsche Datei oder das falsche Format angegeben?','e');
INSERT INTO text VALUES (200,1,'The amount must be higher or lower than 0 but not 0!','e');
INSERT INTO text VALUES (200,2,'Der Betrag muss ungleich 0 sein!','e');
INSERT INTO text VALUES (201,1,'next month','t');
INSERT INTO text VALUES (201,2,'nächster Monat','t');
INSERT INTO text VALUES (202,1,'previous month','t');
INSERT INTO text VALUES (202,2,'voriger Monat','t');
INSERT INTO text VALUES (203,1,'This name already exists!','e');
INSERT INTO text VALUES (203,2,'Der Name existiert bereits!','e');
INSERT INTO text VALUES (204,1,'Attention!','t');
INSERT INTO text VALUES (204,2,'Achtung!','t');
INSERT INTO text VALUES (205,1,'No monthly settlement for the previous month exists. Do you want to create it now?','t');
INSERT INTO text VALUES (205,2,'Für den vergangenen Monat existiert kein Monatsabschluß. Wollen Sie ihn jetzt anlegen?','t');
INSERT INTO text VALUES (206,1,'1x','t');
INSERT INTO text VALUES (206,2,'1x','t');
INSERT INTO text VALUES (207,1,'last usage','t');
INSERT INTO text VALUES (207,2,'verwendet am','t');
INSERT INTO text VALUES (208,1,'Number of empty lines for adding a moneyflow','t');
INSERT INTO text VALUES (208,2,'Anzahl freier Zeilen beim hinzufügen von Geldbewegungen','t');
INSERT INTO text VALUES (209,1,'pr','t');
INSERT INTO text VALUES (209,2,'pr','t');
INSERT INTO text VALUES (210,1,'group','t');
INSERT INTO text VALUES (210,2,'Gruppe','t');
INSERT INTO templates VALUES ('display_add_language.tpl');
INSERT INTO templates VALUES ('display_add_moneyflow.tpl');
INSERT INTO templates VALUES ('display_analyze_cmp_data.tpl');
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl');
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl');
INSERT INTO templates VALUES ('display_delete_currencies.tpl');
INSERT INTO templates VALUES ('display_delete_currencyrates.tpl');
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl');
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl');
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl');
INSERT INTO templates VALUES ('display_delete_user.tpl');
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl');
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl');
INSERT INTO templates VALUES ('display_edit_currencies.tpl');
INSERT INTO templates VALUES ('display_edit_currencyrates.tpl');
INSERT INTO templates VALUES ('display_edit_language.tpl');
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl');
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl');
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl');
INSERT INTO templates VALUES ('display_edit_user.tpl');
INSERT INTO templates VALUES ('display_event_monthlysettlement.tpl');
INSERT INTO templates VALUES ('display_generate_report.tpl');
INSERT INTO templates VALUES ('display_header.tpl');
INSERT INTO templates VALUES ('display_list_capitalsources.tpl');
INSERT INTO templates VALUES ('display_list_contractpartners.tpl');
INSERT INTO templates VALUES ('display_list_currencies.tpl');
INSERT INTO templates VALUES ('display_list_currencyrates.tpl');
INSERT INTO templates VALUES ('display_list_languages.tpl');
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl');
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl');
INSERT INTO templates VALUES ('display_list_reports.tpl');
INSERT INTO templates VALUES ('display_list_users.tpl');
INSERT INTO templates VALUES ('display_login_user.tpl');
INSERT INTO templates VALUES ('display_personal_settings.tpl');
INSERT INTO templates VALUES ('display_plot_trends.tpl');
INSERT INTO templates VALUES ('display_search.tpl');
INSERT INTO templates VALUES ('display_system_settings.tpl');
INSERT INTO templates VALUES ('display_upfrm_cmp_data.tpl');
INSERT INTO templatevalues VALUES ('display_header.tpl',1);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',1);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',2);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',2);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',2);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',2);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',2);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',2);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',2);
INSERT INTO templatevalues VALUES ('display_header.tpl',2);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',2);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',2);
INSERT INTO templatevalues VALUES ('display_search.tpl',2);
INSERT INTO templatevalues VALUES ('display_header.tpl',3);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',3);
INSERT INTO templatevalues VALUES ('display_header.tpl',4);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',4);
INSERT INTO templatevalues VALUES ('display_header.tpl',5);
INSERT INTO templatevalues VALUES ('display_list_reports.tpl',5);
INSERT INTO templatevalues VALUES ('display_header.tpl',6);
INSERT INTO templatevalues VALUES ('display_plot_trends.tpl',6);
INSERT INTO templatevalues VALUES ('display_header.tpl',7);
INSERT INTO templatevalues VALUES ('display_search.tpl',7);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',8);
INSERT INTO templatevalues VALUES ('display_header.tpl',8);
INSERT INTO templatevalues VALUES ('display_header.tpl',9);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',10);
INSERT INTO templatevalues VALUES ('display_header.tpl',10);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',11);
INSERT INTO templatevalues VALUES ('display_header.tpl',11);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',12);
INSERT INTO templatevalues VALUES ('display_header.tpl',12);
INSERT INTO templatevalues VALUES ('display_header.tpl',13);
INSERT INTO templatevalues VALUES ('display_header.tpl',14);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',15);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',16);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',16);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',16);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',16);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',16);
INSERT INTO templatevalues VALUES ('display_search.tpl',16);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',17);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',17);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',17);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',17);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',17);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',18);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',18);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',18);
INSERT INTO templatevalues VALUES ('display_delete_monthlysettlement.tpl',18);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',18);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',18);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',18);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',18);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',18);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',18);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',18);
INSERT INTO templatevalues VALUES ('display_search.tpl',18);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',19);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',19);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',19);
INSERT INTO templatevalues VALUES ('display_delete_monthlysettlement.tpl',19);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',19);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',19);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',19);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',19);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',19);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',19);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',19);
INSERT INTO templatevalues VALUES ('display_plot_trends.tpl',19);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',19);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',21);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',21);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',21);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',21);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',21);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',21);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',21);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',21);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',21);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',21);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',21);
INSERT INTO templatevalues VALUES ('display_search.tpl',21);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_language.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',22);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',22);
INSERT INTO templatevalues VALUES ('display_header.tpl',22);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',22);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',22);
INSERT INTO templatevalues VALUES ('display_add_language.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_language.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',23);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',23);
INSERT INTO templatevalues VALUES ('display_header.tpl',23);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',24);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_currencies.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_monthlysettlement.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',25);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',25);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',25);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',25);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',25);
INSERT INTO templatevalues VALUES ('display_event_monthlysettlement.tpl',25);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',25);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',25);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',25);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',25);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',25);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_currencies.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_monthlysettlement.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',26);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',26);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',26);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',26);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',26);
INSERT INTO templatevalues VALUES ('display_event_monthlysettlement.tpl',26);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',26);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',26);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',26);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',26);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',26);
INSERT INTO templatevalues VALUES ('display_delete_moneyflow.tpl',27);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',28);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',28);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',28);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',28);
INSERT INTO templatevalues VALUES ('display_list_languages.tpl',28);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',28);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',28);
INSERT INTO templatevalues VALUES ('display_plot_trends.tpl',28);
INSERT INTO templatevalues VALUES ('display_add_language.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_languages.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',29);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',29);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',30);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',30);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',30);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',30);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',31);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',31);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',31);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',31);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',32);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',32);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',32);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',33);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',33);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',33);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',34);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',34);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',34);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',34);
INSERT INTO templatevalues VALUES ('display_header.tpl',34);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',34);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',34);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',35);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',35);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',35);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',35);
INSERT INTO templatevalues VALUES ('display_header.tpl',35);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',35);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',35);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',36);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',36);
INSERT INTO templatevalues VALUES ('display_header.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_languages.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',36);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',36);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',37);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',37);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',37);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',38);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',39);
INSERT INTO templatevalues VALUES ('display_delete_capitalsource.tpl',40);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',41);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',41);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',41);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',42);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',42);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',42);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',43);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',43);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',43);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',44);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',44);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',44);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',45);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',45);
INSERT INTO templatevalues VALUES ('display_list_contractpartners.tpl',45);
INSERT INTO templatevalues VALUES ('display_edit_contractpartner.tpl',46);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',47);
INSERT INTO templatevalues VALUES ('display_delete_contractpartner.tpl',48);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',49);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',51);
INSERT INTO templatevalues VALUES ('display_delete_predefmoneyflow.tpl',52);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',53);
INSERT INTO templatevalues VALUES ('display_list_monthlysettlements.tpl',53);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',54);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',55);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',56);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',56);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',56);
INSERT INTO templatevalues VALUES ('display_search.tpl',56);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',56);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',57);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',57);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',57);
INSERT INTO templatevalues VALUES ('display_search.tpl',57);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',57);
INSERT INTO templatevalues VALUES ('display_edit_monthlysettlement.tpl',58);
INSERT INTO templatevalues VALUES ('display_delete_monthlysettlement.tpl',59);
INSERT INTO templatevalues VALUES ('display_delete_monthlysettlement.tpl',60);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',61);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',62);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',63);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',64);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',65);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',66);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',67);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',68);
INSERT INTO templatevalues VALUES ('display_plot_trends.tpl',69);
INSERT INTO templatevalues VALUES ('display_search.tpl',69);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',69);
INSERT INTO templatevalues VALUES ('display_plot_trends.tpl',70);
INSERT INTO templatevalues VALUES ('display_search.tpl',70);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',70);
INSERT INTO templatevalues VALUES ('display_plot_trends.tpl',71);
INSERT INTO templatevalues VALUES ('display_search.tpl',72);
INSERT INTO templatevalues VALUES ('display_search.tpl',73);
INSERT INTO templatevalues VALUES ('display_search.tpl',74);
INSERT INTO templatevalues VALUES ('display_search.tpl',75);
INSERT INTO templatevalues VALUES ('display_search.tpl',76);
INSERT INTO templatevalues VALUES ('display_search.tpl',77);
INSERT INTO templatevalues VALUES ('display_search.tpl',78);
INSERT INTO templatevalues VALUES ('display_search.tpl',79);
INSERT INTO templatevalues VALUES ('display_search.tpl',80);
INSERT INTO templatevalues VALUES ('display_search.tpl',81);
INSERT INTO templatevalues VALUES ('display_search.tpl',82);
INSERT INTO templatevalues VALUES ('display_search.tpl',83);
INSERT INTO templatevalues VALUES ('display_login_user.tpl',84);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',85);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',85);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',85);
INSERT INTO templatevalues VALUES ('display_login_user.tpl',85);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',86);
INSERT INTO templatevalues VALUES ('display_login_user.tpl',86);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',86);
INSERT INTO templatevalues VALUES ('display_login_user.tpl',87);
INSERT INTO templatevalues VALUES ('display_login_user.tpl',88);
INSERT INTO templatevalues VALUES ('display_header.tpl',89);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',89);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',90);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',90);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',91);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',91);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',92);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',92);
INSERT INTO templatevalues VALUES ('display_header.tpl',93);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',93);
INSERT INTO templatevalues VALUES ('display_header.tpl',94);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',94);
INSERT INTO templatevalues VALUES ('display_header.tpl',95);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',96);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',96);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',96);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',97);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',97);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',97);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',98);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',98);
INSERT INTO templatevalues VALUES ('display_list_users.tpl',98);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',99);
INSERT INTO templatevalues VALUES ('display_edit_user.tpl',100);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',101);
INSERT INTO templatevalues VALUES ('display_delete_user.tpl',102);
INSERT INTO templatevalues VALUES ('display_search.tpl',103);
INSERT INTO templatevalues VALUES ('display_search.tpl',104);
INSERT INTO templatevalues VALUES ('display_search.tpl',105);
INSERT INTO templatevalues VALUES ('display_header.tpl',106);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',106);
INSERT INTO templatevalues VALUES ('display_delete_currencies.tpl',107);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',107);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',107);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',107);
INSERT INTO templatevalues VALUES ('display_header.tpl',107);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',107);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',107);
INSERT INTO templatevalues VALUES ('display_delete_currencies.tpl',108);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',108);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',108);
INSERT INTO templatevalues VALUES ('display_header.tpl',108);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',108);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',109);
INSERT INTO templatevalues VALUES ('display_list_currencies.tpl',109);
INSERT INTO templatevalues VALUES ('display_header.tpl',110);
INSERT INTO templatevalues VALUES ('display_list_currencyrates.tpl',110);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',111);
INSERT INTO templatevalues VALUES ('display_header.tpl',111);
INSERT INTO templatevalues VALUES ('display_edit_currencyrates.tpl',112);
INSERT INTO templatevalues VALUES ('display_header.tpl',112);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',113);
INSERT INTO templatevalues VALUES ('display_delete_currencyrates.tpl',114);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',115);
INSERT INTO templatevalues VALUES ('display_edit_currencies.tpl',116);
INSERT INTO templatevalues VALUES ('display_delete_currencies.tpl',117);
INSERT INTO templatevalues VALUES ('display_delete_currencies.tpl',118);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',177);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',177);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',178);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',178);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',179);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',179);
INSERT INTO templatevalues VALUES ('display_header.tpl',182);
INSERT INTO templatevalues VALUES ('display_list_languages.tpl',182);
INSERT INTO templatevalues VALUES ('display_add_language.tpl',183);
INSERT INTO templatevalues VALUES ('display_list_languages.tpl',183);
INSERT INTO templatevalues VALUES ('display_edit_language.tpl',184);
INSERT INTO templatevalues VALUES ('display_add_language.tpl',185);
INSERT INTO templatevalues VALUES ('display_add_language.tpl',186);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',187);
INSERT INTO templatevalues VALUES ('display_header.tpl',187);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',187);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',188);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',189);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',190);
INSERT INTO templatevalues VALUES ('display_upfrm_cmp_data.tpl',190);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',192);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',193);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',194);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',195);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',196);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',197);
INSERT INTO templatevalues VALUES ('display_analyze_cmp_data.tpl',198);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',201);
INSERT INTO templatevalues VALUES ('display_generate_report.tpl',202);
INSERT INTO templatevalues VALUES ('display_event_monthlysettlement.tpl',204);
INSERT INTO templatevalues VALUES ('display_event_monthlysettlement.tpl',205);
INSERT INTO templatevalues VALUES ('display_edit_predefmoneyflow.tpl',206);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',206);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',207);
INSERT INTO templatevalues VALUES ('display_list_predefmoneyflows.tpl',207);
INSERT INTO templatevalues VALUES ('display_personal_settings.tpl',208);
INSERT INTO templatevalues VALUES ('display_system_settings.tpl',208);
INSERT INTO templatevalues VALUES ('display_add_moneyflow.tpl',209);
INSERT INTO templatevalues VALUES ('display_edit_moneyflow.tpl',209);
INSERT INTO templatevalues VALUES ('display_edit_capitalsource.tpl',210);
INSERT INTO templatevalues VALUES ('display_list_capitalsources.tpl',210);
INSERT INTO domains VALUES ('CAPITALSOURCE_STATE');
INSERT INTO domains VALUES ('CAPITALSOURCE_TYPE');
INSERT INTO domains VALUES ('MONTHS');
INSERT INTO domainvalues VALUES ('MONTHS','1',155);
INSERT INTO domainvalues VALUES ('MONTHS','2',156);
INSERT INTO domainvalues VALUES ('MONTHS','3',157);
INSERT INTO domainvalues VALUES ('MONTHS','4',158);
INSERT INTO domainvalues VALUES ('MONTHS','5',159);
INSERT INTO domainvalues VALUES ('MONTHS','6',160);
INSERT INTO domainvalues VALUES ('MONTHS','7',161);
INSERT INTO domainvalues VALUES ('MONTHS','8',162);
INSERT INTO domainvalues VALUES ('MONTHS','9',163);
INSERT INTO domainvalues VALUES ('MONTHS','10',164);
INSERT INTO domainvalues VALUES ('MONTHS','11',165);
INSERT INTO domainvalues VALUES ('MONTHS','12',166);
INSERT INTO domainvalues VALUES ('CAPITALSOURCE_TYPE','1',173);
INSERT INTO domainvalues VALUES ('CAPITALSOURCE_TYPE','2',174);
INSERT INTO domainvalues VALUES ('CAPITALSOURCE_STATE','1',175);
INSERT INTO domainvalues VALUES ('CAPITALSOURCE_STATE','2',176);
INSERT INTO cmp_data_formats VALUES (1,'Postbank','/^Datum	Wertstellung	Art/','	',1,5,7,4,'DD.MM.YYYY',',','.',6,3,'/^(Überweisung|Dauerauftrag)/');
INSERT INTO cmp_data_formats VALUES (2,'Sparda Bank','/^Buchungstag	Wertstellungstag	Verwendungszweck/','	',1,NULL,4,3,'DD.MM.YYYY',',','.',NULL,NULL,NULL);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET id=0 WHERE username='';
INSERT INTO settings VALUES (0,'displayed_currency','1'),(0,'displayed_language','1'),(0,'max_rows','40'),(0,'date_format','YYYY-MM-DD'),(0,'num_free_moneyflows','1');
INSERT INTO settings (SELECT (SELECT userid FROM users WHERE name='admin'),name,value FROM settings WHERE mur_userid=0);
