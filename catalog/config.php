<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "catalog";
$plugin_config["title"] = "Каталог";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 20;
$plugin_config["sort"] = "order";
$plugin_config["order"] = false;
$plugin_config["header"] = "header.tpl";
$plugin_config["body"] = "catalog.tpl";
$plugin_config["footer"] = "footer.tpl";
$plugin_config["elements_body"] = "elements.tpl";

#Разделы магазина разбитые на каталоги
$plugin_column["catalog"][] = array(
	"name" => "parent",
	"type" => "select",
	"title" => "Родительский каталог",
	"default" => 0,
	"selectname" => "catalog"
);
$plugin_column["catalog"][] = array(
	"name" => "child",
	"type" => "select",
	"title" => "Дочерних элементов",
	"default" => 0,
	"selectname" => "catalog"
);
$plugin_column["catalog"][] = array(
	"name" => "showchild",
	"type" => "tinyint",
	"title" => "Видимость дочерних элементов",
	"default" => 0
);
$plugin_column["catalog"][] = array(
	"name" => "image",
	"type" => "varchar",
	"title" => "Изображение",
	"default" => "",
	"filebrowser" => 1
);
$plugin_column["catalog"][] = array(
	"name" => "text",
	"type" => "text",
	"title" => "Описание"
);

#Каждый товар в отдельности каталога
$plugin_column["catalog_elements"][] = array(
	"name" => "catalog",
	"type" => "select",
	"title" => "Раздел каталога",
	"default" => 0,
	"selectname" => "catalog"
);
$plugin_column["catalog_elements"][] = array(
	"name" => "image",
	"type" => "varchar",
	"title" => "Изображение",
	"default" => "",
	"filebrowser" => 1
);
$plugin_column["catalog_elements"][] = array(
	"name" => "articul",
	"type" => "varchar",
	"title" => "Артикул",
	"default" => ""
);
$plugin_column["catalog_elements"][] = array(
	"name" => "proizvoditel",
	"type" => "varchar",
	"title" => "Производитель",
	"default" => ""
);
$plugin_column["catalog_elements"][] = array(
	"name" => "cost",
	"type" => "varchar",
	"title" => "Цена",
	"default" => ""
);

?>