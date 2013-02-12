<h2>{$page.title}</h2>
{$page.text}

{literal}
<script type="text/javascript">
$(document).ready(function(){
	// добавление в корзину
	$('a.product_add').click(function() {
		var id = $(this).attr('rel');
		$.post('/', {  cart:'add', id:id }, function(data) {
			$('span.product[rel='+id+']').text('добавлен').css({color:'#ff0000','opacity':1}).animate({opacity:0},1000);
			return false;
		});
	});
	// Удаление из корзины
	$('a.product_unadd').click(function() {
		var id = $(this).attr('rel');
		$.post('/', {  cart:'unadd', id:id }, function(data) {
			$('span.product[rel='+id+']').text('уменьшено').css({color:'#ff0000','opacity':1}).animate({opacity:0},1000);
			return false;
		});
	});
	// Подсчет общей стоимости
	$('a.price').click(function() {
		var id = 1;
		$.post('/', {  cart:'price', id:id }, function(data) {
			$('span.price').text(data);
			$.post('/',{cart:'count',id:id},function(dt){
				$('span.count').text(dt);
			});
			return false;
		});
	});	
});
</script>
{/literal}

{foreach $content.product as $v}
	<a href="#" class="product_add" rel="{$v.id}">{$v.title}</a>
	(<a href="#" class="product_unadd" rel="{$v.id}">-</a>)
	<span class="product" rel="{$v.id}"></span>
	<br />
{/foreach}

<a href="#" class="price">Общая стоимость:</a> всего <span class="count">0</span> товаров на сумму <span class="price">0</span> рублей