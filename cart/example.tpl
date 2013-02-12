<h2>{$page.title}</h2>
{$page.text}

{literal}
<script type="text/javascript">
$(document).ready(function(){

	// Просим данные
	function cart_start()
	{
		// price - общая стоимость всех продуктов, с учетом их количества
		$.post('/', {cart:'price', id: 1}, function(data) {
			$('span.price').text(data);
		});
		// count - количество продуктов в корзине
		$.post('/', {cart:'count', id: 1}, function(data) {
			$('span.count').text(data);
		});
	}
	
	cart_start();
			
	// добавление в корзину, либо увиличение на единицу
	$('.product_add').click(function() {
		var rel = $(this).attr('rel');
		$.post('/', {cart:'add', id: rel}, function(data) {
			$('span.product[rel='+rel+']').text('добавлен').css({color:'#ff0000','opacity':1}).animate({opacity:0},1000);
			cart_start();
		});
		return false;
	});
	// Уменьшение на 1
	$('.product_unadd').click(function() {
		var rel = $(this).attr('rel');
		$.post('/', {cart:'unadd', id: rel}, function(data) {
			$('span.product[rel='+rel+']').text('убавлен').css({color:'#ff0000','opacity':1}).animate({opacity:0},1000);
			cart_start();
		});
		return false;
	});
	// Удаление продукта из корзины
	$('.product_delete').click(function() {
		var rel = $(this).attr('rel');
		$.post('/', {cart:'delete', id: rel}, function(data) {
			$('span.product[rel='+rel+']').text('удален').css({color:'#ff0000','opacity':1}).animate({opacity:0},1000);
			cart_start();
		});
		return false;
	});
});
</script>
{/literal}

{foreach $content.product as $v}
	<a href="#" class="product_add" rel="{$v.id}">{$v.title}</a>
	[
		<a href="#" class="product_add" rel="{$v.id}">плюс</a> /
		<a href="#" class="product_unadd" rel="{$v.id}">минус</a> /
		<a href="#" class="product_delete" rel="{$v.id}">удалить</a>
	]
	<span class="product" rel="{$v.id}"></span>
	<br />
{/foreach}

<br />

Общая стоимость:
всего <span class="count">0</span>
товаров на сумму <span class="price">0</span> рублей

<br /><br />
{$cart = cart_pay()}
{mpr($cart)}