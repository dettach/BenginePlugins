<form action="#" method="post">
	
	<fieldset>
		<legend>Список статических блоков:</legend>
		<table cellpadding="0" cellspacing="0" class="editor">
			<tr class="header">
				<td>Заголовок блока</td>
				<td class="center" width="220px">Быстрая ссылка</td>
				<td class="center" width="120px">Управление</td>
			</tr>
			<?php
			if(count($content) > 0)
			{
				foreach($content as $k => $v)
				{
					echo '
					<tr>
						<td>
							<a href="/admin/action/edit/'.$v["id"].'/">'.$v["title"].'</a>
						</td>
						<td class="center">{$cache_action['.$k.'].text}</td>
						<td class="control">
							<a href="/admin/action/delete/'.$v["id"].'/"><img src="/system/template/img/delete.png" /></a>
							<a href="/admin/action/edit/'.$v["id"].'/"><img src="/system/template/img/reply.png" /></a>
						</td>
					</tr>
					';
				}
			}
			?>
		</table>
	</fieldset>
	<a href="/admin/action/add/" class="button">Создать новый блок</a>
</form>