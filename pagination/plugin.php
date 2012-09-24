<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

/* 
 Pagination.ru — это простое в установке и настройке решение на языке PHP (не ниже версии 5.3 у вас версия ниже?),
 позволяющее быстрое создание т.н. пагинации (от английского слова pagination — порядковая нумерация страниц) —
 разбивки большого количества информации из таблицы реляционной базы данных на небольшие виртуальные страницы,
 а также создание визуального интерфейса на языке HTML, позволяющего делать переход по этим страницам.
 По всем вопросам пишите на makogon.vs[собака]gmail.com
 http://www.pagination.ru
*/

include_once(ROOT_DIR."/plugins/pagination/Manager.php");
include_once(ROOT_DIR."/plugins/pagination/Helper.php");

function paginationManager($costRows,$nextPage,$navCount)
{
	$result = array();
	
	#Инстанцирование объекта
	$paginationManager = new Krugozor_Pagination_Manager($costRows, $nextPage, $_GET, $page = "page", $sep = "sep" );
	$paginationHelper = new Krugozor_Pagination_Helper($paginationManager);
	
	#Настройка внешнего вида пагинатора Хотим получить стандартный вид пагинации
	$paginationHelper->setPaginationType(Krugozor_Pagination_Helper::PAGINATION_NORMAL_TYPE);

	#Установка количества строк, которые вернул бы SQL-запрос, если бы LIMIT не был указан
	$paginationManager->setCount($navCount);

	/*
	$result["limit"] = $paginationManager->limit;
	$result["link_count"] = $paginationManager->link_count;
	$result["current_sep"] = $paginationManager->current_sep;
	$result["start_limit"] = $paginationManager->start_limit;
	$result["stop_limit"] = $paginationManager->stop_limit;
	$result["total_rows"] = $paginationManager->total_rows;
	$result["total_pages"] = $paginationManager->total_pages;
	$result["total_blocks"] = $paginationManager->total_blocks;
	$result["page_var_name"] = $paginationManager->page_var_name;
	$result["separator_var_name"] = $paginationManager->separator_var_name;
	$result["inctiment"] = $paginationManager->getAutoincrementNum();
	$result["table"] = $paginationManager->table;
	*/
	
	$result["getAutodecrementNum"] = $paginationManager->getAutodecrementNum();
	$result["getAutoincrementNum"] = $paginationManager->getAutoincrementNum();
	$result["getPreviousBlockSeparator"] = $paginationManager->getPreviousBlockSeparator();
	$result["getNextBlockSeparator"] = $paginationManager->getNextBlockSeparator();
	$result["getLastSeparator"] = $paginationManager->getLastSeparator();
	$result["getLastPage"] = $paginationManager->getLastPage();
	$result["getCurrentPage"] = $paginationManager->getCurrentPage();
	$result["getCurrentSeparator"] = $paginationManager->getCurrentSeparator();
	$result["getPreviousPageSeparator"] = $paginationManager->getPreviousPageSeparator();
	$result["getNextPageSeparator"] = $paginationManager->getNextPageSeparator();
	$result["getPreviousPage"] = $paginationManager->getPreviousPage();
	$result["getPageForPreviousBlock"] = $paginationManager->getPageForPreviousBlock();
	$result["getNextPage"] = $paginationManager->getNextPage();
	$result["getSeparatorName"] = $paginationManager->getSeparatorName();
	$result["getPageName"] = $paginationManager->getPageName();
	$result["getTemplateData"] = $paginationManager->table;
	
	return $result;
}

?>