<?php

$plugin_config = array();
$plugin_config["admin"] = 1;
$plugin_config["name"] = "register";
$plugin_config["title"] = "Регистрация";
$plugin_config["extend"] = 1;
$plugin_config["cache"] = 0;

#код авторизации
$plugin_config["auhtkey"] = 1;
#Соль в пароле при регистрации
$plugin_config["passkey"] = '';
#MIN символов логин
$plugin_config["int_login"] = 3;
#MIN символов пароль
$plugin_config["int_passw"] = 3;
#Группа по умолчанию
$plugin_config["reg_group"] = 4;
#Письмо о регистрации
$plugin_config["reg_mail"] = 1;
#Заголовок письма
$plugin_config["reg_title"] = "Регистрация ".$cfg["title"];
#Текст письма
$plugin_config["reg_text"] = "Вы зарегистрировались в ".$cfg["title"]."\r\n<br /><br />Ваш логин: {login}\r\nВаш пароль: {passw}\r\n<br /><br />Свяжитесь с администртором, чтобы активировать вашу учетную запись.";
#Письмо о восстановлен
$plugin_config["lost_mail"] = 1;
#Заголовок письма
$plugin_config["lost_title"] = "Восстановление пароля ".$cfg["title"];
#Текст письма
$plugin_config["lost_text"] = "Ваш логин: {login}\r\n<br />Ваш новый пароль: {passw}\r\n<br />Смените его после авторизации в личном кабинете.";

?>