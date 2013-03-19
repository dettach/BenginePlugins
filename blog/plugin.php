<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

# Смотрим конкретную запись в таблице, по уникальному id
if(isset($nodes[1]) and (int)$nodes[1] > 0) {
	$sql = doquery("SELECT * FROM news WHERE id='".$nodes[1]."' and menu=1 LIMIT 1");
	if(dorows($sql) == 1) {
		$content = doassoc($sql);
		$body = "/template/".$content["body"];
		#comments
		$sql = doquery("SELECT * FROM comments WHERE askmekak_id='".$nodes[1]."' ORDER BY datetime");
		$content["comments_list"] = doarray($sql);
	}
}

# Смотрим список всех записей
else
{
	# Навигация по плагину
	if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
		$nav = donav($plugin_config["limit"], "news", "menu=1", $p);
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
	$sql = doquery("SELECT * FROM news WHERE menu=1 ORDER BY `".$order."` DESC ".$navigation);
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}
?>