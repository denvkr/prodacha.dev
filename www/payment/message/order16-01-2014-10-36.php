<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль OrderFunction Наличная опл. |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

$sql="select message,message_header  from ".$SysValue['base']['table_name48']." where id=".$_POST['order_metod'];
$result=mysql_query(@$sql);
$row = mysql_fetch_array(@$result);

$message=$row['message'];
$message_header=$row['message_header'];


// Определяем переменые nah~

	if ($_POST['order_metod']==25)
		$message.="<div class='bictext'>Ваш заказ успешно принят! Для оформления кредита, пожалуйста, заполните данную <a href='#' onClick='".$_POST['bic_code']."' >форму</a></div>";

$SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$message_header."</B></FONT><BR>".$message;

// Подключаем шаблон
$disp=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
$disp.="
<script language=\"JavaScript1.2\">
if(window.document.getElementById('num')){
window.document.getElementById('num').innerHTML='0';
window.document.getElementById('sum').innerHTML='0';
}
</script>";

// Очищаем корзину
unset($_SESSION['cart']);
?>