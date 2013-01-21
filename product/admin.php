<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

# Сокращаем название плагина для удобства
$pl = $plugin_config["name"];

# Запрос к разделу плагина
if(isset($nodes[1]) and $nodes[1] == $pl)
{
	#Добавление элементa каталога
	if(isset($nodes[2]) and $nodes[2] == "add" and !isset($nodes[3]))
	{
		if(isset($_POST["submit"])) 
		{
			add("product", $_POST);
			cacheAdd("product",$plugin_config);
			header("Location: /admin/product/");
			die();
		}
		#Список файлов шаблона сайта
		$body = "/plugins/product/product_add_edit.tpl";
	}

	#Редактирование элементa каталога
	elseif(isset($nodes[2]) and $nodes[2] == "edit" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		# Отправка в БД
		if(isset($_POST["submit"]))
		{
			edit("product", $_POST, $nodes[3]);
			cacheAdd("product",$plugin_config);
			header("Location: /admin/product/edit/".$nodes[3]."/");
			die();
		}
		
		$query_product = doquery("SELECT * FROM `product` WHERE `id`='".$nodes[3]."' LIMIT 1");
		if(dorows($query_product) > 0) {
			$content = doassoc($query_product);
		}
		#Список файлов в шаблоне сайта
		$body = "/plugins/product/product_add_edit.tpl";
	}	

	#Удаление элементa каталога
	elseif(isset($nodes[2]) and $nodes[2] == "delete" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		#Удаляем элемент
		doquery("DELETE FROM `product` WHERE id='".$nodes[3]."' LIMIT 1");
		
		cacheAdd("product",$plugin_config);
		header("Location: /admin/product/");
		die();
	}
	
	#Список элементов каталога
	else
	{
		# Включение и отключение параметра
		if(isset($nodes[2]) and $nodes[2] == "menu" and isset($nodes[3]) and (int)$nodes[3] > 0) {
			$onoff = onoff("product", $nodes[3], $nodes[2]);
			cacheAdd("product",$plugin_config);
			die("".$onoff."");
		}
		# Навигация по плагину
		if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {	
			$nav = donav($plugin_config["limit"], "product", $p);
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
		$query_product = doquery("SELECT * FROM `product` ORDER BY `".$order."` DESC ".$navigation);
		if(dorows($query_product) > 0) {
			$content = doarray($query_product);
		}
		
		$body = "/plugins/product/product.tpl";
	}
}
# Запрос не может быть обработан
else
{
	$body = "/system/template/error.tpl";
}
?>