<?php
if(!defined("BENGINE")) {die ("Hacking!");}

include_once(ROOT_DIR."/plugins/category/arrays.php");

if(isset($nodes[1]) and (int)$nodes[1] > 0)
{
	#Зашли в каталог и смотрим его подкаталоги и элементы
	$sql = doquery("SELECT * FROM category WHERE id='".$nodes[1]."' LIMIT 1");
	if(dorows($sql) == 1)
	{
		#Смотрим всю информацию о каталоге
		$content = doassoc($sql);
		
		#Возможно есть родительский каталог
		if(!empty($content["parent"]) > 0) {
			$sql = doquery("SELECT * FROM category WHERE id='".$content["parent"]."' LIMIT 1");
			if(dorows($sql) == 1) {
				$content["category_parent"] = doassoc($sql);
			}
		}
		
		#Список подкаталогов
		if(!empty($content["child"]) > 0) {
			$sql = doquery("SELECT * FROM category WHERE parent='".$content["id"]."' ORDER BY title");
			if(dorows($sql) > 0) {
				$content["category_child"] = doarray($sql);
				#если нет картинки, оставляем пустую строку
				foreach($content["category_child"] as $k => $v) {
					$v["image"] = str_replace("%20"," ",$v["image"]);
					if(file_exists(ROOT_DIR.$v["image"]) === false) {
						$content["category_child"][$k]["image"] = "";
					} else {
						#ищем маленькую превьюшку
						$tmp_small_image = explode("/",$v["image"]);
						$pref_small_image = end($tmp_small_image);
						$small_image = str_replace($pref_small_image,"small_".$pref_small_image,$v["image"]);
						if(file_exists(ROOT_DIR.$small_image) !== false) {
							$content["category_child"][$k]["small_image"] = $small_image;
						}
					}
				}
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
		$sql = doquery("SELECT * FROM product WHERE category='".$content["id"]."' ORDER BY title ");
		if(dorows($sql) > 0) {
			$content["product"] = doarray($sql);
			#если нет картинки, оставляем пустую строку
			foreach($content["product"] as $k => $v) {
				$v["image"] = str_replace("%20"," ",$v["image"]);
				if(file_exists(ROOT_DIR.$v["image"]) === false) {
					$content["product"][$k]["image"] = "";
				} else {
					#ищем маленькую превьюшку
					$tmp_small_image = explode("/",$v["image"]);
					$pref_small_image = end($tmp_small_image);
					$small_image = str_replace($pref_small_image,"small_".$pref_small_image,$v["image"]);
					if(file_exists(ROOT_DIR.$small_image) !== false) {
						$content["product"][$k]["small_image"] = $small_image;
					}
				}
			}
		}
		
		#просмотр одного элемента
		if(isset($nodes[2]) and (int)$nodes[2] > 0) {
			$sql = doquery("SELECT * FROM product WHERE id='".$nodes[2]."' LIMIT 1");
			if(dorows($sql) > 0) {
				$content["element"] = doassoc($sql);
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