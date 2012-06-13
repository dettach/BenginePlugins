<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

# Смотрим конкретную запись в таблице, по уникальному id
if(isset($nodes[1]) and (int)$nodes[1] > 0) {
	$sql = doquery("SELECT * FROM ".$pl." WHERE page='".$page["id"]."' and id='".$nodes[1]."' LIMIT 1");
	if(dorows($sql)== 1) {
		$content = doassoc($sql);
		# Стандартные шаблоны для плагина берез из его конфигурации
		if(isset($content["header"])){ $header = "/templates/".$cfg["template"]."/".$content["header"]; }
		if(isset($content["body"]))  { $body = "/templates/".$cfg["template"]."/".$content["body"]; }
		if(isset($content["footer"])){ $footer = "/templates/".$cfg["template"]."/".$content["footer"]; }
	}
}

# Смотрим список всех записей
else
{	
	# Определяем сортировку
	if(isset($plugin_config["order"]) and $plugin_config["order"] == true) {
		$order = "DESC";
	} else {
		$order = "";
	}
	# Навигация по плагину
	if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
		$nav = donav($plugin_config["limit"], $pl, "page='".$page["id"]."'", $p);
		$navigation = "LIMIT ".$nav["start"].",".$nav["num"];
	} else {
		$navigation = "";
	}
	# Запрашиваем весь массив данных
	$sql = doquery("SELECT * FROM ".$pl." WHERE page='".$page["id"]."' ORDER BY `".$plugin_config["sort"]."` ".$order." ".$navigation);
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}
?>