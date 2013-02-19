<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

if(isset($_SESSION["admin"]) and $_SESSION["admin"] == true)
{
	#Запрос на добавление блока
	if(isset($nodes[2]) and $nodes[2] == "add" and !isset($nodes[3]) and isset($_POST["submit"]) and count($_POST["submit"]) > 0)
	{
		if(add("action", $_POST)) {
			cacheAdd("action");
			header("Location: /admin/action/");
		}
		die("При добавлении блока возникла ошибка");
	}

	#Запрос на редактирование блока
	elseif(isset($nodes[2]) and $nodes[2] == "edit" and isset($nodes[3]) and (int)$nodes[3] > 0 and isset($_POST["submit"]) and count($_POST["submit"]) > 0)
	{
		if(edit("action", $_POST, $nodes[3])) {
			cacheAdd("action");
			header("Location: /admin/action/edit/".$nodes[3]."/");
			die("При редактировании блока возникли ошибки");
		}
		die("При редактировании блока возникла ошибка");
	}

	#Запрос на редактирование новости
	elseif(isset($nodes[2]) and $nodes[2] == "delete" and isset($nodes[3]) and (int)$nodes[3] > 0)
	{
		if(doquery("DELETE FROM `action` WHERE id=".$nodes[3]." LIMIT 1")) {	
			cacheAdd("action");
			header("Location: /admin/action/");
			die();
		}
	}
}
?>