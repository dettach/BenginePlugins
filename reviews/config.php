<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "reviews";
$plugin_config["title"] = "Отзывы";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 10;
$plugin_config["order"] = true;
$plugin_config["body"] = "reviews.tpl";

$plugin_column["reviews"][] = array(
	"name" => "image",
	"type" => "varchar",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Изображение",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["reviews"][] = array(
	"name" => "anons",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Анонс",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["reviews"][] = array(
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