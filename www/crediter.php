<!DOCTYPE html>
<html class="iframe_body">
<head>
	
	<link href="/phpshop/templates/prodacha/style.css" type="text/css" rel="stylesheet">
	
	<link rel="stylesheet" href="/phpshop/templates/prodacha/css/style.css" type="text/css">
	
	
	<link rel="stylesheet" type="text/css" href="/java/highslide/highslide.css"/>
	
	<link href="/phpshop/templates/prodacha/javascript/fancybox/jquery.fancybox.css" rel="stylesheet">
</head>
<body>

<div class='bic_box'>
			<div class="header2">Товар успешно добавлен в корзину</div>
			<p> Для оформления покупки в кредит сумма заказа должна составлять не менее 10 000 рублей.	</p>
			<p class="bold">Оформление кредита возможно из корзины.</p>
	</div>
<?

session_start();

$_SESSION['buyincredit'].=$_GET['id'].";";
?>

</body>
</html>