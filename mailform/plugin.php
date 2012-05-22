<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

if(isset($_POST["submit"]) and count($_POST) > 1)
{
	$msgIn = array("[datetime]","[ip]");
	$msgOu = array(DATETIME, IP);
	
	# Экранируем и чистим
	foreach($_POST as $k => $v)
	{
		$msgIn[] = "[".$k."]";
		$msgOu[] =  htmlspecialchars(trim($v), ENT_QUOTES, "UTF-8");
	}
	
	# Формируем и заменяем
	$msg = $page["text"];
	$msg = str_replace($msgIn, $msgOu, $msg);
	
	# Отправляем и ждем результат
	if(!bengine_mail($plugin_config["send_mail_list"], $plugin_config["title_mail_msg"].$page["title"], $msg)) {
		$result = "Ошибка отправки сообщения";
	} else {
		$result = 1;
	}
}
?>