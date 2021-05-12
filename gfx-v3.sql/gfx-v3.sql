-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 23. Oktober 2005 um 17:25
-- Server Version: 4.1.13
-- PHP-Version: 5.0.4
-- 
-- Datenbank: `gfx-v3`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `conf_settings`
-- 

CREATE TABLE `conf_settings` (
  `conf_id` int(10) NOT NULL auto_increment,
  `conf_title` text collate latin1_general_ci NOT NULL,
  `conf_description` text collate latin1_general_ci NOT NULL,
  `conf_group` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_type` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_key` text collate latin1_general_ci NOT NULL,
  `conf_value` text collate latin1_general_ci NOT NULL,
  `conf_default` text collate latin1_general_ci NOT NULL,
  `conf_extra` text collate latin1_general_ci NOT NULL,
  `conf_protected` tinyint(1) NOT NULL default '0',
  `conf_start_group` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_end_group` tinyint(1) NOT NULL default '0',
  `conf_add_cache` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`conf_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `conf_settings`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `conf_settings_titles`
-- 

CREATE TABLE `conf_settings_titles` (
  `conf_title_id` smallint(3) NOT NULL auto_increment,
  `conf_title_title` varchar(255) collate latin1_general_ci NOT NULL default '',
  `conf_title_desc` text collate latin1_general_ci NOT NULL,
  `conf_title_count` smallint(3) NOT NULL default '0',
  PRIMARY KEY  (`conf_title_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `conf_settings_titles`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `groups`
-- 

CREATE TABLE `groups` (
  `id` int(10) NOT NULL auto_increment,
  `group_name` varchar(250) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

-- 
-- Daten für Tabelle `groups`
-- 

INSERT INTO `groups` VALUES (1, 'Validating');
INSERT INTO `groups` VALUES (2, 'Guest');
INSERT INTO `groups` VALUES (3, 'Member');
INSERT INTO `groups` VALUES (4, 'Admin');
INSERT INTO `groups` VALUES (5, 'Banned');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `members`
-- 

CREATE TABLE `members` (
  `id` mediumint(8) NOT NULL default '0',
  `name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `mgroup` smallint(3) NOT NULL default '0',
  `password` varchar(32) collate latin1_general_ci NOT NULL default '',
  `email` varchar(60) collate latin1_general_ci NOT NULL default '',
  `joined` int(10) NOT NULL default '0',
  `ip_address` varchar(16) collate latin1_general_ci NOT NULL default '',
  `posts` mediumint(7) default '0',
  `title` varchar(64) collate latin1_general_ci default NULL,
  `allow_admin_mails` tinyint(1) default NULL,
  `time_offset` varchar(10) collate latin1_general_ci default NULL,
  `hide_email` varchar(8) collate latin1_general_ci default NULL,
  `email_full` tinyint(1) default NULL,
  `skin` smallint(5) default NULL,
  `warn_level` int(10) default NULL,
  `warn_lastwarn` int(10) NOT NULL default '0',
  `last_post` int(10) default NULL,
  `restrict_post` varchar(100) collate latin1_general_ci NOT NULL default '0',
  `view_sigs` tinyint(1) default '1',
  `view_avs` tinyint(1) default '1',
  `bday_day` int(2) default NULL,
  `new_msg` tinyint(2) default '0',
  `last_visit` int(10) default '0',
  `last_activity` int(10) default '0',
  `mod_posts` varchar(100) collate latin1_general_ci NOT NULL default '0',
  `temp_ban` varchar(100) collate latin1_general_ci default '0',
  `sub_end` int(10) NOT NULL default '0',
  `login_anonymous` char(3) collate latin1_general_ci NOT NULL default '0&0',
  `ignored_users` text collate latin1_general_ci NOT NULL,
  `member_login_key` varchar(32) collate latin1_general_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `mgroup` (`mgroup`),
  KEY `bday_day` (`bday_day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `members`
-- 

INSERT INTO `members` VALUES (1, 'Ducki', 4, '', 'alexwichti@freenet.de', 1124031915, '127.0.0.1', 0, 'Administrator', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '0', 1, 1, NULL, 0, 1124035394, 1125178002, '0', '0', 0, '0&1', '', '7d14dade97efe70f659dd2ec000c7ecf');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `permissions`
-- 

CREATE TABLE `permissions` (
  `g_id` int(3) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`g_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `permissions`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `sessions`
-- 

CREATE TABLE `sessions` (
  `id` varchar(32) collate latin1_general_ci NOT NULL default '0',
  `member_name` varchar(64) collate latin1_general_ci default NULL,
  `member_id` mediumint(8) NOT NULL default '0',
  `ip_address` varchar(16) collate latin1_general_ci default NULL,
  `browser` varchar(128) collate latin1_general_ci default NULL,
  `browser_key` varchar(32) collate latin1_general_ci NOT NULL default '',
  `time` int(10) default NULL,
  `location` varchar(40) collate latin1_general_ci default NULL,
  `member_group` smallint(3) default NULL,
  `in_error` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `sessions`
-- 

INSERT INTO `sessions` VALUES ('15af1f2816951a4797d0a0142104e518', 'Ducki', 1, '127.0.0.1', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8b5) Gecko/20051006 Firefox/1.4.1', '9b0414229acb2344e8620b55823e0083', 1129556092, 'vorne', 4, 0);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `skin_macro`
-- 

CREATE TABLE `skin_macro` (
  `macro_id` smallint(3) NOT NULL auto_increment,
  `macro_value` varchar(200) collate latin1_general_ci default NULL,
  `macro_replace` text collate latin1_general_ci,
  `macro_can_remove` tinyint(1) default '0',
  `macro_set` smallint(3) NOT NULL default '0',
  PRIMARY KEY  (`macro_id`),
  KEY `macro_set` (`macro_set`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `skin_macro`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `skin_sets`
-- 

CREATE TABLE `skin_sets` (
  `skin_set_id` int(10) NOT NULL auto_increment,
  `name` varchar(150) collate latin1_general_ci NOT NULL default '',
  `image_dir` varchar(200) collate latin1_general_ci NOT NULL default '',
  `hidden` tinyint(1) NOT NULL default '0',
  `default` tinyint(1) NOT NULL default '0',
  `css_method` varchar(100) collate latin1_general_ci NOT NULL default 'inline',
  `css` mediumtext collate latin1_general_ci NOT NULL,
  `cache_macro` mediumtext collate latin1_general_ci NOT NULL,
  `wrapper` mediumtext collate latin1_general_ci NOT NULL,
  `css_updated` int(10) NOT NULL default '0',
  `emoticon_folder` varchar(60) collate latin1_general_ci NOT NULL default 'default',
  PRIMARY KEY  (`skin_set_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=1 AUTO_INCREMENT=3 ;

-- 
-- Daten für Tabelle `skin_sets`
-- 

INSERT INTO `skin_sets` VALUES (1, 'Default Testing Skin', 'default_testing_skin', 0, 1, 'inline', 'body {\r\n    font-family: arial;\r\n}', '', '<html>\r\n<head>\r\n<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />\r\n\r\n<title><% TITLE %></title>\r\n<% CSS %> \r\n</head> \r\n<body>\r\n<div id="wrapper">\r\n\r\n<% DEBUG %>\r\n\r\n<% MIEP %>\r\n\r\n</div>\r\n</body> \r\n</html>', 0, 'default');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `skin_templates`
-- 

CREATE TABLE `skin_templates` (
  `suid` int(10) NOT NULL auto_increment,
  `set_id` int(10) NOT NULL default '0',
  `group_name` varchar(255) collate latin1_general_ci NOT NULL default '',
  `section_content` mediumtext collate latin1_general_ci,
  `func_name` varchar(255) collate latin1_general_ci default NULL,
  `func_data` text collate latin1_general_ci,
  `updated` int(10) default NULL,
  `can_remove` tinyint(4) default '0',
  PRIMARY KEY  (`suid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `skin_templates`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `stuff_cache`
-- 

CREATE TABLE `stuff_cache` (
  `c_key` varchar(250) collate latin1_general_ci NOT NULL default '',
  `content` text collate latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- 
-- Daten für Tabelle `stuff_cache`
-- 

INSERT INTO `stuff_cache` VALUES ('bans', '');
