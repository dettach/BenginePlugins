<?phpif( !defined("BENGINE") ) { die ("Hacking!"); }# Сокращаем название плагина для удобства$pl = $plugin_config["name"];# Запрос к разделу плагинаif(isset($nodes[1]) and $nodes[1] == $pl){	# Добавление записи в таблицу плагина	if(isset($nodes[2]) and $nodes[2] == "add")	{		# Отправка в БД		if(isset($_POST["submit"])) {			add($pl,$_POST);			if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {				if(isset($plugin_config["order"])) {					cacheAdd($pl,$plugin_config["order"]);				}			}			header("Location: /admin/".$pl."/");			die();		}		# Запрос к БД		$content = array();				# Шаблон раздела плагина		$body = "/system/template/plugin_edit.tpl";	}	# Редактирование записи в таблице плагина	elseif(isset($nodes[2]) and $nodes[2] == "edit" and isset($nodes[3]) and (int)$nodes[3] > 0)	{				# Отправка в БД		if(isset($_POST["submit"])) {			edit($pl, $_POST, $nodes[3]);			if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {				if(isset($plugin_config["order"])) {					cacheAdd($pl,$plugin_config["order"]);				}			}			header("Location: /admin/".$pl."/edit/".$nodes[3]."/");			die();		}		# Запрос к БД		$content = doqueryassoc("SELECT * FROM `".$pl."` WHERE id='".$nodes[3]."' LIMIT 1");				# Шаблон раздела плагина		$body = "/system/template/plugin_edit.tpl";	}	# Удаление записи из таблицы плагина	elseif(isset($nodes[2]) and $nodes[2] == "delete" and isset($nodes[3]) and (int)$nodes[3] > 0)	{		doquery("DELETE FROM `".$pl."` WHERE id='".$nodes[3]."' LIMIT 1");		if(isset($plugin_config["cache"]) and $plugin_config["cache"] == 1) {			if(isset($plugin_config["order"])) {				cacheAdd($pl,$plugin_config["order"]);			}		}		header("Location: /admin/".$pl."/");		die();	}	# Список полей плагина	else	{		# вверх или вниз		if(isset($nodes[2]) and ($nodes[2] == "up" or $nodes[2] == "dn") and isset($nodes[3]) and (int)$nodes[3] > 0) {			updown($pl, $nodes[3], $nodes[2]);			if(isset($plugin_config["order"])) {				cacheAdd($pl,$plugin_config["order"]);			}			header("Location: /admin/".$pl."/");			die();		}		# Включение и отключение параметра		if(isset($nodes[2]) and $nodes[2] == "menu" and isset($nodes[3]) and (int)$nodes[3] > 0) {			$onoff = onoff($pl, $nodes[3], $nodes[2]);			if(isset($plugin_config["order"])) {				cacheAdd($pl,$plugin_config["order"]);			}			die("".$onoff."");		}		# Навигация по плагину		if(isset($plugin_config["limit"]) and $plugin_config["limit"] != 0) {				$nav = donav($plugin_config["limit"], $pl, false, $p);			$navigation = "LIMIT ".$nav["start"].",".$nav["num"];		} else {			$navigation = "";		}		# Определяем сортировку		if(isset($plugin_config["order"]) and $plugin_config["order"] == true) {			$order = "order";		} else {			$order = "id";		}		# Запрос к БД		$sql_plugin = doquery("SELECT * FROM ".$pl." ORDER BY `".$order."` DESC ".$navigation);		if(dorows($sql_plugin) > 0) {			$content = doarray($sql_plugin);		}		# Шаблон раздела плагина		$body = "/system/template/plugin_list.tpl";	}}# Запрос не может быть обработанelse{	$body = "/system/template/error.tpl";}?>