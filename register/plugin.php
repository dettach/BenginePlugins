<?phpif( !defined("BENGINE") ) { die ("Hacking!"); }#Подтверждение емайла при регистрацииif(isset($_GET["login"]) and $_GET["login"] != "" and isset($_GET["auhtkey"]) and $_GET["auhtkey"] != ""){	$login = bengine_strip_tags($_GET["login"]);	$auhtkey = bengine_strip_tags($_GET["auhtkey"]);		if($login != "" and $auhtkey != "")	{		if(($sqlauht = doquery("SELECT * FROM users WHERE login='".$login."' and auhtkey='".$auhtkey."' LIMIT 1")) != false) {			if(dorows($sqlauht) == 1) {				if(doquery("UPDATE users SET auhtkey='' WHERE login='".$login."' LIMIT 1")) {					echo '					<title>Вы успешно подтвердили свой e-mail</title>					<script type="text/javascript">					function locate() {						document.location.href = "/";						return false;					}					setTimeout(locate,1000);					</script>					';					die("Вы успешно подтвердили свой e-mail. Для продолжение работы перейдите по на главную <a href=\"/\">страницу</a>");				}			}		}	}}#Авторизация через cookieif(isset($_COOKIE["login"]) and isset($_COOKIE["passw"]) and $_COOKIE["login"] != "" and $_COOKIE["passw"] != ""){	$login = $_COOKIE["login"];	$passw = $_COOKIE["passw"];	$sql = doquery("SELECT * FROM `users` WHERE `login`='".$login."' and `passw`='".$passw."' LIMIT 1");	if(dorows($sql) == 1) {		$user = doassoc($sql);		$_SESSION = $user;		$usergroup = doqueryassoc("SELECT `admin`,`permission` FROM `groups` WHERE `id`='".$user["group"]."' LIMIT 1");		$usergroup["admin"] == 1 ? $_SESSION["admin"] = 1 : $_SESSION["admin"] = 0;		$_SESSION["permission"] = unserialize($usergroup["permission"]);		if(doquery("UPDATE `users` SET `auht`=NOW(),`ip`='".IP."' WHERE login='".$login."' LIMIT 1")) {			setcookie("login",$user["login"],time()+60*60*24*30);			setcookie("passw",$user["passw"],time()+60*60*24*30);		}	} else {		setcookie("login","",time()-3600);		setcookie("passw","",time()-3600);	}}#Регистрация пользователяif(isset($_POST["plugin_register"]) and $_POST["plugin_register"] == "register"){	$result = "";		#Проверка ЛОГИНА	if(isset($_POST["login"]) and $_POST["login"] == "") {		$result .= "Не заполено поле Логин<br />";	} elseif(mb_strlen($_POST["login"]) < $plugin_config["int_login"]) {		$result .= "В поле Логин должно быть не менее ".$plugin_config["int_login"]." символов <br />";	} else {		$login = bengine_chars($_POST["login"]);	}		#Проверка ПАРОЛЯ	if(isset($_POST["passw"]) and $_POST["passw"] == "") {		$result .= "Не заполено поле Пароль <br />";	} elseif(mb_strlen($_POST["passw"]) < $plugin_config["int_passw"]) {		$result .= "В поле Пароль должно быть не менее ".$plugin_config["int_passw"]." символов <br />";	} else {		$passw = $_POST["passw"];	}	if(isset($_POST["passw_confirm"]) and $_POST["passw_confirm"] == "") {		$result .= "Заполните Пароль повторно <br />";	}	if(isset($_POST["passw"]) and $_POST["passw"] != "" and isset($_POST["passw_confirm"]) and $_POST["passw_confirm"] != "") {		if(md5($_POST["passw_confirm"]) != md5($_POST["passw"])) {			$result .= "Введенные пароли не совпадают <br />";		} else {			$passw = $_POST["passw"];		}	}		#Проверка ПОЧТОВОГО ЯЩИКА	if(isset($_POST["email"]) and $_POST["email"] == "") {		$result .= "Не заполено поле E-mail <br />";	}	if(isset($_POST["email"]) and $_POST["email"] != "" and filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) == false) {		$result .= "Введите правильный E-mail <br />";	} else {		$email = $_POST["email"];	}		#Проверка полей созданных в Админке	if(($fields = dotable("users_fields")) != false)	{		foreach($fields as $k => $v)		{			if(isset($_POST[$v["name"]]))			{				$_POST[$v["name"]] = bengine_chars($_POST[$v["name"]]);				if($v["check"] == 1 and $_POST[$v["name"]] == "")				{					$result .= "Не заполено поле ".$v["title"]."<br />";				}				elseif($v["check"] == 1 and mb_strlen($_POST[$v["name"]]) < $v["minimal"] and $v["minimal"] != 0)				{					$result .= "В поле ".$v["title"]." должно быть не менее ".$v["minimal"]." символов <br />";				}			}		}	}		#Проверка CAPTCHA	if(isset($_POST["antibot"]) and $_POST["antibot"] == "" and isset($_SESSION["antibot"])) {		$result .= "Не заполено поле Код проверки <br />";	}	if(isset($_POST["antibot"]) and $_POST["antibot"] != "" and isset($_SESSION["antibot"])) {		if($_SESSION["antibot"] != md5($_POST["antibot"])) {			$result .= "Заполните Код проверки правильно<br />";		}	}		#Проверка СВОБОДНОГО ИМЕНИ	if(isset($login) and $login != "") {		if(doqueryassoc("SELECT * FROM `users` WHERE `login`='".$login."' LIMIT 1")) {			$result .= "Такой логин уже зарегистрирован <br />";		}	}		#Проверка СВОБОДНОГО МЫЛА	if(isset($email) and $email != "") {		if(doqueryassoc("SELECT * FROM `users` WHERE `email`='".$email."' LIMIT 1")) {			$result .= "Такой e-mail уже зарегистрирован <br />";		}	}		#Процедура регистрации	if($result == "")	{		#Подтверждение мыла указанного при регистрации				#Пароль для регистрации		if(isset($plugin_config["auhtkey"]) and $plugin_config["auhtkey"] == 1) {			$auhtkey = md5(md5(time()));			$auhtlink = "http://".$cfg["url"]."/?login=".$login."&auhtkey=".$auhtkey."";		} else {			$auhtkey = "";			$auhtlink = "";		}				$_POST["auhtkey"] = $auhtkey;		$_POST["passw"] = md5($_POST["passw"].$plugin_config["passkey"]);		$_POST["menu"] = 0;		$_POST["reg"] = DATETIME;		$_POST["auht"] = DATETIME;		$_POST["ip"] = IP;		$_POST["root"] = 0;		$_POST["group"] = $plugin_config["reg_group"];				#Добавление в БД		if(add_post("users", $_POST) == false) {			$result = "Ошибка при регистрации <br />";		} else {			#Отправка сообщения о регистрации			if($plugin_config["reg_mail"] == 1) {							$msgtext = $plugin_config["reg_text"];				$msgtext = str_replace("{login}", $login, $msgtext);				$msgtext = str_replace("{passw}", $passw, $msgtext);				$msgtext = str_replace("{title}", $cfg["title"], $msgtext);				$msgtext = str_replace("{email}", $email, $msgtext);				$msgtext = str_replace("{auth}", $auhtlink, $msgtext);				$msgtext = str_replace("<br />", "\r\n", $msgtext);								if(bengine_mail($email, $plugin_config["reg_title"], $msgtext)) {					unset($_SESSION["antibot"]);					$result = 1;				} else {					$result = "Вы успешно зарегистрировались<br />, но при отправке сообщения возникла ошибка.<br />";				}								#Отправка мыла администратору				#bengine_mail($cfg["email"], "Зарегистрирован новый пользователь", "Зарегистрирован новый пользователь <b>".$login."</b>");			} else {				$result = 1;			}		}	}		if(isset($_GET["die"])) {		die("".$result."");	}}#Авторизация пользователяif(isset($_POST["plugin_register"]) and $_POST["plugin_register"] == "login"){		if(isset($_POST["captcha"]) and $_POST["captcha"] != "") {		header("Location: /");		die();	}		$result = "";		#Проверка ЛОГИНА	if(isset($_POST["login"]) and $_POST["login"] == "") {		$result .= "Заполните поле Логин <br />";	} else {		$login = bengine_chars($_POST["login"]);	}		#Проверка ПАРОЛЯ	if(isset($_POST["passw"]) and $_POST["passw"] == "") {		$result .= "Заполните ваш Пароль <br />";	} else {		$pass = md5($_POST["passw"].$plugin_config["passkey"]);	}		#Проверка CAPTCHA	if(isset($_POST["antibot"]) and $_POST["antibot"] == "" and isset($_SESSION["antibot"])) {		$result .= "Заполните Код проверки <br />";	}	if(isset($_POST["antibot"]) and $_POST["antibot"] != "" and isset($_SESSION["antibot"])) {		if($_SESSION["antibot"] != md5($_POST["antibot"])) {			$result .= "Заполните Код проверки правильно<br />";		}	}		#Проверяем наличие пользователя и совпадение пароля	if(isset($login) and ($user = doqueryassoc("SELECT * FROM `users` WHERE `login`='".$login."' or `email`='".$login."' LIMIT 1")) == false) {		$result = "Такой пользователь не найден<br />";	} else {		if(isset($pass) and $pass != $user["passw"]) {			$result .= "Не верно введен Пароль<br />";		}		if(isset($user["auhtkey"]) and $user["auhtkey"] != "") {			$result .= "Подтвердите свой E-mail. При регистрации вам было выслано письмо информацией об активации аккаунта.<br />";		}	}		#Авторизация	if($result == "")	{		if($user["auht"] == 1) {			$result = "Подтвердите свой E-mail адрес.<br />При регистрации вам было выслано письмо с ссылкой.<br />";		}		else		{			$_SESSION = $user;						$usergroup = doqueryassoc("SELECT `admin`,`permission` FROM `groups` WHERE `id`='".$user["group"]."' LIMIT 1");			if($usergroup["admin"] == 1) {				$_SESSION["admin"] = 1;			} else {				$_SESSION["admin"] = 0;			}			$_SESSION["permission"] = unserialize($usergroup["permission"]);						if(doquery("UPDATE `users` SET `auht`=NOW(),`ip`='".IP."' WHERE login='".$login."' LIMIT 1")) {				if(isset($_POST["remember"])) {					setcookie("login",$user["login"],time()+60*60*24*30);					setcookie("passw",$user["passw"],time()+60*60*24*30);				}				$result = 1;			} else {				unset($_SESSION);				$result = "При авторизации возникла ошибка.<br />Обратитесь к администратору.<br />";			}		}	}		unset($_SESSION["antibot"]);		if(isset($_GET["die"])) {		die("".$result."");	} else {		if($result == "") {			header("Location: /");			die();		}	}}#Забали парольif(isset($_POST["plugin_register"]) and $_POST["plugin_register"] == "lostpass"){	if(isset($_POST["captcha"]) and $_POST["captcha"] != "") {		header("Location: /");		die();	}	$result = "1";		#Проверка ЛОГИНА ИЛИ МЫЛА	if(isset($_POST["lostpass"]) and $_POST["lostpass"] == "") {		$result .= "Заполните поле логин <br />";	} else {		$lostpass = $_POST["lostpass"];		if(filter_var($_POST["lostpass"], FILTER_VALIDATE_EMAIL) == false) {			$lostpass = bengine_chars($lostpass);		}	}		#Проверка CAPTCHA	if(isset($_SESSION["antibot"]) and isset($_POST["antibot"])) {		if($_POST["antibot"] == "" or $_SESSION["antibot"] != md5($_POST["antibot"])) {			$result .= "Введен не правильный Код проверки<br />";		}	}		#Поиск пользователя в БД	if(isset($lostpass) and ($user = doqueryassoc("SELECT * FROM `users` WHERE `login`='".$lostpass."' or `email`='".$lostpass."' LIMIT 1")) == false) {		$result = "Пользователь не найден<br />";	}		#Создание и отсылка нового пароля	if($result == "")	{		$newpass = generator(6,6);		$dbpass = md5($newpass.$plugin_config["passkey"]);				#Обновляем пароль		if(doquery("UPDATE `users` SET `passw`='".$dbpass."' WHERE `login`='".$user["login"]."' LIMIT 1"))		{				#Отправляем пароль на мыло			if($plugin_config["lost_mail"] == 1) {											$msgtext = $plugin_config["lost_text"];				$msgtext = str_replace("{login}", $user["login"], $msgtext);				$msgtext = str_replace("{passw}", $newpass, $msgtext);				$msgtext = str_replace("{title}", $cfg["title"], $msgtext);				$msgtext = str_replace("{email}", $user["email"], $msgtext);				$msgtext = str_replace("<br />", "\r\n", $msgtext);								if(bengine_mail($user["email"], $plugin_config["lost_title"], $msgtext)) {					$result = 1;				} else {					$result = "При отправке нового пароля возникла ошибка.<br />Обратитесь к администратору.<br />";				}			} else {				$result = 1;			}		} else {			$result = "При создании нового пароля возникла ошибка.<br />Обратитесь к администратору.<br />";		}			}		if(isset($_GET["die"])) {		die("".$result."");	}}#Быстрое редактирование личного кабинетаif(isset($_POST["plugin_register"]) and $_POST["plugin_register"] == "lk"){	if(($user = doqueryassoc("SELECT * FROM `users` WHERE id='".$_SESSION["id"]."' LIMIT 1")) != false)	{			if(($fields = dotable("users_fields")) != false) {			foreach($fields as $v) {				if(isset($_POST[$v["name"]])) {					$post[$v["name"]] = bengine_chars($_POST[$v["name"]]);				}			}			if(edit("users", $post, $_SESSION["id"])) {				foreach($fields as $k => $v) {					if(isset($post[$v["name"]])) {						$_SESSION[$v["name"]] = $post[$v["name"]];					}				}			}			$result = 1;		}			}	if(isset($_GET["die"])) {		die("".$result."");	}}#Изменение пароляif(isset($_POST["plugin_register"]) and $_POST["plugin_register"] == "pass"){	if(isset($_POST["old_passw"]) and $_POST["old_passw"] != "" and isset($_POST["new_passw"]) and $_POST["new_passw"] != "")	{		$oldpassw = md5($_POST["old_passw"]);		$newpassw = md5($_POST["new_passw"]);				if(isset($_POST["rep_passw"]) and md5($_POST["rep_passw"]) != $newpassw) {			$result .= "Введенные пароли не совпадают<br />";		}				$sql = doquery("SELECT * FROM `users` WHERE id='".$_SESSION["id"]."' and passw='".$oldpassw."' LIMIT 1");		if(dorows($sql) == 1) {			$post["passw"] = $newpassw;				} else {			$result .= "Старый пароль введен не верно<br />";		}	} else {		$result .= "Заполните все поля формы<br />";	}		if($result == "" and isset($post["passw"])) {		edit("users", $post, $_SESSION["id"]);	}}?>