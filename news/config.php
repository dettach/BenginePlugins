<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "news";
$plugin_config["title"] = "Новости";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 20;
$plugin_config["order"] = true;
$plugin_config["sort"] = "order";
$plugin_config["body"] = "news.tpl";

$plugin_column["news"][] = array(
	"name" => "image",
	"type" => "varchar",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Изображение",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["news"][] = array(
	"name" => "anons",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Анонс",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["news"][] = array(
	"name" => "text",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Наполнение",
	"visible" => 0,
	"filebrowser" => 0
);
	
?>