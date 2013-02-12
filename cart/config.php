<?php

$plugin_config = array();
$plugin_config["admin"] = 1;
$plugin_config["name"] = "cart";
$plugin_config["title"] = "Корзина";
$plugin_config["extend"] = 1;
$plugin_config["cache"] = 0;
	
/* 
 * user - id пользователя
 * session - id сессии, если пользователь не авторизован
 * datetime - дата добавления в корзину
 * element - id элемента в каталоге
 * count [0..99] - количество выбранных элементов
 * old [0 or 1] - уже купленный ранее, попадает в историю заказов
 * price - стоимость одного элемента
*/

$plugin_install[] = "CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `session` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `element` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `price` float NOT NULL DEFAULT '0',
  `old` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `session` (`session`),
  KEY `datetime` (`datetime`),
  KEY `element` (`element`),
  KEY `count` (`count`),
  KEY `price` (`price`),
  KEY `old` (`old`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";

$plugin_uninstall[] = "DROP TABLE IF EXISTS `cart`";

?>