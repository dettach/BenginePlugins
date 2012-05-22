<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "articles";
$plugin_config["title"] = "Статьи";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 10;
$plugin_config["order"] = true;
$plugin_config["body"] = "articles.tpl";

$plugin_column["articles"][] = array(
	"name" => "image",
	"type" => "varchar",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Изображение",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["articles"][] = array(
	"name" => "anons",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Анонс",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["articles"][] = array(
	"name" => "text",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Текст",
	"visible" => 0,
	"filebrowser" => 0
);
	
?>