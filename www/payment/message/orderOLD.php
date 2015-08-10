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


// Определяем переменые
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

// Учищаем корзину
unset($_SESSION['cart']);
?>