<?php
if(!defined("BENGINE")) {die ("Hacking!");}

if(isset($nodes[1]) and (int)$nodes[1] > 0)
{
	#Зашли в каталог и смотрим его подкаталоги и элементы
	$sql = doquery("SELECT * FROM catalog WHERE id='".$nodes[1]."' LIMIT 1");
	if(dorows($sql) == 1)
	{
		#Смотрим всю информацию о каталоге
		$content = doassoc($sql);
		
		#Возможно есть родительский каталог
		if($content["catalog"]["parent"] > 0) {
			$sql = doquery("SELECT * FROM catalog WHERE parent='".$content["parent"]."' LIMIT 1");
			if(dorows($sql) > 0) {
				$content["catalog_parent"] = doassoc($sql);
			}
		}
		
		#Список подкаталогов
		if($content["catalog"]["child"] > 0) {
			$sql = doquery("SELECT * FROM catalog WHERE parent='".$content["id"]."' ORDER BY `order`");
			if(dorows($sql) > 0) {
				$content["catalog_child"] = doarray($sql);
			}
		}
		
		# Навигация по элементам каталога
		if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
			$nav = donav($plugin_config["limit"], "catalog_elements", "`catalog`='".$content["id"]."'", $p);
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
		$sql = doquery("SELECT * FROM catalog_elements WHERE catalog='".$content["id"]."' ORDER BY `".$order."` DESC ".$navigation);
		if(dorows($sql) > 0) {
			$content["catalog_elements"] = doarray($sql);
		}
		
		#просмотр одного элемента
		if(isset($nodes[2]) and (int)$nodes[2] > 0) {
			$sql = doquery("SELECT * FROM catalog_elements WHERE catalog='".$nodes[1]."' and id='".$nodes[2]."' LIMIT 1");
			if(dorows($sql) > 0) {
				$content["elements"] = doassoc($sql);
			}
		}
	}
}
else
{
	#Список всех каталогов
	$sql = doquery("SELECT * FROM `catalog` WHERE `parent`=0 ORDER BY `order` ");
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}
?>