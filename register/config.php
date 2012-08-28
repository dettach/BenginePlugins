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
$plugin_config["reg_group"] = 2;
#Письмо о регистрации
$plugin_config["reg_mail"] = 1;
#Заголовок письма
$plugin_config["reg_title"] = "Регистрации на сайте";
#Текст письма
$plugin_config["reg_text"] = "Спасибо за регистрацию на сайте.\r\nВаш логин: {login}\r\nВаш пароль: {passw}\r\n";
#Письмо о восстановлен
$plugin_config["lost_mail"] = 1;
#Заголовок письма
$plugin_config["lost_title"] = "Восстановление пароля на сайте";
#Текст письма
$plugin_config["lost_text"] = "Ваш логин: {login}\r\nВаш новый пароль: {passw}";
?>