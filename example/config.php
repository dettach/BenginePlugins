<?php

# Очищаем данные предыдущего плагина
$plugin_config = array();

# ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР
# Определяем принадлежность к панели управления
$plugin_config["admin"] = 0;

# ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР
# Определяем имя плагина, должно совпадать с названием папки плагина
# Основные условия - имя плагина должно быть написано латинскими буквами
# имя должно быть уникальным, иначе будет ошибка добавления в БД
$plugin_config["name"] = "example";

# ОБЯЗАТЕЛЬНЫЙ ПАРАМЕТР
# Название плагина на русском для удобства доступа
$plugin_config["title"] = "Пример плагина";

# К плагину можно обращаться в любой момент, а не только, когда он встроен
# в определенную страницу, для этого установите параметр extend = 1
$plugin_config["extend"] = 0;

# Настройки конфигурации плагина. Всключение кэширования, в этом случае создается
# глобальная переменная $cache_[имя_плагина], доступ к которой может
# осуществляться в любом месте шаблона или других плагинов. В ней сосредоточена
# вся структура таблицы плагина в БД. Возможные параметры: 1 или 0
$plugin_config["cache"] = 1;

# Количество элементов на страницу, в том числе и в администрировании
$plugin_config["limit"] = 0;

#Колонка по которой будет происходить сортировка
$plugin_config["sort"] = "order";

#Сортировка по возрастанию (false) или по убыванию (true)
$plugin_config["order"] = true;

# Если плагин подразумевает использование шаблонов для отображения дизайна,
# то нужно вписать название для шапки сайта(header), тела плагина(body),
# низа сайта(footer), если нет, то оставить их пустыми
$plugin_config["header"] = "header.tpl";
$plugin_config["body"] = "example.tpl";
$plugin_config["footer"] = "footer.tpl";

# Определяем столбцы, которые нужны дополнительно к уже существующим
# Основные параметры, которые необходимы для правильной установки и обновления:
# name (обязательно) - название колонки, например image,file,anons,text
# type (обязательно) - тип данных, цифры или текст varchar, text, int, checkbox, select
# title (обязательно) - Название колонки
# visible - видимость столбца при просмотре списка данных в плагине админки (заголовок, дата, действия, свой вариант)
# filebrowser - для колонки необходимо подключение к файловому обозревателю
# selectname - при типе данных select поле показывает к какой таблице обращаться за данными
# default - значение по умолчанию при установке или обновлении, или например $_SESSION["login"]
# selecttitle - при типе данных select данный параметр показывает, что поле selecttitle бедет видимо как значение <option value="$k">$v[selecttitle]</option>
# selectkey - при типе данных select данный параметр показывает, что поле selectkey бедет видимо как ключ <option value="$v[selectkey]">$v</option>


$plugin_column["example"][] = array(
	"name" => "image",
	"type" => "varchar",
	"title" => "Изображение",
	"default" => "",
	"visible" => 0,
	"filebrowser" => 1
);
$plugin_column["example"][] = array(
	"name" => "name",
	"type" => "varchar",
	"title" => "Имя",
	"default" => "",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["example"][] = array(
	"name" => "datepub",
	"type" => "datetime",
	"title" => "Время публикации",
	"default" => "0000-00-00 00:00:00",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["example"][] = array(
	"name" => "mainpage",
	"type" => "checkbox",
	"title" => "На главной",
	"default" => 1,
	"visible" => 0,
	"filebrowser" => 0
);

$plugin_column["example"][] = array(
	"name" => "selectpage",
	"type" => "select",
	"title" => "Раздел страницы",
	"default" => 0,
	"selectname" => "pages",
	"selecttitle" => "title",
);
$plugin_column["example"][] = array(
	"name" => "anons",
	"type" => "text",
	"title" => "Анонс",
	"default" => "",
	"visible" => 0,
	"filebrowser" => 0
);
$plugin_column["example"][] = array(
	"name" => "text",
	"type" => "text",
	"title" => "Наполнение",
	"default" => "",
	"visible" => 0,
	"filebrowser" => 0
);
	
?>