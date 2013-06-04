<?php

# Очищаем данные предыдущего плагина
$plugin_config = array();

###############################################################################################
# ОБЯЗАТЕЛЬНЫЕ ПАРАМЕТРЫ
###############################################################################################

# Определяем принадлежность к панели управления
# 0 - плагин доступен всем, имеет редактируемые данные
# 1 - плагин доступен только администраторам, может не иметь данных, сервисный
# 2 - заголовок отображается в верхнем навигационном меню панели управления для быстрого доступа
$plugin_config["admin"] = 0;

# Определяем имя плагина, должно совпадать с названием папки плагина
# Основные условия - имя плагина должно быть написано латинскими буквами
# имя должно быть уникальным, иначе будет ошибка добавления в БД
$plugin_config["name"] = "example";

# Название плагина на русском для удобства доступа
$plugin_config["title"] = "Пример плагина";

###############################################################################################
# НЕ ОБЯЗАТЕЛЬНЫЕ ПАРАМЕТРЫ
###############################################################################################

# Если нужно, чтобы при добавлении данных через админку вместо главного поля ЗАГОЛОВОК
# было написано иначене, нужно изменить это значение

$plugin_config["column_title"] = "Заголовок";

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
# то нужно вписать название шаблонов или то удалить
$plugin_config["header"] = "header.tpl";
$plugin_config["body"] = "example.tpl";
$plugin_config["footer"] = "footer.tpl";

###############################################################################################
# СОЗДАНИЕ ТАБЛИЦ ПЛАГИНА
# $plugin_column["_название_таблицы_"][] = array(_параметры_колонки_таблицы_);
# Типы данных в таблице (varchar, text, int, datetime, checkbox, select, multiselect)
# 
# КОЛОНКИ (name) НЕЛЬЗЯ НАЗЫВАТЬ:
# id,page,order,menu,datetime,title,description,keywords,header,body,footer
###############################################################################################

# ТЕКСТОВОЕ ПОЛЕ
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (varchar)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (string or var)
# visible - видимость столбца при просмотре списка данных в плагине админки (bool)
# filebrowser - для колонки необходимо подключение к файловому обозревателю (bool)
# unique - уникальный ключ (bool)
$plugin_column["example"][] = array(
	"name" => "first_name",
	"type" => "varchar",
	"title" => "Ваше имя"
);

# ЦИФРОВОЕ ПОЛЕ
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (int)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (int or var)
# visible - видимость столбца при просмотре списка данных в плагине админки (bool)
# unique - уникальный ключ (bool)
$plugin_column["example"][] = array(
	"name" => "you_age",
	"type" => "varchar",
	"title" => "Возраст"
);

# БОЛЬШОЕ ТЕКСТОВОЕ ПОЛЕ
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (text,londtext)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (string or var)
$plugin_column["example"][] = array(
	"name" => "anons",
	"type" => "text",
	"title" => "Анонс"
);

# ПОЛЕ С ДАТОЙ И ВРЕМЕНЕМ
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (datetime)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (string or var)
# visible - видимость столбца при просмотре списка данных в плагине админки (bool)
$plugin_column["example"][] = array(
	"name" => "date_news",
	"type" => "datetime",
	"title" => "Время публикации"
);

# ФЛАЖОК
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (checkbox)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (bool)
$plugin_column["example"][] = array(
	"name" => "sale_product",
	"type" => "checkbox",
	"title" => "Распродажа"
);

# ВЫПАДАЮЩИЙ СПИСОК С ОДНИМ ЭЛЕМЕНТОМ
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (select)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (int)
# selectname - какой таблице или массиву обращаться за данными (string or array)
# selectkey - данный параметр показывает, что поле будет видимо как ключ (string or int)
# selecttitle - данный параметр показывает, что поле будет видимо как значение (string)
$plugin_column["example"][] = array(
	"name" => "page_parent",
	"type" => "multiselect",
	"title" => "Раздел страницы",
	"default" => 0,
	"selectname" => "pages",
	"selecttitle" => "title",
);

# ВЫПАДАЮЩИЙ СПИСОК С НЕСКОЛЬКИМИ ЭЛЕМЕНТАМИ
# name (обязательно) - название колонки в БД
# type (обязательно) - тип данных (multiselect)
# title (обязательно) - отображаемое название в админке (string)
# default - значение по умолчанию при установке или обновлении (int)
# selectname - какой таблице или массиву обращаться за данными (string or array)
# selectkey - данный параметр показывает, что поле будет видимо как ключ, default:id (string or int)
# selecttitle - данный параметр показывает, что поле будет видимо как значение, default:title (string)
$plugin_column["example"][] = array(
	"name" => "catalog_parent",
	"type" => "multiselect",
	"title" => "Разделы каталога",
	"selectname" => "pages",
	"selecttitle" => "title",
);
$color = array(
	1 => 'белый',
	2 => 'синий',
	3 => 'красный',
	4 => 'зеленый',
	5 => 'фиолетовый',
);
$plugin_column["example"][] = array(
	"name" => "catalog_color",
	"type" => "multiselect",
	"title" => "Цвета",
	"selectname" => $color
);
	
?>