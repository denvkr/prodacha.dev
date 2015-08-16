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

	if ($_POST['order_metod']==25) {
		$message.="<div class='bictext' style='visibility:hidden;'>Ваш заказ успешно принят! Для оформления кредита, пожалуйста, заполните данную <a style='visibility:hidden;' id='credit_forma' href='#' onClick='".$_POST['bic_code']."' >форму</a></div>"; //$_POST['bic_code']
		//$message.="<span style='font-size:18px;visibility:visible;'>".$this->lang('good_order_mesage_1')."</span><BR><span style='font-size:14px;visibility:visible;'><b> На Вашу почту: ".$_POST['mail']." отправлено письмо с подтверждением заказа № ".$_POST['ouid']."</b><BR><BR> В ближайшее время с Вами свяжется наш менеджер для уточнения деталей заказа.</span>";
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
	} else {
		$this->set('mesageText', $this->message('<span style="font-size:18px">'.$this->lang('good_order_mesage_1').'</span><BR>','<span style="font-size:14px"><b> На Вашу почту: '.$_POST['mail'].' отправлено письмо с подтверждением заказа № '.$_POST['ouid'].'</b><BR><BR> В ближайшее время с Вами свяжется наш менеджер для уточнения деталей заказа.</span>'));
		$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
		$this->set('orderMesage', $disp);	
	}

// Очищаем корзину
unset($_SESSION['cart']);
?>