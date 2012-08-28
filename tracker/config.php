<?php

# Очищаем данные предыдущего плагина
$plugin_config = array();

$plugin_config["admin"] = 1;
$plugin_config["name"] = "tracker";
$plugin_config["title"] = "Багтрекер";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 0;
$plugin_config["order"] = false;
$plugin_config["header"] = "header.tpl";
$plugin_config["body"] = "tracker.tpl";
$plugin_config["footer"] = "footer.tpl";

$plugin_install[] = "
CREATE TABLE IF NOT EXISTS `tracker_projects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";
$plugin_install[] = "
CREATE TABLE IF NOT EXISTS `tracker_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `project` int(11) NOT NULL,
  `user_in` int(11) NOT NULL,
  `user_ou` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` tinyint(1) NOT NULL,
  `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project` (`project`),
  KEY `user_in` (`user_in`),
  KEY `user_ou` (`user_ou`),
  KEY `datetime` (`datetime`),
  KEY `status` (`status`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";
$plugin_install[] = "
CREATE TABLE IF NOT EXISTS `tracker_comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ticket` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket` (`ticket`),
  KEY `user` (`user`),
  KEY `datetime` (`datetime`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";

$plugin_uninstall[] = "DROP TABLE `tracker_projects`";
$plugin_uninstall[] = "DROP TABLE `tracker_tickets`";
$plugin_uninstall[] = "DROP TABLE `tracker_comments`";
?>