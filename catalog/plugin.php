<?php
if(!defined("BENGINE")) {die ("Hacking!");}

$content["catalog"] = array();

#Просмотр конфигурации каталога
if(($sql_catalog_config = doquery("SELECT * FROM catalog_config WHERE id=1 LIMIT 1")) != false) {
	$catalog_config = doassoc($sql_catalog_config);
} else {
	die("Ошибка конфиурации плагина");
}

#Просим конкретную строку таблицы плагина
if(isset($nodes[1]) and (int)$nodes[1] > 0)
{
	if(($sql_data = doquery("SELECT * FROM `catalog` WHERE id='".$nodes[1]."' LIMIT 1")) != false) {
		if(dorows($sql_data) > 0)
		{
			$content["catalog"] = doassoc($sql_data);

			#один элемент каталога
			if(isset($nodes[2]) and (int)$nodes[2] > 0)
			{
				#просмотр одного элемента
				if(($sql_elements = doquery("SELECT * FROM catalog_elements WHERE id='".$nodes[2]."' and parent='".$content["catalog"]["id"]."' LIMIT 1")) != false) {
					if(dorows($sql_elements) > 0) {
						$content["elements"] = doassoc($sql_elements);
						$body = $template."/".$content["elements"]["body"];
					}
				}
				$body = $template."/".$catalog_config["elements_body"];
			}
			else
			{
				#подкаталоги
				if($content["catalog"]["child"] > 0) {
					if(($sql_data = doquery("SELECT * FROM catalog WHERE parent='".$content["catalog"]["id"]."' ORDER BY `".$catalog_config["catalog_order"]."` ")) != false) {
						if(dorows($sql_data) > 0) {
							$content["podcatalog"] = doarray($sql_data);
						}
					}
				}
				
				#получение данных о всех элементах каталога
				if(($sql_elements = doquery("SELECT * FROM `catalog_elements` WHERE `parent`='".$content["catalog"]["id"]."' ORDER BY `id` ")) != false) {
					if(dorows($sql_elements) > 0) {
						$content["elements"] = doarray($sql_elements);
					}
				}
				
				#Шаблон
				$body = $template."/".$content["catalog"]["body"];
			}
		}
	}
}
#Просим все записи таблицы плагина
else
{
	#получение данных о всех каталогах 0 уровня
	if(($sql_data = doquery("SELECT * FROM `catalog` WHERE `parent`=0 ORDER BY `".$catalog_config["catalog_order"]."` ")) != false) {
		if(dorows($sql_data) > 0) {
			$content["catalog"] = doarray($sql_data);
		}
	}
}
?>