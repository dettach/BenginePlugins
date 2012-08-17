<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

#Перемещение вверх или вниз Каталогов
if(isset($nodes[2]) and ($nodes[2] == "up" or $nodes[2] == "down") and isset($nodes[3]) and (int)$nodes[3] > 0)
{
	$sql_query = doquery("SELECT * FROM `catalog` WHERE id='".$nodes[3]."' LIMIT 1");
	if(dorows($sql_query) == 1)
	{
		$sql = doassoc($sql_query);
		if($nodes[2] == "up") { $order = $sql["order"] - 1; }
		elseif($nodes[2] == "down") { $order = $sql["order"] + 1; }
	
		$update = "UPDATE `catalog` AS t1 INNER JOIN `catalog` AS t2 ON t1.order='".$order."' and t2.order='".$sql["order"]."' SET t1.order='".$sql["order"]."',t2.order='".$order."' WHERE t1.parent='".$sql["parent"]."' and  t2.parent='".$sql["parent"]."'";
		
		if(doquery($update)) {
			cache_create("catalog"); header("Location: /admin/catalog/"); die();
		} else {
			die("Ошибка изменения сортировки");
		}
	} else {
		die("Такая страница не найдена");
	}
}

#Включение в меню Каталогов
if(isset($nodes[2]) and $nodes[2] == "menu" and isset($nodes[3]) and (int)$nodes[3] > 0)
{
	$menu = 0;
	$sql_query = doquery("SELECT `menu` FROM `catalog` WHERE id='".$nodes[3]."' LIMIT 1");
	if(dorows($sql_query) == 1)
	{
		$sql = doassoc($sql_query);
		if($sql["menu"] == 0) { $menu = 1; }
		doquery("UPDATE `catalog` SET `menu`='".$menu."' WHERE id='".$nodes[3]."' LIMIT 1");
		cache_create("catalog");
	}
	die("".$menu."");
}

#Удаление Каталога
if(isset($nodes[2]) and $nodes[2] == "delete" and isset($nodes[3]) and (int)$nodes[3] > 0)
{
	$sql_query = doquery("SELECT `order`,`parent`,`child` FROM `catalog` WHERE id='".$nodes[3]."' LIMIT 1");
	if(dorows($sql_query) == 1)
	{
		$sql = doassoc($sql_query);
		if($sql["child"] == 0) {
			doquery("UPDATE `catalog` SET `order`=`order`-1 WHERE `parent`='".$sql["parent"]."' and `order`>'".$sql["order"]."'");
		}
		if($sql["parent"] > 0) {
			doquery("UPDATE `catalog` SET `child`=`child`-1 WHERE `id`='".$sql["parent"]."' LIMIT 1");
		}
		doquery("DELETE FROM `catalog` WHERE id='".$nodes[3]."' LIMIT 1");	
		
		cache_create("catalog");
		header("Location: /admin/catalog/"); die();
	} else {
		die("Такая страница не найдена");
	}
}

#Включение в меню Элемента каталога
if(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and $nodes[4] == "menu" and isset($nodes[5]) and (int)$nodes[5] > 0)
{
	$menu = 0;
	$sql_query = doquery("SELECT `menu` FROM `catalog_elements` WHERE id='".$nodes[5]."' LIMIT 1");
	if(dorows($sql_query) == 1)
	{
		$sql = doassoc($sql_query);
		if($sql["menu"] == 0) { $menu = 1; }
		doquery("UPDATE `catalog_elements` SET `menu`='".$menu."' WHERE id='".$nodes[5]."' LIMIT 1");
		cache_create("catalog_elements");
	}
	die("".$menu."");
}

#Удаление Элемента каталога
if(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and $nodes[4] == "delete" and isset($nodes[5]) and (int)$nodes[5] > 0)
{
	if(doquery("DELETE FROM `catalog_elements` WHERE id='".$nodes[5]."' LIMIT 1")) {
		cache_create("catalog_elements");
		header("Location: /admin/catalog/elements/".$nodes[3]."/"); die();
	} else {
		die("Такая страница не найдена");
	}
}

#Обработка всех POST данных
if(isset($_POST["submit"]))
{
	#Добавление каталога
	if(isset($nodes[2]) and $nodes[2] == "add")
	{
		$_POST["title"] = bengine_chars($_POST["title"]);
		$_POST["description"] = bengine_chars($_POST["description"]);
		$_POST["keywords"] = bengine_chars($_POST["keywords"]);
		$_POST["image"] = bengine_chars($_POST["image"]);
		$_POST["text"] = bengine_chars_mysql(trim($_POST["text"]));
		if(isset($nodes[3]) and (int)$nodes[3] > 0) {
			if(add_post("catalog", $_POST, false, $nodes[3]) != true) {
				die("При добавлении каталога возникла ошибка");
			}
		} else {
			if(add_post("catalog", $_POST) != true) {
				die("При добавлении каталога возникла ошибка");
			}
		}
		cache_create("catalog");
		header("Location: /admin/catalog/");
		die();
	}
	
	#Редактирование каталога
	elseif(isset($nodes[2]) and $nodes[2] == "edit" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		$_POST["title"] = bengine_chars($_POST["title"]);
		$_POST["description"] = bengine_chars($_POST["description"]);
		$_POST["keywords"] = bengine_chars($_POST["keywords"]);
		$_POST["image"] = bengine_chars($_POST["image"]);
		$_POST["text"] = bengine_chars_mysql(trim($_POST["text"]));
		if(edit_post("catalog", $_POST, $nodes[3]) != true) {
			die("При редактировании каталога возникли ошибки");
		}
		cache_create("catalog");
		header("Location: /admin/catalog/edit/".$nodes[3]."/");
		die();
	}
	
	#Добавление элементa каталога
	elseif(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and $nodes[4] == "add" and !isset($nodes[5]))
	{
		if(count($_POST) > 1) {
			foreach($_POST as $k => $v) {
				$_POST[$k] = bengine_chars($v);
			}
		}
		if(add_post("catalog_elements", $_POST, false, $nodes[3]) != true) {
			die("При добавлении элемента каталога возникла ошибка");
		}
		cache_create("catalog_elements");
		header("Location: /admin/catalog/elements/".$nodes[3]."/");
		die();
	}
	
	#Редактирование элементa каталога
	elseif(isset($nodes[2]) and $nodes[2] == "elements" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($nodes[4]) and $nodes[4] == "edit" and isset($nodes[5]) and (int)$nodes[5] > 0)
	{
		if(count($_POST) > 1) {
			foreach($_POST as $k => $v) {
				$_POST[$k] = bengine_chars($v);
			}
		}
		
		if(edit_post("catalog_elements", $_POST, $nodes[5]) != true) {
			die("При редактировании элемента каталога возникли ошибки");
		}
		
		cache_create("catalog_elements");
		header("Location: /admin/catalog/elements/".$nodes[3]."/edit/".$nodes[5]."/");
		die();
	}

	#Конфигурация каталогов и элементов
	elseif(isset($nodes[2]) and $nodes[2] == "config")
	{
		if(edit_post("catalog_config", $_POST, 1) != true) {
			die("Ошибка изменения поля в таблице catalog_config");
		} else {
			header("Location: /admin/catalog/config/"); die();
		}
	}
}
?>