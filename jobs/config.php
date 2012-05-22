<?php

$plugin_config = array();
$plugin_config["admin"] = 0;
$plugin_config["name"] = "jobs";
$plugin_config["title"] = "Вакансии";
$plugin_config["extend"] = 0;
$plugin_config["cache"] = 1;
$plugin_config["limit"] = 0;
$plugin_config["order"] = true;
$plugin_config["body"] = "jobs.tpl";

$plugin_column["jobs"][] = array(
	"name" => "image",
	"type" => "varchar",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Изображение",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["jobs"][] = array(
	"name" => "location",
	"type" => "char",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Расположение",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["jobs"][] = array(
	"name" => "compensat",
	"type" => "char",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Компенсация",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["jobs"][] = array(
	"name" => "anons",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Требования к кандидату",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["jobs"][] = array(
	"name" => "text",
	"type" => "text",
	"attributes" => "",
	"null" => "NOT NULL",
	"default" => "",
	"title" => "Описание позиции",
	"visible" => 0,
	"filebrowser" => 0
);
	
?>