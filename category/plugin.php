<?php
if(!defined("BENGINE")) {die ("Hacking!");}

if(isset($nodes[1]) and (int)$nodes[1] > 0)
{
	#Зашли в каталог и смотрим его подкаталоги и элементы
	$sql = doquery("SELECT * FROM category WHERE id='".$nodes[1]."' LIMIT 1");
	if(dorows($sql) == 1)
	{
		#Смотрим всю информацию о каталоге
		$content = doassoc($sql);
		$header = "/template/".$content["header"];
		$body = "/template/".$content["body"];
		$footer = "/template/".$content["footer"];
		
		#Возможно есть родительский каталог
		if(!empty($content["parent"]) > 0) {
			$sql = doquery("SELECT * FROM category WHERE id='".$content["parent"]."' LIMIT 1");
			if(dorows($sql) == 1) {
				$content["category_parent"] = doassoc($sql);
			}
		}
		
		#Список подкаталогов
		if(!empty($content["child"]) > 0) {
			$sql = doquery("SELECT * FROM category WHERE parent='".$content["id"]."' ORDER BY `order`");
			if(dorows($sql) > 0) {
				$content["category_child"] = doarray($sql);
			}
		}
		
		# Навигация по элементам каталога
		if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
			$nav = donav($plugin_config["limit"], "product", "`category`='".$content["id"]."'", $p);
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
		
		#Список элементов каталога
		$sql = doquery("SELECT * FROM product WHERE category='".$content["id"]."' ORDER BY `".$order."` DESC ".$navigation);
		if(dorows($sql) > 0) {
			$content["product"] = doarray($sql);
		}
		
		#просмотр одного элемента
		if(isset($nodes[2]) and (int)$nodes[2] > 0) {
			$sql = doquery("SELECT * FROM product WHERE category='".$nodes[1]."' and id='".$nodes[2]."' LIMIT 1");
			if(dorows($sql) > 0) {
				$content["elements"] = doassoc($sql);
			}
		}
	}
}
else
{
	#Список всех каталогов
	$sql = doquery("SELECT * FROM `category` WHERE `parent`=0 ORDER BY `order` ");
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}
?>