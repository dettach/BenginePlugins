<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

# Смотрим конкретную запись в таблице, по уникальному id
if(isset($nodes[1]) and (int)$nodes[1] > 0) {
	$sql = doquery("SELECT * FROM ".$pl." WHERE page='".$page["id"]."' and id='".$nodes[1]."' LIMIT 1");
	if(dorows($sql)== 1) {
		$content = doassoc($sql);
		# Стандартные шаблоны для плагина берез из его конфигурации
		if(isset($content["header"])){ $header = "/template/".$content["header"]; }
		if(isset($content["body"]))  { $body = "/template/".$content["body"]; }
		if(isset($content["footer"])){ $footer = "/template/".$content["footer"]; }
	}
}

# Смотрим список всех записей
else
{
	# Навигация по плагину
	if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
		$nav = donav($plugin_config["limit"], $pl, false, $p);
		$navigation = "LIMIT ".$nav["start"].",".$nav["num"];
	} else {
		$navigation = "";
	}
	
	# Определяем сортировку
	if(isset($plugin_config["order"]) and $plugin_config["order"] == true) {
		$order = "order";
	} else {
		$order = "id";
	}
	
	# Запрашиваем весь массив данных
	$sql = doquery("SELECT * FROM ".$pl." WHERE page='".$page["id"]."' ORDER BY `".$order."` DESC ".$navigation);
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}
?>