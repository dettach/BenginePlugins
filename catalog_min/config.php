<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "catalog_min";
$plugin_config["title"] = "Каталог";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 0;
$plugin_config["order"] = true;
$plugin_config["body"] = "catalog.tpl";

$plugin_column["catalog_min"][] = array(
	"name" => "image_min",
	"type" => "varchar",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Фото (min)",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["catalog_min"][] = array(
	"name" => "image_max",
	"type" => "varchar",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Фото (max)",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["catalog_min"][] = array(
	"name" => "text",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Описание",
	"visible" => 0,
	"filebrowser" => 0
);
	
?>