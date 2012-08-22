<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }
	
/*
  user - id пользователя
  element - id элемента в каталоге
  count - количество выбранных элементов
  old - уже купленный ранее, попадает в историю заказов
  #только для уже купленных элементов#
  datetime - дата последнего заказа
  payment - метод оплаты
  cost - стоимость всех элементов
*/

if(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and !empty($_POST["id"]) and !empty($_SESSION["id"]))
{
	#Добавление в корзину, увеличение на единицу
	if($_POST["go"] == "add")
	{
		$user = $_SESSION["id"];
		$id = $_POST["id"];
		
		#Проверяем, возможно уже этот товар присутствует в БД
		$sql = doquery("SELECT * FROM `cart` WHERE `user`='".$user."' and `element`='".$id."' LIMIT 1");
		if(dorows($sql) > 0) {
			if(doquery("UPDATE `cart` SET `count`=`count`+1,`datetime`=NOW() WHERE `user`='".$user."' and `element`='".$id."' LIMIT 1")) {
				die("1");
			} else {
				die("0");
			}
		}
		#Товара нет в БД, обновляем данные
		else {
			if(doquery("INSERT INTO `cart` SET `id`=NULL,`user`='".$user."',`element`='".$id."',`count`='1',`old`='0',`datetime`=NOW(),`payment`='',`cost`='0' ;")) {
				die("1");
			} else {
				die("0");
			}
		}
	}

	#Уменьшение на единицу
	if(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and $_POST["go"] == "unadd" and !empty($_POST["id"]) and !empty($_SESSION["id"]))
	{
		$user = $_SESSION["id"];
		$id = $_POST["id"];
		
		#Проверяем, возможно уже этот товар присутствует в БД
		$sql = doquery("SELECT * FROM `cart` WHERE `user`='".$user."' and `element`='".$id."' LIMIT 1");
		if(dorows($sql) > 0) {
			if(doquery("UPDATE `cart` SET `count`=`count`-1,`datetime`=NOW() WHERE `user`='".$user."' and `element`='".$id."' LIMIT 1")) {
				die("1");
			} else {
				die("0");
			}
		}
	}

	#Удаление
	if(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and $_POST["go"] == "delete" and !empty($_POST["id"]) and !empty($_SESSION["id"]))
	{
		$user = $_SESSION["id"];
		$id = $_POST["id"];
		if(doquery("DELETE FROM `cart` WHERE `user`='".$user."' and `element`='".$id."' LIMIT 1")) {
			die("1");
		} else {
			die("0");
		}
	}

	#Подсчет общей стоимости
	if(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and $_POST["go"] == "allcost" and !empty($_POST["id"]) and !empty($_SESSION["id"]))
	{
		$sql = doquery("
		SELECT
			t1.count,t2.cost
		FROM
			cart AS t1 INNER JOIN catalog_elements AS t2 ON (t2.id = t1.element)
		WHERE
			t1.user='".$_SESSION["id"]."'
		ORDER BY
			t1.id DESC
		");
		$die = 0;
		if(count($result = doarray($sql)) > 0) {
			foreach($result as $v) {
				$die = $die + ($v["count"] * $v["cost"]);
			}
		}
		die("".$die."");
	}
}
#Просмотр содержимого корзины, манипуляция с данными
if(!empty($_SESSION["id"]))
{
	$sql = doquery("
	SELECT
		t1.id,t1.user,t1.element,t1.count,t2.title,t2.cost,t2.id AS id_element,t2.catalog
	FROM
		cart AS t1 INNER JOIN catalog_elements AS t2 ON (t2.id = t1.element)
	WHERE
		t1.user='".$_SESSION["id"]."'
	ORDER BY
		t1.id DESC
	");
	if(dorows($sql) > 0) {
		$content = doarray($sql);
	}
	#mpr($content);

}
?>