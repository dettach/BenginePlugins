<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "partners";
$plugin_config["title"] = "Партнеры";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 10;
$plugin_config["order"] = true;
$plugin_config["body"] = "partners.tpl";

$plugin_column["partners"][] = array(
	"name" => "image",
	"type" => "varchar",
	"title" => "Изображение",
	"default" => "",
	"filebrowser" => 1
);
$plugin_column["partners"][] = array(
	"name" => "link",
	"type" => "varchar",
	"title" => "Сайт",
	"default" => "",
	"filebrowser" => 1
);
$plugin_column["partners"][] = array(
	"name" => "text",
	"type" => "text",
	"title" => "Информация",
	"default" => ""
);
	
?>