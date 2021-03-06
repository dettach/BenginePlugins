<?php
if( !defined("BENGINE") ) { die ("Hacking!"); }

include_once(ROOT_DIR."/plugins/category/arrays.php");
$smarty->assign("plugin_array",$plugin_array);
	
/* 
 * user - id пользователя
 * session - id сессии, если пользователь не авторизован
 * datetime - дата добавления в корзину
 * element - id элемента в каталоге
 * count [0..99] - количество выбранных элементов
 * old [0 or 1] - уже купленный ранее, попадает в историю заказов
 * price - стоимость одного элемента
*/

if(isset($_POST["cart"]) and $_POST["cart"] != "")
{
	###################################################################
	# Если пользователь не авторизован, то работаем с ID сессии
		if(!isset($_SESSION["id"])) {
			$user = "`session`='".session_id()."'";
			$into_user = '0';
			$into_session = session_id();
		} else {
			$user = "`user`='".$_SESSION["id"]."'";
			$into_user = $_SESSION["id"];
			$into_session = '';
		}
	#
	###################################################################
	# ID элемента или товара
		if(!empty($_POST["id"]) and (int)$_POST["id"] > 0) {
			$id = (int)$_POST["id"];	
		} else {
			$id = 0;
		}
	#
	###################################################################
	
	#Добавление в корзину, увеличение на единицу
	if($_POST["cart"] == "add" and $id > 0)
	{
		# Информация о продукте
		$sql = doquery("SELECT * FROM `product` WHERE id=".$id." LIMIT 1");
		$product = doassoc($sql);
		# стоимость одной единицы товара
		$price = (float)$product["price"];
	
		#Проверяем, возможно уже этот товар присутствует в БД
		$sql = doquery("SELECT * FROM `cart` WHERE ".$user." and `element`='".$id."' and `old`='0' LIMIT 1");
		if(dorows($sql) > 0) {
			if(doquery("UPDATE `cart` SET `count`=`count`+1,`datetime`='".DATETIME."',`price`='".$price."' WHERE ".$user." and `element`='".$id."' and `old`='0' LIMIT 1")) {
				die("1");
			} else {
				die("0");
			}
		}
		#Товара нет в БД, обновляем данные
		else {
			if(doquery("INSERT INTO `cart` SET 	
				`id` = NULL,
				`user` = ".$into_user.",
				`session` = '".$into_session."',
				`datetime` = '".DATETIME."',
				`element` = ".$id.",
				`count` = 1,
				`price` = '".$price."',
				`old` = 0
			;")) {
				die("1");
			} else {
				die("0");
			}
		}
	}

	#Уменьшение на единицу
	elseif($_POST["cart"] == "unadd" and $id > 0)
	{
		# Информация о продукте
		$sql = doquery("SELECT * FROM `product` WHERE id=".$id." LIMIT 1");
		$product = doassoc($sql);
		# стоимость одной единицы товара
		$price = (float)$product["price"];
		
		#Проверяем, возможно уже этот товар присутствует в БД
		$sql = doquery("SELECT * FROM `cart` WHERE ".$user." and `element`='".$id."' and `old`='0' LIMIT 1");
		if(dorows($sql) > 0) {
			$row = doassoc($sql);
			if($row["count"] > 1) {
				if(doquery("UPDATE `cart` SET `count`=`count`-1,`datetime`='".DATETIME."',`price`='".$price."' WHERE ".$user." and `element`='".$id."' and `old`='0' LIMIT 1")) {
					die("1");
				}
			}
			die("0");
		}
	}

	#Удаление
	elseif($_POST["cart"] == "delete" and $id > 0)
	{
		if(doquery("DELETE FROM `cart` WHERE ".$user." and `element`='".$id."' and `old`='0' LIMIT 1")) {
			die("1");
		} else {
			die("0");
		}
	}
	
	# Подсчет количества элементов в корзине	
	elseif($_POST["cart"] == "count")
	{
		$die = docount("cart", $user." and `old`='0'");
		die("".$die."");
	}
	
	# Подсчет количества элементов в корзине	
	elseif($_POST["cart"] == "countId" and $id > 0)
	{
		$sql = doquery("SELECT * FROM `cart` WHERE ".$user." and `element`='".$id."' and `old`='0' LIMIT 1");
		$row = doassoc($sql);
		die("".$row["count"]."");
	}

	# Подсчет общей стоимости
	elseif($_POST["cart"] == "price")
	{
		$sql = doquery("SELECT `count`, `price` FROM `cart` WHERE ".$user." and `old`='0' ORDER BY id DESC ");
		$die = 0;
		if(dorows($sql) > 0) {
			$result = doarray($sql);
			foreach($result as $v) {
				if((int)$v["price"] > 0) {
					$die = $die + ($v["count"] * $v["price"]);
				}
			}
		}
		die("".$die."");
	}
	
	# Ошибка запроса
	else {
		die("0");
	}
}

###########################################################################################################################

#Просмотр всех товаров в корзине
function cart()
{
	# Если пользователь не авторизован, то работаем с ID сессии
	if(!isset($_SESSION["id"])) {
		$user = "t1.session='".session_id()."'";
	} else {
		$user = "t1.user='".$_SESSION["id"]."'";
	}
	$sql = doquery("
	SELECT
		t1.id,t1.user,t1.session,t1.datetime,t1.count,t1.price,
		t2.title,t2.id AS product_id,t2.price AS product_price,t2.category AS product_category,t2.image AS product_image,t2.zachto AS product_zachto
	FROM
		cart AS t1 INNER JOIN
		product AS t2 ON (t2.id = t1.element)
	WHERE
		".$user." and
		t1.old = '0' and
		t1.count > 0
	ORDER BY
		t1.id DESC
	");
	if(dorows($sql) > 0) {
		$productTmp = doarray($sql);
		foreach($productTmp as $k => $v) {
			$product[$v["product_id"]] = $v;
			$v["product_image"] = str_replace("%20"," ",$v["product_image"]);
			if(file_exists(ROOT_DIR.$v["product_image"]) === false) {
				$product[$v["product_id"]]["product_image"] = "";
			}
		}
	} else {
		$product = array();
	}
	return $product;
}

#Просмотр ранее заказанных товаров, только для авторизованных пользователей
function cart_old()
{
	if(!isset($_SESSION["id"])) {
		return false;
	} else {	
		$sql = doquery("
		SELECT
			t1.id,t1.user,t1.session,t1.datetime,t1.count,t1.price,
			t2.title,t2.id AS product_id,t2.price AS product_price,t2.category AS product_category
		FROM
			cart AS t1 INNER JOIN
			product AS t2 ON (t2.id = t1.element)
		WHERE
			t1.user = '".$_SESSION["id"]."' and
			t1.old = '1' and
			t1.count > 0
		ORDER BY
			t1.id DESC
		");
		if(dorows($sql) > 0) {
			$productTmp = doarray($sql);
		} else {
			$product = array();
		}
		return $product;
	}
}

#Оформление заказа
function cart_pay()
{
	global $cfg;
	include_once(ROOT_DIR."/plugins/cart/config.php");
	
	# Список продуктов в корзине
	$product = cart();
	
	# Формируем часть письма, в котором указаны товары 	
	$i = 1;
	$c = 0;
	$cart = "";
	
	foreach($product as $key => $val) {
		$in[] = "[i]";
		$ou[] = $i;
		foreach($val as $k => $v) {
			$in[] = "[".$k."]";
			$ou[] = $v;
		}
		$cart .= str_replace($in, $ou, $plugin_array["msg_cart"]);
		$c = $c + ($val["price"] * $val["count"]);
		unset($in);
		unset($ou);
		$i++;
	}
	
	$msg["cart"] = $cart;
	$msg["result"] = $c;
}

/*
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
				$cart .= "".$i.". ".$v["title"]." (арт. ".$v["articul"].") - ".$v["count"]." шт по ".$v["price"]." руб. за шт<br />";
				$c = $c + ($v["price"]*$v["count"]);
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
		$body = "/templates/pay.tpl";
	}
*/
?>