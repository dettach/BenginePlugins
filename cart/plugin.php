<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }
	
if(empty($_SESSION["id"]))
{
	#404
	$php_sapi_name = php_sapi_name();
	if ($php_sapi_name == 'cgi' or $php_sapi_name == 'cgi-fcgi') {
		header('Status: 404 Not Found');
	} else {
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
	}
	$header = "/templates/".$cfg["template"]."/".$cfg["header"];
	$body   = "/templates/".$cfg["template"]."/".$cfg["error404"];
	$footer = "/templates/".$cfg["template"]."/".$cfg["footer"];
	$page["title"] = "404 Not Found";
	
}
else
{

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

	if(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and !empty($_POST["id"]))
	{
		#Добавление в корзину, увеличение на единицу
		if($_POST["go"] == "add")
		{
			$user = $_SESSION["id"];
			$id = $_POST["id"];
			
			#Проверяем, возможно уже этот товар присутствует в БД
			$sql = doquery("SELECT * FROM `cart` WHERE `user`='".$user."' and `element`='".$id."' LIMIT 1");
			if(dorows($sql) > 0) {
				if(doquery("UPDATE `cart` SET `count`=`count`+1,`datetime`=NOW() WHERE `user`='".$user."' and `element`='".$id."' and `old`='0' LIMIT 1")) {
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
		elseif(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and $_POST["go"] == "unadd" and !empty($_POST["id"]))
		{
			$user = $_SESSION["id"];
			$id = $_POST["id"];
			
			#Проверяем, возможно уже этот товар присутствует в БД
			$sql = doquery("SELECT * FROM `cart` WHERE `user`='".$user."' and `element`='".$id."' and `old`='0' LIMIT 1");
			if(dorows($sql) > 0) {
				if(doquery("UPDATE `cart` SET `count`=`count`-1,`datetime`=NOW() WHERE `user`='".$user."' and `element`='".$id."' and `old`='0' LIMIT 1")) {
					die("1");
				} else {
					die("0");
				}
			}
		}

		#Удаление
		elseif(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and $_POST["go"] == "delete" and !empty($_POST["id"]))
		{
			$user = $_SESSION["id"];
			$id = $_POST["id"];
			if(doquery("DELETE FROM `cart` WHERE `user`='".$user."' and `element`='".$id."' and `old`='0' LIMIT 1")) {
				die("1");
			} else {
				die("0");
			}
		}

		#Подсчет общей стоимости
		elseif(isset($_POST["plugin"]) and $_POST["plugin"] == "cart" and isset($_POST["go"]) and $_POST["go"] == "allcost" and !empty($_POST["id"]))
		{
			$sql = doquery("
			SELECT
				t1.count,t2.catalog,t2.cost
			FROM
				cart AS t1 INNER JOIN catalog_elements AS t2 ON (t2.id = t1.element)
			WHERE
				t1.user='".$_SESSION["id"]."' and t1.old = '0'
			ORDER BY
				t1.id DESC
			");
			$die = 0;
			if(count($result = doarray($sql)) > 0) {
				foreach($result as $v) {
					if($v["catalog"] == 282) {
						$die = $die + ($v["count"] * ($v["cost"] * 1.2));
					} else {
						$die = $die + ($v["count"] * $v["cost"]);
					}
				}
			}
			$die = number_format($die,2,',',' ');
			die("".$die."");
		}
	}

	#Просмотр содержимого корзины, манипуляция с данными, оформление заказа
	if(!empty($_SESSION["id"]))
	{
		#Просмотр всех товаров в корзине
		$sql = doquery("
		SELECT
			t1.id,t1.user,t1.element,t1.count,t2.title,t2.cost,t2.id AS id_element,t2.catalog,t2.articul
		FROM
			cart AS t1 INNER JOIN catalog_elements AS t2 ON (t2.id = t1.element)
		WHERE
			t1.user='".$_SESSION["id"]."' and t1.old = '0'
		ORDER BY
			t1.id DESC
		");
		if(dorows($sql) > 0) {
			$content = doarray($sql);
		}
		#mpr($content);

		#Оформление заказа
		if(isset($nodes[1]) and $nodes[1] == "pay")
		{
			if(isset($_POST["pay"])) {
				$post["user"] = $_SESSION;
				$post["post"] = $_POST;
				$cart = "";
				$orderId = 0;
				
				#Список товаров
				$i = 1;
				$c = 0;
				foreach($content as $v) {
					$cart .= "".$i.". ".$v["title"]." (арт. ".$v["articul"].") - ".$v["count"]." шт по ".$v["cost"]." руб. за шт<br />";
					$c = $c + ($v["cost"]*$v["count"]);
					$orderId = $v["id"];
					$i++;
				}
				$cart .= "Итого: <strong>".$c." рублей</strong>";
				
				#Формируем список данных для оплаты QIWI
				if(isset($_POST["oplata"]) and $_POST["oplata"] == "qiwi" and isset($_POST["qiwi"]) and $_POST["qiwi"] != "") {
					#Формируем список данных для оплаты:
					$q = array();
					$q["from"] = "204544";
					$q["to"] = $_POST["qiwi"];
					$q["summ"] = $c;
					$q["com"] = "Оплата%20за%20автодетали";
					$q["lifetime"] = "48";
					$q["check_agt"] = true;
					$q["txn_id"] = $_SESSION["id"].".".date("Ymd.His");
					$link = "https://w.qiwi.ru/setInetBill_utf.do?from=".$q["from"]."&to=".$q["to"]."&summ=".$q["summ"].".00&com=".$q["com"]."&lifetime=".$q["lifetime"]."&check_agt=".$q["check_agt"]."&txn_id=".$q["txn_id"]."";
					if(!($rq = file_get_contents($link))) {
						die("Ошибка оплаты. Используйте другой способ.");
					}
				}
				#Формируем список данных для оплаты RBK Money
				if(isset($_POST["oplata"]) and $_POST["oplata"] == "rbk") {
					#Формируем список данных для оплаты:
					$q = array();
					$q["eshopId"] = "2014891";
					$q["orderId"] = $orderId;
					$q["recipientAmount"] = $c;
					$q["recipientCurrency"] = "RUR";
					$q["serviceName"] = bengine_russian("Оплата за автодетали",true);
					$link = "https://rbkmoney.ru/acceptpurchase.aspx?eshopId=".$q["eshopId"]."&orderId=".$q["orderId"]."&recipientAmount=".$q["recipientAmount"]."&recipientCurrency=".$q["recipientCurrency"]."&serviceName=".$q["serviceName"]."";
					header("Location: ".$link); die("Ошибка оплаты. Используйте другой способ.");
				}
				
				#Текст берем как оформленную страницу
				$msg = $page["text"];
				$in = array("[cart]");
				$ou = array($cart);
				
				#Переменные для подмены данных в письме
				foreach($post as $key => $val) {
					foreach($val as $k => $v) {
						if(!is_array($v)) {
							# [user_adress] => $post["user"]["adress"]
							$in[] = "[".$key."_".$k."]";
							$ou[] = $v;
						}
					}
				}
				
				$msg = str_replace($in, $ou, $msg);
				if(bengine_mail($_SESSION["email"].",".$cfg["email"], "Заказ запчастей на Автодетали72", $msg)) {
					#Обновляем БД, что заказ был сделан
					doquery("
						UPDATE
							`cart`
						SET
							`old` = '1',
							`datetime` = '".DATETIME."',
							`payment` = '".$c."'
						WHERE
							`user` = '".$_SESSION["id"]."' and `old` = '0'
					");
					$result = 1;
				}
			}
			$body = "/templates/".$cfg["template"]."/pay.tpl";
		}
	}
}
?>