<?php

$plugin_config = array();
$plugin_config["admin"] = 2;
$plugin_config["name"] = "category";
$plugin_config["title"] = "Категории";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 0;
$plugin_config["sort"] = "order";
$plugin_config["order"] = false;
$plugin_config["header"] = "header.tpl";
$plugin_config["body"] = "example.tpl";
$plugin_config["footer"] = "footer.tpl";

#Разделы магазина разбитые на каталоги
$plugin_column["category"][] = array(
	"name" => "parent",
	"type" => "select",
	"title" => "Родительский каталог",
	"default" => 0,
	"selectname" => "category"
);
$plugin_column["category"][] = array(
	"name" => "child",
	"type" => "select",
	"title" => "Дочерних элементов",
	"default" => 0,
	"selectname" => "category"
);
$plugin_column["category"][] = array(
	"name" => "showchild",
	"type" => "tinyint",
	"title" => "Видимость дочерних элементов",
	"default" => 0
);
$plugin_column["category"][] = array(
	"name" => "image",
	"type" => "varchar",
	"title" => "Изображение",
	"default" => "",
	"filebrowser" => 1
);
$plugin_column["category"][] = array(
	"name" => "text",
	"type" => "text",
	"title" => "Описание"
);

?>