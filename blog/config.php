<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "blog";
$plugin_config["title"] = "Блог";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 20;
$plugin_config["body"] = "blog.tpl";


$plugin_column["blog"][] = array(
	"name" => "image1",
	"type" => "varchar",
	"title" => "Изображение",
	"filebrowser" => 1
);
$plugin_column["blog"][] = array(
	"name" => "image2",
	"type" => "varchar",
	"title" => "Изображение",
	"filebrowser" => 1
);
$plugin_column["blog"][] = array(
	"name" => "image3",
	"type" => "varchar",
	"title" => "Изображение",
	"filebrowser" => 1
);
$plugin_column["blog"][] = array(
	"name" => "anons",
	"type" => "text",
	"title" => "Анонс"
);
$plugin_column["blog"][] = array(
	"name" => "text",
	"type" => "text",
	"title" => "Текст"
);
$plugin_column["blog"][] = array(
	"name" => "comments",
	"type" => "int",
	"title" => "Комментарий",
	"default" => "0",
);
	
?>