-- MySQL dump 10.11
--
-- Host: localhost    Database: moneyflow
-- ------------------------------------------------------
-- Server version	5.0.33

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
CREATE TABLE users (
  id int(10) unsigned NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  att_new tinyint(1) unsigned NOT NULL,
  perm_login tinyint(1) unsigned NOT NULL,
  perm_admin tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY mur_i_01 (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mur';

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS settings;
CREATE TABLE settings (
  userid int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `value` varchar(50) default NULL,
  PRIMARY KEY  (`name`,userid),
  KEY mse_mur_pk (userid),
  CONSTRAINT mse_mur_pk FOREIGN KEY (userid) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mse';

--
-- Table structure for table `capitalsources`
--

DROP TABLE IF EXISTS capitalsources;
CREATE TABLE capitalsources (
  userid int(10) unsigned NOT NULL,
  id int(10) unsigned NOT NULL auto_increment,
  `type` enum('current asset','long-term asset') NOT NULL default 'current asset',
  state enum('non cash','cash') NOT NULL default 'non cash',
  accountnumber bigint(20) default NULL,
  bankcode bigint(20) default NULL,
  `comment` varchar(255) default NULL,
  validtil date NOT NULL default '2999-12-31',
  validfrom date NOT NULL default '1970-01-01',
  PRIMARY KEY  (id,userid),
  KEY mcs_i_01 (userid),
  CONSTRAINT mcs_mur_pk FOREIGN KEY (userid) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcs';

--
-- Table structure for table `contractpartners`
--

DROP TABLE IF EXISTS contractpartners;
CREATE TABLE contractpartners (
  userid int(10) unsigned NOT NULL,
  id int(10) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  street varchar(100) NOT NULL default '',
  postcode int(12) NOT NULL default '0',
  town varchar(100) NOT NULL default '',
  country varchar(100) NOT NULL default '',
  PRIMARY KEY  (id,userid),
  UNIQUE KEY mcp_i_01 (userid,`name`),
  CONSTRAINT mcp_mur_pk FOREIGN KEY (userid) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcp';

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS currencies;
CREATE TABLE currencies (
  id int(10) unsigned NOT NULL auto_increment,
  currency varchar(20) NOT NULL,
  rate float(11,5) default NULL,
  att_default tinyint(1) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY mcu_i_02 (currency),
  UNIQUE KEY mcu_i_01 (att_default)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mcu';

--
-- Table structure for table `moneyflows`
--

DROP TABLE IF EXISTS moneyflows;
CREATE TABLE moneyflows (
  userid int(10) unsigned NOT NULL,
  id int(10) unsigned NOT NULL auto_increment,
  bookingdate date NOT NULL default '0000-00-00',
  invoicedate date NOT NULL default '0000-00-00',
  amount float(8,2) NOT NULL default '0.00',
  capitalsourceid int(10) unsigned NOT NULL,
  contractpartnerid int(10) unsigned NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (id,userid),
  KEY mmf_mcp_pk (contractpartnerid,userid),
  KEY mmf_i_01 (userid,bookingdate),
  KEY mmf_mcs_pk (capitalsourceid,userid),
  CONSTRAINT mmf_mcp_pk FOREIGN KEY (contractpartnerid, userid) REFERENCES contractpartners (id, userid) ON UPDATE CASCADE,
  CONSTRAINT mmf_mcs_pk FOREIGN KEY (capitalsourceid, userid) REFERENCES capitalsources (id, userid) ON UPDATE CASCADE,
  CONSTRAINT mmf_mur_pk FOREIGN KEY (userid) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mmf';

--
-- Table structure for table `monthlysettlements`
--

DROP TABLE IF EXISTS monthlysettlements;
CREATE TABLE monthlysettlements (
  userid int(10) unsigned NOT NULL,
  id int(10) NOT NULL auto_increment,
  capitalsourceid int(10) unsigned NOT NULL,
  `month` tinyint(4) NOT NULL default '0',
  `year` year(4) NOT NULL default '0000',
  amount float(8,2) NOT NULL default '0.00',
  PRIMARY KEY  (id,userid),
  UNIQUE KEY mms_i_01 (userid,`month`,`year`,capitalsourceid),
  KEY mms_mcs_pk (capitalsourceid,userid),
  CONSTRAINT mms_mcs_pk FOREIGN KEY (capitalsourceid, userid) REFERENCES capitalsources (id, userid) ON UPDATE CASCADE,
  CONSTRAINT mms_mur_pk FOREIGN KEY (userid) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mms';

--
-- Table structure for table `predefmoneyflows`
--

DROP TABLE IF EXISTS predefmoneyflows;
CREATE TABLE predefmoneyflows (
  userid int(10) unsigned NOT NULL,
  id int(10) unsigned NOT NULL auto_increment,
  amount float(8,2) NOT NULL default '0.00',
  capitalsourceid int(10) unsigned NOT NULL,
  contractpartnerid int(10) unsigned NOT NULL default '0',
  `comment` varchar(100) NOT NULL default '',
  PRIMARY KEY  (id,userid),
  KEY mpm_mur_pk (userid),
  KEY mpm_mcp_pk (contractpartnerid,userid),
  KEY mpm_mcs_pk (capitalsourceid,userid),
  CONSTRAINT mpm_mcp_pk FOREIGN KEY (contractpartnerid, userid) REFERENCES contractpartners (id, userid) ON UPDATE CASCADE,
  CONSTRAINT mpm_mcs_pk FOREIGN KEY (capitalsourceid, userid) REFERENCES capitalsources (id, userid) ON UPDATE CASCADE,
  CONSTRAINT mpm_mur_pk FOREIGN KEY (userid) REFERENCES users (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mpm';

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS languages;
CREATE TABLE languages (
  id int(10) unsigned NOT NULL auto_increment,
  `language` varchar(10) NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY mla_i_01 (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mla';

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS templates;
CREATE TABLE templates (
  `name` varchar(50) NOT NULL,
  textid int(10) unsigned NOT NULL,
  PRIMARY KEY  (`name`,textid),
  KEY mtm_mtx_pk (textid),
  CONSTRAINT mtm_mtx_pk FOREIGN KEY (textid) REFERENCES `text` (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtm';

--
-- Table structure for table `text`
--

DROP TABLE IF EXISTS text;
CREATE TABLE `text` (
  id int(10) unsigned NOT NULL,
  languageid int(10) unsigned NOT NULL,
  `text` varchar(255) NOT NULL,
  `type` enum('t','m','e') NOT NULL,
  PRIMARY KEY  (id,languageid,`type`),
  KEY mte_mla_pk (languageid),
  CONSTRAINT mte_mla_pk FOREIGN KEY (languageid) REFERENCES languages (id) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='mtx';
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2007-02-02 13:22:11
INSERT INTO currencies VALUES (1,'EUR',1.00000,1);
INSERT INTO currencies VALUES (2,'DM',1.95583,NULL);
INSERT INTO languages VALUES (2,'deutsch');
INSERT INTO languages VALUES (1,'english');
INSERT INTO text VALUES (1,1,'sources of capital','t');
INSERT INTO text VALUES (1,1,'January','m');
INSERT INTO text VALUES (1,1,'No sources of capital where created in the system!','e');
INSERT INTO text VALUES (1,2,'Kapitalquellen','t');
INSERT INTO text VALUES (1,2,'Januar','m');
INSERT INTO text VALUES (1,2,'Es wurden keine Kapitalquellen im System angelegt!','e');
INSERT INTO text VALUES (2,1,'contractual partners','t');
INSERT INTO text VALUES (2,1,'February','m');
INSERT INTO text VALUES (2,1,'You may not delete a source of capital while it is referenced by a flow of money!','e');
INSERT INTO text VALUES (2,2,'Vertragspartner','t');
INSERT INTO text VALUES (2,2,'Februar','m');
INSERT INTO text VALUES (2,2,'Es dürfen keine Kapitalquellen gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e');
INSERT INTO text VALUES (3,1,'predefined flows of money','t');
INSERT INTO text VALUES (3,1,'March','m');
INSERT INTO text VALUES (3,1,'There are flows of money for this source of capital outside the period of validitiy you''ve choosen!','e');
INSERT INTO text VALUES (3,2,'vordefinierte Geldbewegungen','t');
INSERT INTO text VALUES (3,2,'März','m');
INSERT INTO text VALUES (3,2,'Es existieren Geldbewegungen für diese Kapitalquelle ausserhalb des gwählten Gültigkeitszeitraums','e');
INSERT INTO text VALUES (4,1,'monthly balances','t');
INSERT INTO text VALUES (4,1,'April','m');
INSERT INTO text VALUES (4,1,'The source of capital you''ve choosen is not valid on the booking date you''ve specified!','e');
INSERT INTO text VALUES (4,2,'Monatsabschlüsse','t');
INSERT INTO text VALUES (4,2,'April','m');
INSERT INTO text VALUES (4,2,'Das Buchungsdatum liegt nicht im Gültigkeitszeitraum der gewählten Kapitalquelle!','e');
INSERT INTO text VALUES (5,1,'reports','t');
INSERT INTO text VALUES (5,1,'May','m');
INSERT INTO text VALUES (5,1,'No contractual partners where created in the system!','e');
INSERT INTO text VALUES (5,2,'Reports','t');
INSERT INTO text VALUES (5,2,'Mai','m');
INSERT INTO text VALUES (5,2,'Es wurden keine Vertragspartner im System angelegt!','e');
INSERT INTO text VALUES (6,1,'trends','t');
INSERT INTO text VALUES (6,1,'June','m');
INSERT INTO text VALUES (6,1,'You may not delete a contractual partner who is still referenced by a flow of money!','e');
INSERT INTO text VALUES (6,2,'Trends','t');
INSERT INTO text VALUES (6,2,'Juni','m');
INSERT INTO text VALUES (6,2,'Es dürfen keine Vertragspartner gelöscht werden, welche noch von einer Geldbewegung referenziert sind!','e');
INSERT INTO text VALUES (7,1,'search for flows of money','t');
INSERT INTO text VALUES (7,1,'July','m');
INSERT INTO text VALUES (7,1,'The selected currency to display does not exist in the system!','e');
INSERT INTO text VALUES (7,2,'Suche nach Geldbewegungen','t');
INSERT INTO text VALUES (7,2,'Juli','m');
INSERT INTO text VALUES (7,2,'Die zur Anzeige ausgewählte Währung existiert im System nicht!','e');
INSERT INTO text VALUES (8,1,'add a flow of money','t');
INSERT INTO text VALUES (8,1,'August','m');
INSERT INTO text VALUES (8,1,'The currency to display is not specified in the settings!','e');
INSERT INTO text VALUES (8,2,'Geldbewegung hinzufügen','t');
INSERT INTO text VALUES (8,2,'August','m');
INSERT INTO text VALUES (8,2,'Es wurde keine Währung zur Anzeige in den Einstellungen angegeben!','e');
INSERT INTO text VALUES (9,1,'report','t');
INSERT INTO text VALUES (9,1,'September','m');
INSERT INTO text VALUES (9,1,'The field ''source of capital'' must not be empty!','e');
INSERT INTO text VALUES (9,2,'Report','t');
INSERT INTO text VALUES (9,2,'September','m');
INSERT INTO text VALUES (9,2,'Das Feld ''Kapitalquelle'' darf nicht leer sein!','e');
INSERT INTO text VALUES (10,1,'add source of capital','t');
INSERT INTO text VALUES (10,1,'October','m');
INSERT INTO text VALUES (10,1,'The field ''contractual partner'' must not be empty!','e');
INSERT INTO text VALUES (10,2,'Kapitalquelle hinzufügen','t');
INSERT INTO text VALUES (10,2,'Oktober','m');
INSERT INTO text VALUES (10,2,'Das Feld ''Vertragspartner'' darf nicht leer sein!','e');
INSERT INTO text VALUES (11,1,'add contractual partner','t');
INSERT INTO text VALUES (11,1,'November','m');
INSERT INTO text VALUES (11,1,'The field ''invoice date'' has to be in the format YYYY-MM-DD and must be a valid date!','e');
INSERT INTO text VALUES (11,2,'Vertragspartner hinzufügen','t');
INSERT INTO text VALUES (11,2,'November','m');
INSERT INTO text VALUES (11,2,'Das Feld ''Rechnungsdatum'' muss dem Format YYYY-MM-DD entsprechen und ein g&uuml;ltiges Datum sein!','e');
INSERT INTO text VALUES (12,1,'add predefined flow of money','t');
INSERT INTO text VALUES (12,1,'December','m');
INSERT INTO text VALUES (12,1,'The field ''booking date'' has to be in the format YYYY-MM-DD and must be a valid date!','e');
INSERT INTO text VALUES (12,2,'vordef. Geldbew. hinzufügen','t');
INSERT INTO text VALUES (12,2,'Dezember','m');
INSERT INTO text VALUES (12,2,'Das Feld ''Buchungsdatum'' muss dem Format YYYY-MM-DD entsprechen und ein g&uuml;ltiges Datum sein!','e');
INSERT INTO text VALUES (13,1,'logout','t');
INSERT INTO text VALUES (13,1,'The field ''comment'' must not be empty!','e');
INSERT INTO text VALUES (13,2,'Abmelden','t');
INSERT INTO text VALUES (13,2,'Das Feld ''Kommentar'' darf nicht leer sein!','e');
INSERT INTO text VALUES (14,1,'shortcuts','t');
INSERT INTO text VALUES (14,1,'The amount ''A1A'' is in a format which is not readable by the system!','e');
INSERT INTO text VALUES (14,2,'Shortcuts','t');
INSERT INTO text VALUES (14,2,'Der Betrag ''A1A'' ist in einem vom System nicht lesbaren Format!','e');
INSERT INTO text VALUES (15,1,'edit flow of money','t');
INSERT INTO text VALUES (15,1,'It was nothing marked to add!','e');
INSERT INTO text VALUES (15,2,'Geldbewegung bearbeiten','t');
INSERT INTO text VALUES (15,2,'Es wurde nichts zum hinzufügen markiert!','e');
INSERT INTO text VALUES (16,1,'booking date','t');
INSERT INTO text VALUES (16,1,'The specified username or password are invalid!','e');
INSERT INTO text VALUES (16,2,'Buchungsdatum','t');
INSERT INTO text VALUES (16,2,'Der angegebene Benutzername oder das Password sind falsch!','e');
INSERT INTO text VALUES (17,1,'invoice date','t');
INSERT INTO text VALUES (17,1,'The selected language to display does not exist in the system!','e');
INSERT INTO text VALUES (17,2,'Rechnungsdatum','t');
INSERT INTO text VALUES (17,2,'Die zur Anzeige ausgewählte Sprache existiert im System nicht!','e');
INSERT INTO text VALUES (18,1,'amount','t');
INSERT INTO text VALUES (18,1,'The language to display is not specified in the settings!','e');
INSERT INTO text VALUES (18,2,'Betrag','t');
INSERT INTO text VALUES (18,2,'Es wurde keine Sprache zur Anzeige in den Einstellungen angegeben!','e');
INSERT INTO text VALUES (19,1,'source of capital','t');
INSERT INTO text VALUES (19,1,'The passwords are not matching!','e');
INSERT INTO text VALUES (19,2,'Kapitalquelle','t');
INSERT INTO text VALUES (19,2,'Die Passwörter sind unterschiedlich!','e');
INSERT INTO text VALUES (20,1,'contractual partner','t');
INSERT INTO text VALUES (20,1,'Your account has been locked!','e');
INSERT INTO text VALUES (20,2,'Vertragspartner','t');
INSERT INTO text VALUES (20,2,'Ihr Benutzerkonto wurde gesperrt!','e');
INSERT INTO text VALUES (21,1,'comment','t');
INSERT INTO text VALUES (21,1,'You must specifiy an username!','e');
INSERT INTO text VALUES (21,2,'Kommentar','t');
INSERT INTO text VALUES (21,2,'Sie müssen einen Benutzernamen angeben!','e');
INSERT INTO text VALUES (22,1,'save','t');
INSERT INTO text VALUES (22,1,'You must specifiy a password!','e');
INSERT INTO text VALUES (22,2,'Speichern','t');
INSERT INTO text VALUES (22,2,'Sie müssen ein Passwort angeben!','e');
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
INSERT INTO text VALUES (50,1,'add predefined flow of money','t');
INSERT INTO text VALUES (50,2,'vordefinierte Geldbewegung hinzufügen','t');
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
INSERT INTO text VALUES (80,1,'group by','t');
INSERT INTO text VALUES (80,2,'gruppieren nach','t');
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
INSERT INTO text VALUES (96,1,'login','t');
INSERT INTO text VALUES (96,2,'Anmeldung','t');
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
INSERT INTO templates VALUES ('display_header.tpl',1);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',1);
INSERT INTO templates VALUES ('display_header.tpl',2);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',2);
INSERT INTO templates VALUES ('display_header.tpl',3);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',3);
INSERT INTO templates VALUES ('display_header.tpl',4);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',4);
INSERT INTO templates VALUES ('display_header.tpl',5);
INSERT INTO templates VALUES ('display_list_reports.tpl',5);
INSERT INTO templates VALUES ('display_header.tpl',6);
INSERT INTO templates VALUES ('display_plot_trends.tpl',6);
INSERT INTO templates VALUES ('display_header.tpl',7);
INSERT INTO templates VALUES ('display_search.tpl',7);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',8);
INSERT INTO templates VALUES ('display_header.tpl',8);
INSERT INTO templates VALUES ('display_header.tpl',9);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',10);
INSERT INTO templates VALUES ('display_header.tpl',10);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',11);
INSERT INTO templates VALUES ('display_header.tpl',11);
INSERT INTO templates VALUES ('display_header.tpl',12);
INSERT INTO templates VALUES ('display_header.tpl',13);
INSERT INTO templates VALUES ('display_header.tpl',14);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',15);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',16);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',16);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',16);
INSERT INTO templates VALUES ('display_generate_report.tpl',16);
INSERT INTO templates VALUES ('display_search.tpl',16);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',17);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',17);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',17);
INSERT INTO templates VALUES ('display_generate_report.tpl',17);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',18);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',18);
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl',18);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',18);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',18);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',18);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',18);
INSERT INTO templates VALUES ('display_generate_report.tpl',18);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',18);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',18);
INSERT INTO templates VALUES ('display_search.tpl',18);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',19);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',19);
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl',19);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',19);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',19);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',19);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',19);
INSERT INTO templates VALUES ('display_generate_report.tpl',19);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',19);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',19);
INSERT INTO templates VALUES ('display_plot_trends.tpl',19);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',20);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',20);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',20);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',20);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',20);
INSERT INTO templates VALUES ('display_generate_report.tpl',20);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',20);
INSERT INTO templates VALUES ('display_search.tpl',20);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',21);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',21);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',21);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',21);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',21);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',21);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',21);
INSERT INTO templates VALUES ('display_generate_report.tpl',21);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',21);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',21);
INSERT INTO templates VALUES ('display_search.tpl',21);
INSERT INTO templates VALUES ('display_add_moneyflow.tpl',22);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',22);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',22);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',22);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',22);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',22);
INSERT INTO templates VALUES ('display_edit_user.tpl',22);
INSERT INTO templates VALUES ('display_personal_settings.tpl',22);
INSERT INTO templates VALUES ('display_system_settings.tpl',22);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',23);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',23);
INSERT INTO templates VALUES ('display_edit_moneyflow.tpl',23);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',23);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',23);
INSERT INTO templates VALUES ('display_edit_user.tpl',23);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',24);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',25);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',25);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',25);
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl',25);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',25);
INSERT INTO templates VALUES ('display_delete_user.tpl',25);
INSERT INTO templates VALUES ('display_edit_user.tpl',25);
INSERT INTO templates VALUES ('display_list_users.tpl',25);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',26);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',26);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',26);
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl',26);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',26);
INSERT INTO templates VALUES ('display_delete_user.tpl',26);
INSERT INTO templates VALUES ('display_edit_user.tpl',26);
INSERT INTO templates VALUES ('display_list_users.tpl',26);
INSERT INTO templates VALUES ('display_delete_moneyflow.tpl',27);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',28);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',28);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',28);
INSERT INTO templates VALUES ('display_list_users.tpl',28);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',29);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',29);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',29);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',29);
INSERT INTO templates VALUES ('display_list_users.tpl',29);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',30);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',30);
INSERT INTO templates VALUES ('display_generate_report.tpl',30);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',30);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',31);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',31);
INSERT INTO templates VALUES ('display_generate_report.tpl',31);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',31);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',32);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',32);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',32);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',33);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',33);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',33);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',34);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',34);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',34);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',35);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',35);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',35);
INSERT INTO templates VALUES ('display_generate_report.tpl',36);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',36);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',36);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',36);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',36);
INSERT INTO templates VALUES ('display_list_users.tpl',36);
INSERT INTO templates VALUES ('display_generate_report.tpl',37);
INSERT INTO templates VALUES ('display_list_capitalsources.tpl',37);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',37);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',37);
INSERT INTO templates VALUES ('display_list_predefmoneyflows.tpl',37);
INSERT INTO templates VALUES ('display_list_users.tpl',37);
INSERT INTO templates VALUES ('display_edit_capitalsource.tpl',38);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',39);
INSERT INTO templates VALUES ('display_delete_capitalsource.tpl',40);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',41);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',41);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',41);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',42);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',42);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',42);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',43);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',43);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',43);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',44);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',44);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',44);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',45);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',45);
INSERT INTO templates VALUES ('display_list_contractpartners.tpl',45);
INSERT INTO templates VALUES ('display_edit_contractpartner.tpl',46);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',47);
INSERT INTO templates VALUES ('display_delete_contractpartner.tpl',48);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',49);
INSERT INTO templates VALUES ('display_edit_predefmoneyflow.tpl',50);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',51);
INSERT INTO templates VALUES ('display_delete_predefmoneyflow.tpl',52);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',53);
INSERT INTO templates VALUES ('display_list_monthlysettlements.tpl',53);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',54);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',55);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',56);
INSERT INTO templates VALUES ('display_generate_report.tpl',56);
INSERT INTO templates VALUES ('display_search.tpl',56);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',57);
INSERT INTO templates VALUES ('display_generate_report.tpl',57);
INSERT INTO templates VALUES ('display_search.tpl',57);
INSERT INTO templates VALUES ('display_edit_monthlysettlement.tpl',58);
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl',59);
INSERT INTO templates VALUES ('display_delete_monthlysettlement.tpl',60);
INSERT INTO templates VALUES ('display_generate_report.tpl',61);
INSERT INTO templates VALUES ('display_generate_report.tpl',62);
INSERT INTO templates VALUES ('display_generate_report.tpl',63);
INSERT INTO templates VALUES ('display_generate_report.tpl',64);
INSERT INTO templates VALUES ('display_generate_report.tpl',65);
INSERT INTO templates VALUES ('display_generate_report.tpl',66);
INSERT INTO templates VALUES ('display_generate_report.tpl',67);
INSERT INTO templates VALUES ('display_generate_report.tpl',68);
INSERT INTO templates VALUES ('display_plot_trends.tpl',69);
INSERT INTO templates VALUES ('display_search.tpl',69);
INSERT INTO templates VALUES ('display_plot_trends.tpl',70);
INSERT INTO templates VALUES ('display_search.tpl',70);
INSERT INTO templates VALUES ('display_plot_trends.tpl',71);
INSERT INTO templates VALUES ('display_search.tpl',72);
INSERT INTO templates VALUES ('display_search.tpl',73);
INSERT INTO templates VALUES ('display_search.tpl',74);
INSERT INTO templates VALUES ('display_search.tpl',75);
INSERT INTO templates VALUES ('display_search.tpl',76);
INSERT INTO templates VALUES ('display_search.tpl',77);
INSERT INTO templates VALUES ('display_search.tpl',78);
INSERT INTO templates VALUES ('display_search.tpl',79);
INSERT INTO templates VALUES ('display_search.tpl',80);
INSERT INTO templates VALUES ('display_search.tpl',81);
INSERT INTO templates VALUES ('display_search.tpl',82);
INSERT INTO templates VALUES ('display_search.tpl',83);
INSERT INTO templates VALUES ('display_login_user.tpl',84);
INSERT INTO templates VALUES ('display_delete_user.tpl',85);
INSERT INTO templates VALUES ('display_edit_user.tpl',85);
INSERT INTO templates VALUES ('display_list_users.tpl',85);
INSERT INTO templates VALUES ('display_login_user.tpl',85);
INSERT INTO templates VALUES ('display_edit_user.tpl',86);
INSERT INTO templates VALUES ('display_login_user.tpl',86);
INSERT INTO templates VALUES ('display_personal_settings.tpl',86);
INSERT INTO templates VALUES ('display_login_user.tpl',87);
INSERT INTO templates VALUES ('display_login_user.tpl',88);
INSERT INTO templates VALUES ('display_header.tpl',89);
INSERT INTO templates VALUES ('display_personal_settings.tpl',89);
INSERT INTO templates VALUES ('display_personal_settings.tpl',90);
INSERT INTO templates VALUES ('display_system_settings.tpl',90);
INSERT INTO templates VALUES ('display_personal_settings.tpl',91);
INSERT INTO templates VALUES ('display_system_settings.tpl',91);
INSERT INTO templates VALUES ('display_edit_user.tpl',92);
INSERT INTO templates VALUES ('display_personal_settings.tpl',92);
INSERT INTO templates VALUES ('display_header.tpl',93);
INSERT INTO templates VALUES ('display_system_settings.tpl',93);
INSERT INTO templates VALUES ('display_header.tpl',94);
INSERT INTO templates VALUES ('display_list_users.tpl',94);
INSERT INTO templates VALUES ('display_header.tpl',95);
INSERT INTO templates VALUES ('display_delete_user.tpl',96);
INSERT INTO templates VALUES ('display_edit_user.tpl',96);
INSERT INTO templates VALUES ('display_list_users.tpl',96);
INSERT INTO templates VALUES ('display_delete_user.tpl',97);
INSERT INTO templates VALUES ('display_edit_user.tpl',97);
INSERT INTO templates VALUES ('display_list_users.tpl',97);
INSERT INTO templates VALUES ('display_delete_user.tpl',98);
INSERT INTO templates VALUES ('display_edit_user.tpl',98);
INSERT INTO templates VALUES ('display_list_users.tpl',98);
INSERT INTO templates VALUES ('display_edit_user.tpl',99);
INSERT INTO templates VALUES ('display_edit_user.tpl',100);
INSERT INTO templates VALUES ('display_delete_user.tpl',101);
INSERT INTO templates VALUES ('display_delete_user.tpl',102);
INSERT INTO settings VALUES (0,'displayed_currency','1'),(0,'displayed_language','1');
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997',1,1,1);
INSERT INTO users (name,password,perm_login,perm_admin,att_new) VALUES ('','',0,0,0);
UPDATE users SET id=0 WHERE username='';
