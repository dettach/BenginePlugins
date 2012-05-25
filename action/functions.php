<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

if(isset($_SESSION["admin"]) and $_SESSION["admin"] == true)
{
	#Запрос на добавление блока
	if(isset($nodes[2]) and $nodes[2] == "add" and !isset($nodes[3]) and isset($_POST["submit"]) and count($_POST["submit"]) > 0)
	{
		$_POST["title"] = bengine_chars($_POST["title"]);
		$_POST["text"] = bengine_chars_mysql(trim($_POST["text"]));
		
		if(add_post("action", $_POST) != true) {
			die("При добавлении блока возникла ошибка");
		}
		
		if(!cacheAdd("action")){
			die("Ошибка создания кэша. Обновите кэш в панели управления плагином.");
		}
		header("Location: /admin/action/");
		die();
	}

	#Запрос на редактирование новости
	elseif(isset($nodes[2]) and $nodes[2] == "edit" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($_POST["submit"]) and count($_POST["submit"]) > 0)
	{
		$_POST["title"] = bengine_chars($_POST["title"]);
		$_POST["text"] = bengine_chars_mysql(trim($_POST["text"]));
		
		if(edit_post("action", $_POST, $nodes[3]) != true) {
			die("При редактировании блока возникли ошибки");
		}
		
		if(!cacheAdd("action")){
			die("Ошибка создания кэша. Обновите кэш в панели управления плагином.");
		}
		header("Location: /admin/action/edit/".$nodes[3]."/");
		die();
	}

	#Запрос на редактирование новости
	elseif(isset($nodes[2]) and $nodes[2] == "delete" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		if(doquery("DELETE FROM `action` WHERE id=".$nodes[3]." LIMIT 1")) {	
			if(!cacheAdd("action")){
				die("Ошибка создания кэша. Обновите кэш в панели управления плагином.");
			}
			header("Location: /admin/action/");
			die();
		}
	}
}
?>