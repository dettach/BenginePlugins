<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

/* 
 Pagination.ru — это простое в установке и настройке решение на языке PHP (не ниже версии 5.3 у вас версия ниже?),
 позволяющее быстрое создание т.н. пагинации (от английского слова pagination — порядковая нумерация страниц) —
 разбивки большого количества информации из таблицы реляционной базы данных на небольшие виртуальные страницы,
 а также создание визуального интерфейса на языке HTML, позволяющего делать переход по этим страницам.
 По всем вопросам пишите на makogon.vs[собака]gmail.com
 http://www.pagination.ru
 
	{if isset($nav.list) and count($nav.list) > 1}		
		{$pg = pagination($nav.num,"10",$nav.count,"p","s")}
		<div class="pagination pagination-right">
			<ul>
				<li><a href="{$pg.get_url}p=1&s=1">««</a></li>
				<li><a href="{$pg.get_url}p={$pg.page_previous}&s={$pg.separator_previous_block}">«</a></li>
				{foreach $pg.template as $v}
					<li {if isset($get.p) and $get.p == $v.page}class="active"{/if}><a href="{$pg.get_url}p={$v.page}&s={$pg.separator_current}">{$v.page}</a></li>
				{/foreach}
				<li><a href="{$pg.get_url}p={$pg.page_next_block}&s={$pg.separator_next_block}">»</a></li>
				<li><a href="{$pg.get_url}p={$pg.page_last}&s={$pg.separator_last}">»»</a></li>
			</ul>
		</div>
	{/if}
*/

function pagination($costRows, $nextPage, $navCount, $page = "page", $sep = "sep")
{
	global $nodes;
	include_once(ROOT_DIR."/plugins/pagination/Manager.php");
	$result = array();
	
	#Инстанцирование объекта
	$paginationManager = new Krugozor_Pagination_Manager($costRows, $nextPage, $_GET, $page, $sep );
	
	#Установка количества строк, которые вернул бы SQL-запрос, если бы LIMIT не был указан
	$paginationManager->setCount($navCount);

	#Возвращает имя переменной из запроса, содержащей номер сепаратора
	$result["separator_name"] = $paginationManager->getSeparatorName();
	#Возвращает номер сепаратора для формирования ссылки (««)
	$result["separator_previous_block"] = $paginationManager->getPreviousBlockSeparator();
	#Возвращает номер сепаратора для формирования ссылки (»»)
	$result["separator_next_block"] = $paginationManager->getNextBlockSeparator();
	#Возвращает номер сепаратора для формирования ссылки (»»»)
	$result["separator_last"] = $paginationManager->getLastSeparator();
	#Возвращает номер сепаратора для формирования ссылки («)
	$result["separator_previous_page"] = $paginationManager->getPreviousPageSeparator();
	#Возвращает номер текущего сепаратора
	$result["separator_current"] = $paginationManager->getCurrentSeparator();
	#Возвращает номер сепаратора для формирования ссылки (»)
	$result["separator_next_page"] = $paginationManager->getNextPageSeparator();
	
	#Возвращает имя переменной из запроса, содержащей номер страницы
	$result["page_name"] = $paginationManager->getPageName();
	#Возвращает номер текущей страницы
	$result["page_current"] = $paginationManager->getCurrentPage();
	#Возвращает номер страницы для формирования ссылки («)
	$result["page_previous"] = $paginationManager->getPreviousPage();
	#Возвращает номер страницы для формирования ссылки (««)
	$result["page_previous_block"] = $paginationManager->getPageForPreviousBlock();
	#Возвращает номер страницы для формирования ссылки (»)
	$result["page_next"] = $paginationManager->getNextPage();
	#Возвращает номер страницы для формирования ссылки (»»)
	$result["page_next_block"] = $paginationManager->getPageForNextBlock();
	#Возвращает номер страницы для формирования ссылки (»»»)
	$result["page_last"] = $paginationManager->getLastPage();
	
	#Возвращаем GET запрос
	if(isset($nodes["cfg"]["get"]) and $nodes["cfg"]["get"] != "") {
		$result["get_url"] = "?".$nodes["cfg"]["get"]."&";
	} else {
		$result["get_url"] = "?";
	}
	
	#Возвращает многомерный массив для цикла вывода в шаблоне
	$result["template"] = $paginationManager->getTemplateData();
	
	return $result;
}

?>