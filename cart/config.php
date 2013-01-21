<?php

$plugin_config = array();
$plugin_config["admin"] = 1;
$plugin_config["name"] = "cart";
$plugin_config["title"] = "Корзина";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 0;
	
/*
  user - id пользователя
  element - id элемента в каталоге
  count - количество выбранных элементов
  old - уже купленный ранее, попадает в историю заказов
  
  [только для уже купленных элементов]
  
  datetime - дата последнего заказа
  payment - метод оплаты
  cost - стоимость всех элементов
*/

$plugin_install[] = "CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `element` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `old` int(11) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `payment` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `cost` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `element` (`element`),
  KEY `count` (`count`),
  KEY `old` (`old`),
  KEY `datetime` (`datetime`),
  KEY `cost` (`cost`),
  FULLTEXT KEY `payment` (`payment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
";

$plugin_uninstall[] = "DROP TABLE IF EXISTS `cart`";

?>