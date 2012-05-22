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
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Изображение",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["partners"][] = array(
	"name" => "text",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Информация",
	"visible" => 0,
	"filebrowser" => 0
);
	
?>