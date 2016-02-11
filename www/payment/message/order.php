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
		$message.="<div class='bictext'>Ваш заказ успешно принят! Для оформления кредита, пожалуйста, заполните данную форму".$_POST['bic_code']."</div><script type='text/javascript'>VVC.initButtons();</script>"; //style='visibility:hidden;'
		$SysValue['other']['mesageText']= "<font style='font-size:14px;color:red'><b>".$message_header."</b></font><br>".$message;

		$this->set('mesageText',$SysValue['other']['mesageText']);
		$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));

		$disp.='<script type="text/javascript">
		if(window.document.getElementById("num")){
		window.document.getElementById("num").innerHTML="0";
		window.document.getElementById("sum").innerHTML="0";
		}
		</script>';	
	} else {
		$this->set('mesageText', $this->message('<span style="font-size:18px">'.$this->lang('good_order_mesage_1').'</span><BR>','<span style="font-size:14px"><b> На Вашу почту: '.$_POST['mail'].' отправлено письмо с подтверждением заказа № '.$_POST['ouid'].'</b><BR><BR> В ближайшее время с Вами свяжется наш менеджер для уточнения деталей заказа.</span>'));
		$disp = ParseTemplateReturn($this->getValue('templates.order_forma_mesage'));
		$this->set('orderMesage', $disp);	
	}

// Очищаем корзину
unset($_SESSION['cart']);
?>