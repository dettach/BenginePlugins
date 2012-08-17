<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

# Сокращаем название плагина для удобства
$pl = $plugin_config["name"];
#include_once(ROOT_DIR."/plugins/".$pl."/functions.php");

# Запрос к разделу плагина
if(isset($nodes[1]) and $nodes[1] == $pl)
{

	#Добавление каталога
	if(isset($nodes[2]) and $nodes[2] == "add")
	{
		# Отправка в БД
		if(isset($_POST["submit"])) {
			if(isset($nodes[3]) and (int)$nodes[3] > 0) {
				$_POST["parent"] = $nodes[3];
				doquery("UPDATE `catalog` SET child=child+1 WHERE id='".$_POST["parent"]."' LIMIT 1;");
			}
			add($pl,$_POST);
			if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {
				cacheAdd($pl,$plugin_config);
			}
			header("Location: /admin/".$pl."/");
			die();
		}
		
		# Шаблон раздела плагина
		$body = "/plugins/catalog/tpl/catalog_add_edit.tpl";
	}

	#Редактирование каталога
	elseif(isset($nodes[2]) and $nodes[2] == "edit" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		# Отправка в БД
		if(isset($_POST["submit"])) {
			edit($pl, $_POST, $nodes[3]);
			if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {
				cacheAdd($pl,$plugin_config);
			}
			header("Location: /admin/".$pl."/edit/".$nodes[3]."/");
			die();
		}
		# Запрос к БД
		$content = doqueryassoc("SELECT * FROM `".$pl."` WHERE id='".$nodes[3]."' LIMIT 1");
		
		# Шаблон раздела плагина
		$body = "/plugins/catalog/tpl/catalog_add_edit.tpl";
	}

	# Удаление каталога
	elseif(isset($nodes[2]) and $nodes[2] == "delete" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		doquery("DELETE FROM `".$pl."` WHERE id='".$nodes[3]."' LIMIT 1");
		if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {
			cacheAdd($pl,$plugin_config);
		}
		header("Location: /admin/".$pl."/");
		die();
	}
	
#############################################################################################################################################
	
	#Список элементов каталога
	elseif(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and !isset($nodes[4]))
	{
		# вверх или вниз
		if(isset($nodes[2]) and ($nodes[2] == "up" or $nodes[2] == "dn") and isset($nodes[3]) and (int)$nodes[3] > 0) {
			updown("catalog_elements", $nodes[3], $nodes[2]);
			cacheAdd("catalog_elements",$plugin_config);
			header("Location: /admin/".$pl."/");
			die();
		}
		# Включение и отключение параметра
		if(isset($nodes[2]) and $nodes[2] == "menu" and isset($nodes[3]) and (int)$nodes[3] > 0) {
			$onoff = onoff("catalog_elements", $nodes[3], $nodes[2]);
			cacheAdd("catalog_elements",$plugin_config);
			die("".$onoff."");
		}
		# Навигация по плагину
		if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
			$nav = donav($plugin_config["limit"], "catalog_elements", "`page`='".$nodes[3]."'", $p);
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
		$query_elements = doquery("SELECT * FROM `catalog_elements` WHERE `page`='".$nodes[3]."' ORDER BY `".$order."` DESC ".$navigation);
		if(dorows($query_elements) > 0) {
			$content = doarray($query_elements);
		}
		$body = "/plugins/catalog/tpl/elements.tpl";
	}

	#Добавление элементa каталога
	elseif(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and $nodes[4] == "add" and !isset($nodes[5]))
	{
		if(isset($_POST["submit"])) {
			if(isset($nodes[3]) and (int)$nodes[3] > 0) {
				$_POST["page"] = $nodes[3];
			}
			add("catalog_elements",$_POST);
			cacheAdd("catalog_elements",$plugin_config);
			header("Location: /admin/".$pl."/elements/".$nodes[3]."/");
			die();
		}
		#Список файлов шаблона сайта
		$body = "/plugins/catalog/tpl/elements_add_edit.tpl";
	}

	#Редактирование элементa каталога
	elseif(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and $nodes[4] == "edit" and isset($nodes[5]) and (int)$nodes[5] > 0)
	{
		$query_elements = doquery("SELECT * FROM `catalog_elements` WHERE `id`='".$nodes[5]."' LIMIT 1");
		if(dorows($query_elements) > 0) {
			$content = doassoc($query_elements);
		}
		#Список файлов в шаблоне сайта
		$body = "/plugins/catalog/tpl/elements_add_edit.tpl";
	}

#############################################################################################################################################

	#Список родительских каталогов
	else
	{
		# вверх или вниз
		if(isset($nodes[2]) and ($nodes[2] == "up" or $nodes[2] == "dn") and isset($nodes[3]) and (int)$nodes[3] > 0) {
			updown($pl, $nodes[3], $nodes[2]);
			cacheAdd($pl,$plugin_config);
			header("Location: /admin/".$pl."/");
			die();
		}
		# Включение и отключение параметра
		if(isset($nodes[2]) and $nodes[2] == "menu" and isset($nodes[3]) and (int)$nodes[3] > 0) {
			$onoff = onoff($pl, $nodes[3], $nodes[2]);
			cacheAdd($pl,$plugin_config);
			die("".$onoff."");
		}
		
		$query_catalog = doquery("SELECT * FROM `catalog` WHERE `parent`=0 ORDER BY `order` ");
		if(dorows($query_catalog) > 0) {
			$content = doarray($query_catalog);
		}
		$body = "/plugins/catalog/tpl/catalog.tpl";
	}
}
# Запрос не может быть обработан
else
{
	$body = "/system/template/error.tpl";
}
?>