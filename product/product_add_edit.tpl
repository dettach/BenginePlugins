<?php include_once(ROOT_DIR."/system/template/tinymce.tpl"); ?>

<script type="text/javascript">
$(function(){
	$("input[type=submit]").click(function(){	
		var title = $("input[name=title]").val();
		if( title == '' ) {
			alert('Заголовок не должен быть пустым');
			return false;
		}
	});
});
</script>

<form id="edit" method="post">
	<fieldset>
	
		<legend><?php if($nodes[2] == "add") {echo 'Добавление';} else {echo 'Редактирование';} ?> данных в "<?php echo $plugin_config["title"]; ?>"</legend>
		
		<label>Наименование/Заголовок:</label>
		<input type="text" name="title" value="<?php if(isset($content["title"])) { echo $content["title"]; } ?>" maxlength="250" />
		<br />
		
		<label class="seo">Описание:</label>
		<input type="text" name="description" class="seo" value="<?php if(isset($content["description"])) { echo $content["description"]; } ?>" maxlength="250" />
		<br class="seo" />
		
		<label class="seo">Ключевые слова:</label>
		<input type="text" name="keywords" class="seo" value="<?php if(isset($content["keywords"])) { echo $content["keywords"]; } ?>" maxlength="250" />
		<br class="seo" />
		
		<label>Категории:</label>
		<select name="category[]" style="height: 80px;" multiple>
		<?php
			$category = cacheGet("category");
			foreach($category as $v) {
				if(isset($princat[$v["id"]]) and $princat[$v["id"]] == $v["id"]) {
					echo '<option value="'.$v["id"].'" selected>'.$v["title"].'</option>';
				} else {
					echo '<option value="'.$v["id"].'">'.$v["title"].'</option>';
				}
			}
		?>
		</select>
		<br />
		<label></label>
		<span>Категории выбираются через кнопку Ctrl + левая кнопка мыши</span>
		<br /><br />
		
		<?php
			# Смотрим  поля, которые были заданы в конфигурации
			if(!empty($plugin_column["product"]))
			{
				foreach($plugin_column["product"] as $v)
				{
					# Смотрим данные БД
					if(isset($content[$v["name"]])) {
						$column_content = $content[$v["name"]];
					} else {
						$column_content = "";
					}
					# Текст
					if($v["type"] == "text" or $v["type"] == "longtext") {
						echo '<label>'.$v["title"].': <br /><a class="btn" href="javascript:;" onclick="tinymce.execCommand(\'mceToggleEditor\',false,\''.$v["name"].'\');"><span>Отключить TinyMCE</span></a></label> <div style="float: left; width: 80%;"><textarea name="'.$v["name"].'" style="width: 100%; height: 300px;">'.$column_content.'</textarea></div><br /><br />';
					}
					# Дата и время
					elseif($v["type"] == "datetime" or $v["type"] == "date") {
						if(empty($column_content)) {
							$column_content = DATETIME;
						}
						echo '<label>'.$v["title"].':</label> <input type="text" name="'.$v["name"].'" id="'.$v["name"].'" value="'.$column_content.'" maxlength="250" />';
					}
					# Флажок
					elseif($v["type"] == "checkbox") {
						$checked = '';
						if($column_content != "") {
							if($column_content == 1) {
								$checked = 'checked="checked"';
							}
						} else {
							if(isset($v["default"]) and $v["default"] == 1) {
								$checked = 'checked="checked"';
							}
						}
						echo '<input type="hidden" name="'.$v["name"].'" value="0" />';
						echo '<label>'.$v["title"].':</label> <input type="checkbox" name="'.$v["name"].'" id="'.$v["name"].'" value="1" '.$checked.' />';
					}
					# Строка
					else {
						if($v["name"] != "category") {
							if(isset($v["defaultvar"]) and $v["defaultvar"] != "" and $column_content == "") {
								$column_content = $v["defaultvar"];
							}
							echo '<label>'.$v["title"].':</label> <input type="text" name="'.$v["name"].'" id="'.$v["name"].'" value="'.$column_content.'" maxlength="250" />';
							if(isset($v["filebrowser"]) and $v["filebrowser"] == 1) {
								echo '<a href="#" onclick="elFinderBrowser(\''.$v["name"].'\', \''.$column_content.'\', \'images\', window);"><img src="/system/template/img/view.png" class="addImg"></a>';
							}
							echo '<br />';
						}
					}
				}
			}
		?>
		<input type="hidden" name="header" value="<?php echo $plugin_config["header"]; ?>">
		<input type="hidden" name="body" value="<?php echo $plugin_config["body"]; ?>">
		<input type="hidden" name="footer" value="<?php echo $plugin_config["footer"]; ?>">
		<input type="hidden" name="datetime" value="<?php echo DATETIME; ?>">	
	</fieldset>
	
	<input type="submit" name="submit" class="button" value="Сохранить данные">
	<a href="/admin/<?php echo $pl; ?>/product/<?php echo $nodes[3]; ?>/" class="button">Вернуться без сохранения</a><br />
</form>