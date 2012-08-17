<script type="text/javascript">
$(document).ready(function(){
	$(".click_menu").click(function(){
		var rel = $(this).attr("rel");
		$.post("/admin/<?php echo $pl; ?>/menu/"+rel+"/",function(data) {
			$(".click_menu[rel="+rel+"]").attr("src","/system/template/img/menu_"+data+".png");
		});
		return false;
	});
});
</script>

<fieldset>
	<legend>Управление данными "<?php echo $plugin_config["title"]; ?>":</legend>	
	<table cellpadding="0" cellspacing="0" class="editor">
		<tr class="header">
			<td>Заголовок</td>
			<td width="150px">Дата и время</td>
			<?php
				if(isset($plugin_column[$pl]) and is_array($plugin_column[$pl])) {
					foreach($plugin_column[$pl] as $v) {
						if(!empty($v["visible"]) and $v["visible"] == 1) {
							echo '<td>'.$v["title"].'</td>';
						}
					}
				}
			?>
			<td class="center" width="100px">Управление</td>
		</tr>
		<?php
		if(isset($content) and count($content) > 0)
		{
			foreach($content as $v)
			{
				echo '
				<tr>
					<td>
						<a href="/admin/'.$pl.'/dn/'.$v["id"].'/" title="Переместить вверх"><img src="/system/template/img/up.png" /></a>
						<a href="/admin/'.$pl.'/up/'.$v["id"].'/" title="Переместить вниз"><img src="/system/template/img/down.png" /></a>
						<img src="/system/template/img/menu_'.$v["menu"].'.png" class="click_menu" rel="'.$v["id"].'" title="Включить" />
						<a href="/admin/'.$pl.'/elements/1/edit/'.$v["id"].'/" title="Редактировать данные">'.$v["title"].'</a>
					</td>
					<td>'.$v["datetime"].'</td>';
				if(isset($plugin_column[$pl]) and is_array($plugin_column[$pl])) {
					foreach($plugin_column[$pl] as $l) {
						if(!empty($l["visible"]) and $l["visible"] == 1) {
							echo '<td>'.$v[$l["name"]].'</td>';
						}
					}
				}
				echo '
					<td class="control">
						<a href="/admin/'.$pl.'/delete/'.$v["id"].'/" onclick="return confirm(\'Вы уверенны, что хотите удалить: '.$v["title"].'?\'); return false;" title="Удалить данные"><img src="/system/template/img/delete.png" /></a>
						<a href="/admin/'.$pl.'/edit/'.$v["id"].'/"><img src="/system/template/img/reply.png" title="Редактировать данные" /></a>
					</td>
				</tr>
				';
			}
		}
		?>
	</table>		
</fieldset>
<div class="nav">
	<?php		
	if(isset($nav["list"]) and count($nav["list"]) > 0)
	{
		$pagin = 2;
		$paginlist = $pagin * 2; #4
		$countlist =  count($nav["list"]); #10
		$start = 1;
		$finish = $countlist; #10
		$active_start = '';
		$active_finish = '';
			
		if(isset($countlist) and $countlist > 1)
		{
			# Несуществующую страницу
			if(isset($p) and $p > $countlist) {
				$p = 1;
			}
			if($countlist > $paginlist)
			{
				# х [х][х][х][х]
				if(!isset($p) or $p == 1) {
					$finish = $paginlist + 1;
				}
				# [х][х][х][х] x
				elseif($p == $countlist and $p > $paginlist) {
					$start = $finish - $paginlist;
					$active_start = '<a href="?p=1" class="nav">1</a>... ';
				}
				# [х] х [х][х][х]
				elseif($p > 1 and $p <= $paginlist) {
					$finish = $paginlist + 1;
					$active_finish = '... <a href="?p='.$countlist.'" class="nav">'.$countlist.'</a>';
				}
				# [х][х] х [х][х]
				elseif($p > 1 and $p > $paginlist and $p <= $countlist - $paginlist) {
					$start = $p-$pagin;
					$finish = $p + $pagin;
					$active_finish = '... <a href="?p='.$countlist.'" class="nav">'.$countlist.'</a>';
					$active_start = '<a href="?p=1" class="nav">1</a>... ';
				}
				# [х][х][х] х [х]
				else {
					$start = $countlist - $paginlist;
					$active_start = '<a href="?p=1" class="nav">1</a>... ';
					$result = 6;
				}
			}
			echo $active_start;
			for($i = $start; $i <= $finish; $i++) {
				if(isset($p) and $p == $i) {
					echo '<span class="nav">'.$i.'</span>';
				} else {
					echo '<a href="?p='.$i.'" class="nav">'.$i.'</a>';
				}
			}
			echo $active_finish;
		}
	}
	?>
</div>
<a href="/admin/<?php echo $pl; ?>/elements/<?php echo $nodes[3]; ?>/add/" class="button">Добавить данные в каталог</a>
<a href="/admin/<?php echo $pl; ?>/" class="button">Вернуться в список каталогов</a>