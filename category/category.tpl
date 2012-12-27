<?php
function structure($parent, $level = 1)
{
	global $plugin;
	$structure = array();
	if(($sql_structure = doquery("SELECT `id`,`parent`,`child`,`menu`,`order`,`title`,`showchild` FROM `category` WHERE `parent`=".$parent." ORDER BY `order`")) != false)
	{
		if(dorows($sql_structure) > 0) {
			$structure = doarray($sql_structure);
			foreach($structure as $k => $v)
			{
				$padding_level = (40*$level)+13;
				echo '
				<tr>
					<td style="padding-left: '.$padding_level.'px">
						<a href="/admin/category/up/'.$v["id"].'/" title="Переместить вверх"><img src="/system/template/img/up.png" /></a>
						<a href="/admin/category/dn/'.$v["id"].'/" title="Переместить вниз"><img src="/system/template/img/down.png" /></a>
						<img src="/system/template/img/menu_'.$v["menu"].'.png" class="click_menu" rel="'.$v["id"].'" title="Включить" />
					';
					
					if($v["child"] > 0) {
						if($v["showchild"] == 1) {
							echo '<a href="/admin/category/showchild/'.$v["id"].'/0/" title="Скрыть внутренние страницы"><img src="/system/template/img/minimize.png" /></a>';
						} else {
							echo '<a href="/admin/category/showchild/'.$v["id"].'/1/" title="Просмотр внутренних страниц"><img src="/system/template/img/maximize.png" /></a>';
						}
					}
					
					echo '
						<a href="/admin/category/add/'.$v["id"].'/"><img src="/system/template/img/addsmall.png" /></a>
						<a href="/admin/category/edit/'.$v["id"].'/" title="Редактировать данные">'.$v["title"].'</a>
					</td>
					<td class="control">
						<a href="/'.$v["id"].'/" target="_blank"><img src="/system/template/img/view.png" /></a>';
						if(isset($v["child"]) and $v["child"] > 0) {
							echo '<img src="/system/template/img/delete.png" style="opacity:0.5; cursor: default;" />';
						} else {
							echo '<a href="/admin/category/delete/'.$v["id"].'/"><img src="/system/template/img/delete.png" /></a>';
						}
					echo '<a href="/admin/category/edit/'.$v["id"].'/"><img src="/system/template/img/reply.png" /></a>
					</td>
				</tr>
				';
				if(isset($v["child"]) and $v["child"] > 0) {
					if($v["showchild"] == 1) {
						structure($v["id"],$level+1);
					}
				}
			}
		}
	}
}
?>

<script type="text/javascript">
$(document).ready(function(){
	$(".click_menu").click(function(){
		var rel = $(this).attr("rel");
		$.post("/admin/category/menu/"+rel+"/",function(data) {
			$(".click_menu[rel="+rel+"]").attr("src","/system/template/img/menu_"+data+".png");
		});
		return false;
	});
});
</script>

<form action="#" method="post">
	<fieldset>
		<legend>Категории:</legend>
		<table cellpadding="0" cellspacing="0" class="editor">
			<tr class="header">
				<td>Название категории</td>
				<td class="center" width="170">Управление</td>
			</tr>
			<?php
			if(count($content) > 0)
			{
				foreach($content as $k => $v)
				{
					echo '
					<tr>
						<td>
							<a href="/admin/category/up/'.$v["id"].'/" title="Переместить вверх"><img src="/system/template/img/up.png" /></a>
							<a href="/admin/category/dn/'.$v["id"].'/" title="Переместить вниз"><img src="/system/template/img/down.png" /></a>
							<img src="/system/template/img/menu_'.$v["menu"].'.png" class="click_menu" rel="'.$v["id"].'" title="Включить" />
					';
					
					if($v["child"] > 0) {
						if($v["showchild"] == 1) {
							echo '<a href="/admin/category/showchild/'.$v["id"].'/0/" title="Скрыть внутренние страницы"><img src="/system/template/img/minimize.png" /></a>';
						} else {
							echo '<a href="/admin/category/showchild/'.$v["id"].'/1/" title="Просмотр внутренних страниц"><img src="/system/template/img/maximize.png" /></a>';
						}
					}
					
					echo '
							<a href="/admin/category/add/'.$v["id"].'/"><img src="/system/template/img/addsmall.png" /></a>
							<a href="/admin/category/edit/'.$v["id"].'/" title="Редактировать данные">'.$v["title"].'</a>
						</td>
						<td class="control">
							<a href="/'.$v["id"].'/" target="_blank"><img src="/system/template/img/view.png" /></a>';
							if(isset($v["child"]) and $v["child"] > 0) {
								echo '<img src="/system/template/img/delete.png" style="opacity:0.5; cursor: default;" />';
							} else {
								echo '<a href="/admin/category/delete/'.$v["id"].'/"><img src="/system/template/img/delete.png" /></a>';
							}
						echo '<a href="/admin/category/edit/'.$v["id"].'/"><img src="/system/template/img/reply.png" /></a>
						</td>
					</tr>
					';
					if(isset($v["child"]) and $v["child"] > 0) {
						if($v["showchild"] == 1) {
							structure($v["id"]);
						}
					}
				}
			}
			?>
		</table>
	</fieldset>
	<a href="/admin/category/add/" class="button">Добавить новую категорию</a>
</form>