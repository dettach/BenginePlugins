<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

# Сокращаем название плагина для удобства
$pl = $plugin_config["name"];

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
				$parent = $nodes[3];
				doquery("UPDATE `category` SET child=child+1 WHERE id='".$_POST["parent"]."' LIMIT 1;");
			} else {
				$parent = 0;
			}
			add($pl, $_POST, "parent='".$parent."'");
			if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {
				cacheAdd($pl,$plugin_config);
			}
			header("Location: /admin/".$pl."/");
			die();
		}
		
		# Шаблон раздела плагина
		$body = "/plugins/category/category_add_edit.tpl";
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
		$body = "/plugins/category/category_add_edit.tpl";
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
	
	#Сворачиваем/разворачиваем подстраницы
	elseif(isset($nodes[2]) and $nodes[2] == "showchild" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and (int)$nodes[4] >= 0)
	{
		if(doquery("UPDATE `".$pl."` SET `showchild`='".$nodes[4]."' WHERE id=".$nodes[3]." LIMIT 1")) {
			header("Location: /admin/".$pl."/"); die();
		} else {
			die("Ошибка работы с дочерними страницами");
		}
	}

	#Перемещение вверх или вниз
	elseif(isset($nodes[2]) and ($nodes[2] == "up" or $nodes[2] == "dn") and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		$sql_query = doquery("SELECT * FROM `".$pl."` WHERE id='".$nodes[3]."' LIMIT 1");
		if(dorows($sql_query) == 1)
		{
			$sql = doassoc($sql_query);
			
			if($nodes[2] == "up") {
				$order = $sql["order"] - 1;
			}
			elseif($nodes[2] == "dn") {
				$order = $sql["order"] + 1;
			}
		
			if(isset($sql["parent"])) {
				$update = "UPDATE `".$pl."` AS t1 INNER JOIN `".$pl."` AS t2 ON t1.order='".$order."' and t2.order='".$sql["order"]."' SET t1.order='".$sql["order"]."',t2.order='".$order."' WHERE t1.parent='".$sql["parent"]."' and  t2.parent='".$sql["parent"]."'";
			} else {
				$update = "UPDATE `".$pl."` AS t1 INNER JOIN `".$pl."` AS t2 ON t1.order='".$order."' and t2.order='".$sql["order"]."' SET t1.order='".$sql["order"]."',t2.order='".$order."'";
			}
			
			if(doquery($update)) {
				cacheAdd($pl,$plugin_config);
				header("Location: /admin/".$pl."/"); die();
			}
			else {
				die("Ошибка изменения сортировки");
			}
		} else {
			die("Такая страница не найдена");
		}
	}
	#Список каталогов
	else
	{
		# вверх или вниз
		if(isset($nodes[2]) and ($nodes[2] == "up" or $nodes[2] == "dn") and isset($nodes[3]) and (int)$nodes[3] > 0) {
			updown($pl, $nodes[3], $nodes[2], "parent");
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
		
		$query_category = doquery("SELECT * FROM `category` WHERE `parent`=0 ORDER BY `order` ");
		if(dorows($query_category) > 0) {
			$content = doarray($query_category);
		}
		$body = "/plugins/category/category.tpl";
	}
}
# Запрос не может быть обработан
else
{
	$body = "/system/template/error.tpl";
}
?>