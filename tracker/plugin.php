<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

#Список тиккетов в проекте
if(isset($nodes[1]) and is_numeric($nodes[1]) > 0)
{
	$content["project"] = doqueryassoc("SELECT id,title FROM tracker_projects WHERE id='".$nodes[1]."' LIMIT 1");
	
	if(isset($nodes[2]) and is_numeric($nodes[2]) > 0) {
		if(isset($_SESSION["id"]) and isset($_POST["add_comment"])) {
			if($_POST["text"] != "") {
				$_POST["ticket"] = $nodes[2];
				$_POST["user"] = $_SESSION["id"];
				$_POST["text"] = nl2br($_POST["text"]);
				add("tracker_comments",$_POST);
			}
		}
		#Отдельный тиккет и комментарии
		$sql = doquery("SELECT
			t1.id,
			t1.user_in,
			t1.user_ou,
			t1.datetime,
			t1.status,
			t1.title,
			t1.text,
			t2.login AS login_in,
			t3.login AS login_ou
		FROM
			tracker_tickets AS t1 INNER JOIN
			users AS t2 ON (t2.id = t1.user_in) LEFT JOIN
			users AS t3 ON (t3.id = t1.user_ou)
		WHERE
			t1.project='".$nodes[1]."'
				and
			t1.id = '".$nodes[2]."'
		LIMIT 1
		");
		if(dorows($sql) > 0) {
			$content["ticket"] = doassoc($sql);
			$sql = doquery("
			SELECT
				t1.id,
				t1.datetime,
				t1.user,
				t1.text,
				t2.login
			FROM
				tracker_comments AS t1 INNER JOIN
				users AS t2 ON (t2.id = t1.user)
			WHERE
				t1.ticket='".$nodes[2]."'
			ORDER BY
				t1.id
			");
			if(dorows($sql) > 0) {
				$content["comments"] = doarray($sql);
			}
		}
	} else {
		#Добавление тикета
		if(isset($_SESSION["id"]) and isset($_POST["add_ticket"])) {
			if($_POST["title"] != "" and $_POST["text"] != "" and $_POST["user_ou"] != "") {
				$_POST["project"] = $nodes[1];
				$_POST["user_in"] = $_SESSION["id"];
				$_POST["status"] = 0;
				$_POST["text"] = nl2br($_POST["text"]);
				add("tracker_tickets",$_POST);
			}
		}
		#Список всех тиккетов
		$sql = doquery("SELECT
			t1.id,
			t1.user_in,
			t1.user_ou,
			t1.datetime,
			t1.status,
			t1.title,
			(SELECT COUNT(id) FROM tracker_comments WHERE ticket=t1.id) AS comments,
			t2.login AS login_in,
			t3.login AS login_ou
		FROM
			tracker_tickets AS t1 INNER JOIN
			users AS t2 ON (t2.id = t1.user_in) LEFT JOIN
			users AS t3 ON (t3.id = t1.user_ou)
		WHERE
			t1.project='".$nodes[1]."'
		ORDER BY
			t1.id and t1.status
		");
		if(dorows($sql) > 0) {
			$content["tickets"] = doarray($sql);
		}
	}
}
#Список проектов для багтрекера
else
{
	#Добавление проекта
	if(isset($_SESSION["id"]) and isset($_SESSION["admin"]) and $_SESSION["admin"] == 1 and isset($_POST["add_project"])) {
		if($_POST["title"] != "" and $_POST["text"] != "") {
			add("tracker_projects",$_POST);
		}
	}
	# Определяем сортировку
	$plugin_config["order"] == true ? $order = "DESC" : $order = "";
	$sql = doquery("SELECT t1.id,t1.title,t1.text, (SELECT COUNT(id) FROM tracker_tickets WHERE project=t1.id) AS count FROM tracker_projects AS t1 ORDER BY t1.id ".$order." ");
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
}
?>