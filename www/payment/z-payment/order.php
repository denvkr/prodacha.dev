<?php
/**
 * Обработчик оплаты заказа через Z-Payment
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


// регистрационная информация
$LMI_PAYEE_PURSE = $SysValue['z-payment']['LMI_PAYEE_PURSE'];    //кошелек
$LMI_ID = $SysValue['z-payment']['LMI_ID']; 
//$wmid = $SysValue['webmoney']['wmid'];    //аттестат

//параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

//описание покупки
$inv_desc  = "Оплата заказа №$inv_id";
$out_summ  = $GLOBALS['SysValue']['other']['total']*$SysValue['z-payment']['kurs']; //сумма покупки



// вывод HTML страницы с кнопкой для оплаты
$disp= "
<div align=\"center\">

<!-- Аттестат Z-PAYMENT --> 
<a href=\"http://www.z-payment.ru/info.php?zp=$LMI_ID\" target=_blank><IMG SRC=\"images/bank/attestat.gif\" alt=\"Аттестован системой Z-PAYMENT \" border=\"0\" align=\"left\" hspace=\"5\" vspace=\"5\"></a>
<!-- end Аттестат Z-PAYMENT --> 
<strong>Z-PAYMENT</strong> - это универсальная процессинговая система, интегрирующая множество видов оплаты в единый унифицированный алгоритм. Мы предлагаем нашим клиентам гибкий и надежный инструмент для <strong>on-line расчетов</strong>, приема платежей на сайтах, оплаты различных услуг и товаров. 
 <p><br></p>
      <form id=pay name=pay method=\"POST\" action=\"https://z-payment.ru/merchant.php\" name=\"pay\">
    <input type=hidden name=LMI_PAYMENT_AMOUNT value=\"$out_summ\">
	<input type=hidden name=LMI_PAYMENT_DESC value=\"$inv_desc\">
	<input type=hidden name=LMI_PAYMENT_NO value=\"$inv_id\">
	<input type=hidden name=LMI_PAYEE_PURSE value=\"$LMI_PAYEE_PURSE\">
    <input type=hidden name=CLIENT_MAIL value=\"".$_POST['mail']."\">
	  
	  <table>
<tr><td><img src=\"images/shop/icon-setup.gif\" width=\"16\" height=\"16\" border=\"0\"></td>
	<td align=\"center\"><a href=\"javascript:history.back(1)\"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width=\"20\"></td>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:pay.submit();\">Оплатить через платежную систему</a></td>
</tr>
</table>
      </form>
</div>";
?>