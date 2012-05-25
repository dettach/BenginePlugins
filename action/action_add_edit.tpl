<?php include_once(ROOT_DIR."/system/template/tinymce_br.tpl"); ?>

<form action="#" method="post">
<fieldset>
	<legend><?php if($nodes[2] == "add"){echo 'Создание';}else{echo 'Редактирование';}?> статического блока:</legend>
	
		<label>Заголовок:</label> <input type="text" name="title" value="<?php if(isset($content["title"])) {echo $content["title"];} ?>" maxlength="200" /><br />
		
		<label>Ссылка на страницу:</label>
		<select name="page">
			<option value="0">без ссылки</option>
		<?php
			$cache_pages = cacheGet("pages");
			if(!empty($cache_pages) and count($cache_pages) > 0) {
				foreach($cache_pages as $v)
				{
					if(isset($content["page"]) and $content["page"] > 0 and $v["id"] == $content["page"]) {
						echo '<option value="'.$v["id"].'" selected="selected">'.$v["title"].'</option>';
					} else {
						echo '<option value="'.$v["id"].'">'.$v["title"].'</option>';
					}
				}
			}
		?>
		</select>
		<br />
		
		<label>Изображение:</label> <input type="text" name="image" id="image" value="<?php if(isset($content["image"])) {echo $content["image"];} ?>" maxlength="200" />
		<a href="#" onclick="elFinderBrowser('image', '<?php if(isset($content["image"])) {echo $content["image"];} ?>', 'image', window);"><img src="/system/template/img/view.png" class="addImg"></a>
		<br />
		
		<label>Файл:</label> <input type="text" name="file" id="file" value="<?php if(isset($content["file"])) {echo $content["file"];} ?>" maxlength="200" />
		<a href="#" onclick="elFinderBrowser('file', '<?php if(isset($content["file"])) {echo $content["file"];} ?>', 'file', window);"><img src="/system/template/img/view.png" class="addImg"></a>
		<br />
		
		<label>Содержание: <br /><a class="btn" href="javascript:;" onclick="tinymce.execCommand('mceToggleEditor',false,'text');"><span>Отключить TinyMCE</span></a></label>
		<textarea name="text" style="width: 80%; height: 320px;"><?php if(isset($content["text"])) {echo $content["text"];} ?></textarea>
		<br />
		
	</fieldset>
	
	<input type="submit" name="submit" value="<?php if($nodes[2] == "add"){echo 'Создать';}else{echo 'Редактировать';}?> блок">
	<a href="/admin/action/" class="button">Список блоков</a>
	
</form>