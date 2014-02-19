-- MySQL dump 10.13  Distrib 5.5.36, for FreeBSD9.2 (amd64)
--
-- Host: localhost    Database: moneyflow
-- ------------------------------------------------------
-- Server version	5.5.36-log

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
-- Table structure for table `access`
--

DROP TABLE IF EXISTS access;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE access (
  id int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  att_user tinyint(1) unsigned NOT NULL,
  att_change_password tinyint(1) unsigned NOT NULL,
  perm_login tinyint(1) unsigned NOT NULL,
  perm_admin tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY mac_i_01 (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mac';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `access_relation`
--

DROP TABLE IF EXISTS access_relation;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE access_relation (
  id int(10) unsigned NOT NULL,
  ref_id int(10) unsigned NOT NULL,
  validfrom date NOT NULL,
  validtil date NOT NULL,
  PRIMARY KEY (id,validfrom),
  KEY mar_i_01 (ref_id),
  CONSTRAINT mar_mac_pk_01 FOREIGN KEY (id) REFERENCES access (id) ON UPDATE CASCADE,
  CONSTRAINT mar_mac_pk_02 FOREIGN KEY (ref_id) REFERENCES access (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `access_flattened`
--

DROP TABLE IF EXISTS access_flattened;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE access_flattened (
  id int(10) unsigned NOT NULL,
  validfrom date NOT NULL,
  validtil date NOT NULL,
  id_level_1 int(10) unsigned NOT NULL,
  id_level_2 int(10) unsigned NOT NULL,
  id_level_3 int(10) unsigned DEFAULT NULL,
  id_level_4 int(10) unsigned DEFAULT NULL,
  id_level_5 int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (id,validfrom),
  KEY maf_i_01 (id_level_1),
  KEY maf_i_02 (id_level_2),
  KEY maf_i_03 (id_level_3),
  KEY maf_i_04 (id_level_4),
  KEY maf_i_05 (id_level_5),
  CONSTRAINT maf_mac_pk_06 FOREIGN KEY (id_level_5) REFERENCES access (id),
  CONSTRAINT maf_mac_pk_01 FOREIGN KEY (id) REFERENCES access (id),
  CONSTRAINT maf_mac_pk_02 FOREIGN KEY (id_level_1) REFERENCES access (id),
  CONSTRAINT maf_mac_pk_03 FOREIGN KEY (id_level_2) REFERENCES access (id),
  CONSTRAINT maf_mac_pk_04 FOREIGN KEY (id_level_3) REFERENCES access (id),
  CONSTRAINT maf_mac_pk_05 FOREIGN KEY (id_level_4) REFERENCES access (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='maf';
/*!40101 SET character_set_client = @saved_cs_client */;

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
  accountnumber bigint(20) unsigned DEFAULT NULL,
  bankcode bigint(20) unsigned DEFAULT NULL,
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
  street varchar(100) DEFAULT '',
  postcode int(12) DEFAULT '0',
  town varchar(100) DEFAULT '',
  country varchar(100) DEFAULT '',
  PRIMARY KEY (contractpartnerid),
  UNIQUE KEY mcp_i_01 (mur_userid,`name`),
  CONSTRAINT mcp_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcp';
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
  mac_id_creator int(10) unsigned NOT NULL,
  mac_id_accessor int(10) unsigned NOT NULL,
  bookingdate date NOT NULL DEFAULT '0000-00-00',
  invoicedate date NOT NULL DEFAULT '0000-00-00',
  amount float(8,2) NOT NULL DEFAULT '0.00',
  mcs_capitalsourceid int(10) unsigned NOT NULL,
  mcp_contractpartnerid int(10) unsigned NOT NULL,
  `comment` varchar(100) NOT NULL DEFAULT '',
  mpa_postingaccountid int(10) unsigned NOT NULL,
  private tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (moneyflowid,mur_userid),
  KEY mmf_i_01 (mur_userid,bookingdate),
  KEY mmf_i_02 (bookingdate),
  KEY mmf_mcs_pk (mcs_capitalsourceid),
  KEY mmf_mcp_pk (mcp_contractpartnerid),
  KEY mmf_mpa_pk (mpa_postingaccountid),
  KEY mmf_i_03 (mac_id_creator),
  KEY mmf_i_04 (mac_id_accessor),
  CONSTRAINT moneyflows_ibfk_2 FOREIGN KEY (mac_id_accessor) REFERENCES access (id) ON UPDATE CASCADE,
  CONSTRAINT mmf_mcp_pk FOREIGN KEY (mcp_contractpartnerid) REFERENCES contractpartners (contractpartnerid) ON UPDATE CASCADE,
  CONSTRAINT mmf_mcs_pk FOREIGN KEY (mcs_capitalsourceid) REFERENCES capitalsources (capitalsourceid) ON UPDATE CASCADE,
  CONSTRAINT mmf_mpa_pk FOREIGN KEY (mpa_postingaccountid) REFERENCES postingaccounts (postingaccountid) ON UPDATE CASCADE,
  CONSTRAINT mmf_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE,
  CONSTRAINT moneyflows_ibfk_1 FOREIGN KEY (mac_id_creator) REFERENCES access (id) ON UPDATE CASCADE
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
-- Table structure for table `postingaccounts`
--

DROP TABLE IF EXISTS postingaccounts;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE postingaccounts (
  mur_userid int(10) unsigned NOT NULL,
  postingaccountid int(10) unsigned NOT NULL AUTO_INCREMENT,
  postingaccountname varchar(20) NOT NULL,
  PRIMARY KEY (postingaccountid),
  UNIQUE KEY mpa_i_01 (postingaccountname),
  KEY mpa_mur_pk (mur_userid),
  CONSTRAINT mpa_mur_pk FOREIGN KEY (mur_userid) REFERENCES `users` (userid) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mpa';
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
  validfrom date NOT NULL,
  validtil date NOT NULL,
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

-- Dump completed on 2014-02-19 21:52:51
INSERT INTO cmp_data_formats VALUES (1,'Postbank Direkt','/^Datum	Wertstellung	Art/','	',1,5,7,4,'DD.MM.YYYY',',','.',6,3,'/^(Überweisung|Dauerauftrag)/');
INSERT INTO cmp_data_formats VALUES (2,'Sparda Bank','/^Buchungstag	Wertstellungstag	Verwendungszweck/','	',1,NULL,4,3,'DD.MM.YYYY',',','.',NULL,NULL,NULL);
INSERT INTO cmp_data_formats VALUES (3,'Postbank Online','/^\"Buchungstag\";\"Wertstellung\";\"Umsatzart\"/',';',1,6,7,4,'DD.MM.YYYY',',','.',5,3,'/^(Gutschrift|Gehalt)/');
INSERT INTO cmp_data_formats VALUES (4,'XML camt.052.001.03','camt','',0,NULL,0,NULL,'','',NULL,NULL,NULL,NULL);
INSERT INTO access (name,password,att_user,att_change_password,perm_login,perm_admin) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1,1);
INSERT INTO access (name,password,att_user,att_change_password,perm_login,perm_admin) VALUES ('root','NULL',0,0,0,0);
UPDATE access SET id=0 WHERE name='root');
INSERT INTO access_relation (id,ref_id,validfrom,validtil) VALUES (1,0,'0001-01-01','2999-12-31');
INSERT INTO access_flattened (id,validfrom,validtil,id_level_1,id_level_2) VALUES (1,'0001-01-01','2999-12-31',1,0);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET userid=0 WHERE name='';
INSERT INTO settings VALUES (0,'displayed_language','1'),(0,'max_rows','40'),(0,'date_format','YYYY-MM-DD'),(0,'num_free_moneyflows','1');
INSERT INTO settings (SELECT (SELECT userid FROM users WHERE name='admin'),name,value FROM settings WHERE mur_userid=0);
