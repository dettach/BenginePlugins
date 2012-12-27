<?php

	$plugin_config = array();

	$plugin_config["admin"] = 1;
	
	$plugin_config["name"] = "mailform";
	
	$plugin_config["title"] = "Отправка форм";
	
	$plugin_config["extend"] = 0;
	
	$plugin_config["cache"] = 0;

	$plugin_config["send_mail_list"] = $cfg["email"];
	
	$plugin_config["title_mail_msg"] = "Собщение с сайта ".$cfg["url"].". ";
	
?>