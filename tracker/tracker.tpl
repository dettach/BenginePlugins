{if !isset($cache_users)}{$cache_users = cacheAdd("users")}{/if}
{$cache_users = cacheGet("users")}

{if isset($nodes[1]) and is_numeric($nodes[1]) > 0}

	{if isset($nodes[2]) and is_numeric($nodes[2]) > 0}
	
		{$v = $content.ticket}
		<h2><a href="/{$page.engname}/">{$page.title}</a> → <a href="/{$page.engname}/{$content.project.id}/">{$content.project.title}</a> → {$v.title}</h2>
		<div class="row">
			<div class="span12">
				<table class="table table-bordered">
					<tr>
						<th style="width:20%;">Дата создания:</th>
						<th style="width:30%;">Статус:</th>
						<th style="width:25%;">Создан:</th>
						<th style="width:25%;">Назначен:</th>
					</tr>
					<tr>
						<td>{$v.datetime|date_format:"%d.%m.%Y в %H:%M"}</td>
						<td>{if $v.status == 0}открыт{elseif $v.status == 1}в работе{elseif $v.status == 2}закрыт{else}не известен{/if}</td>
						<td><a href="/users/{$v.user_in}">{$v.login_in}</a></td>
						<td>{if $v.user_ou == 0}-{else}<a href="/users/{$v.user_ou}">{$v.login_ou}</a>{/if}</td>
					</tr>
					<tr>
						<th>Описание:</th>
						<td colspan="3">{$v.text|decode}</td>
					</tr>
				</table>
			</div>
		</div>
		
		{if isset($content.comments) and count($content.comments) > 0}
			<h4>Комментарии</h4>
			<table class="table table-striped table-bordered">
				{foreach $content.comments as $v}
				<tr>
					<td style="width:20%;">
						<a href="/users/{$v.user}/">{$v.login}</a>
						<br />
						{$v.datetime|date_format:"%d.%m.%Y в %H:%M"}
					</td>
					<td>{$v.text|decode}</td>
				</tr>
				{/foreach}
			</table>
		{/if}
	
		{if isset($smarty.session.id)}
			<form action="#" method="post">
				<legend>Добавить комментарий</legend>
				<textarea rows="3" class="input-xxlarge" name="text"></textarea>
				<label></label> <input type="submit" name="add_comment" class="btn" value="Добавить комментарий" />
			</form>
		{/if}
		
	{else}
	
		<h2><a href="/{$page.engname}/">{$page.title}</a> → {$content.project.title}</h2>
		<table class="table table-striped">
			<tr>
				<th style="width:7%;">Тикет</th>
				<th>Заголовок</th>
				<th style="width:10%;">Статус</th>
				<th style="width:15%;">Дата создания</th>
				<th style="width:10%;">Создан</th>
				<th style="width:10%;">Назначен</th>
				<th style="width:10%;">Отзывов</th>
			</tr>
			{if isset($content.tickets)}
				{foreach $content.tickets as $v}
				<tr>
					<td><a href="/{$page.engname}/{$content.project.id}/{$v.id}/" {if $v.status == 2}style="text-decoration:line-through;"{/if}>#{$v.id}</a></td>
					<td><a href="/{$page.engname}/{$content.project.id}/{$v.id}/" {if $v.status == 2}style="text-decoration:line-through;"{/if}>{$v.title}</a></td>
					<td>{if $v.status == 0}открыт{elseif $v.status == 1}в работе{elseif $v.status == 2}закрыт{else}не известен{/if}</td>
					<td>{$v.datetime|date_format:"%d.%m.%Y в %H:%M"}</td>
					<td><a href="/users/{$v.user_in}/">{$v.login_in}</a></td>
					<td>{if $v.user_ou == 0}-{else}<a href="/users/{$v.user_ou}/">{$v.login_ou}</a>{/if}</td>
					<td>{$v.comments}</td>
				</tr>
				{/foreach}
			{/if}
		</table>
	
		{if isset($smarty.session.id)}
			<form action="#" method="post">
				<legend>Добавить тикет</legend>
				<label>Заголовок</label> <input type="text" name="title">
				<label>Описание</label> <textarea rows="3" class="input-xxlarge" name="text"></textarea>
				<label>Назначить</label> 
				<select name="user_ou">
					<option value="0">-</option>
					{foreach $cache_users as $v}
						<option value="{$v.id}">{$v.login}</option>
					{/foreach}
				</select>
				<label></label> <input type="submit" name="add_ticket" class="btn" value="Добавить тикет" />
			</form>
		{/if}
		
	{/if}
	
{else}

	<h2>{$page.title}</h2>
	<table class="table table-striped">
		<tr>
			<th style="width:25%;">Проект</th>
			<th style="width:65%;">Описание</th>
			<th style="width:10%;">Тикетов</th>
		</tr>
		{foreach $content as $v}
		<tr>
			<td><a href="/{$page.engname}/{$v.id}/">{$v.title}</a></td>
			<td><a href="/{$page.engname}/{$v.id}/">{$v.text}</a></td>
			<td>{$v.count}</td>
		</tr>
		{/foreach}
	</table>
	
	{if isset($smarty.session.admin) and $smarty.session.admin == 1}
		<form action="#" method="post">
			<legend>Добавить проект в багтрекер</legend>
			<label>Название проекта</label> <input type="text" name="title">
			<label>Описание проекта</label> <textarea rows="3" class="input-xxlarge" name="text"></textarea>
			<label></label> <input type="submit" name="add_project" class="btn" value="Добавить проект" />
		</form>
	{/if}
	
{/if}