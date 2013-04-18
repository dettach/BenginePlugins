<?php

$plugin_config = array();
$plugin_config["admin"] = 2;
$plugin_config["name"] = "product";
$plugin_config["title"] = "Товары";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 20;
$plugin_config["sort"] = "order";
$plugin_config["order"] = false;
$plugin_config["header"] = "header.tpl";
$plugin_config["body"] = "product.tpl";
$plugin_config["footer"] = "footer.tpl";

include_once(ROOT_DIR."/plugins/category/arrays.php");

#Каждый товар в отдельности
$plugin_column["product"][] = array(
	"name" => "category",
	"type" => "select",
	"title" => "Категория",
	"selectname" => "category",
	"selecttitle" => "title",
	"default" => 0
);
$plugin_column["product"][] = array(
	"name" => "image",
	"type" => "varchar",
	"title" => "Изображение",
	"default" => "",
	"filebrowser" => 1
);
$plugin_column["product"][] = array(
	"name" => "naznach",
	"type" => "select",
	"title" => "Основное назначение",
	"selectname" => $plugin_array["naznach"],
	"default" => 0
);
$plugin_column["product"][] = array(
	"name" => "article",
	"type" => "varchar",
	"title" => "Артикул",
	"default" => ""
);
$plugin_column["product"][] = array(
	"name" => "price",
	"type" => "varchar",
	"title" => "Цена",
	"default" => ""
);
$plugin_column["product"][] = array(
	"name" => "zachto",
	"type" => "select",
	"title" => "За что",
	"selectname" => $plugin_array["zachto"],
	"default" => 1
);
$plugin_column["product"][] = array(
	"name" => "text",
	"type" => "text",
	"title" => "Описание",
	"default" => ""
);
$plugin_column["product"][] = array(
	"name" => "tags",
	"type" => "varchar",
	"title" => "Теги",
	"default" => ""
);

?>