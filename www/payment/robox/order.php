<?php
/**
 * Обработчик оплаты заказа через Robox
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

// регистрационная информация
$mrh_login = $SysValue['roboxchange']['mrh_login'];    //логин
$mrh_pass1 = $SysValue['roboxchange']['mrh_pass1'];    // пароль1

//параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

//описание покупки
$inv_desc  = "PHPShopPaymentService";
$out_summ  = $GLOBALS['SysValue']['other']['total']*$SysValue['roboxchange']['mrh_kurs']; //сумма покупки

// формирование подписи
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

// вывод HTML страницы с кнопкой для оплаты
$disp= "
<div align=\"center\">
<img src=\"images/bank/robot.gif\"  width=\"350\" height=\"72\" border=\"0\">
<p>
<b>ROBOXchange</b> работает с основными платежными системами России и всего мира.
Наши услуги мы разделяем на обменные и платежные. С помощью сервиса ROBOXchange можно осуществить обмен WM - обмен WMZ, обмен WMR, обмен других титульных знаков WebMoney , обмен e-gold, Яндекс Деньги, Money Mail, E-Bullion, Pecunix и других электронных валют. Все обменные операции осуществляются в автоматическом режиме мгновенно и требуют минимального участия пользователя. Используя ROBOXchange, Вы можете быть уверены, что Ваши деньги надежно защищены. 
</p>
<DIV class=buttons>
<A href=\"http://webmoney.ru/\" target=_blank><IMG alt=\"\" src=\"images/bank/ban_WebMoney.gif\" border=0></A> <A href=\"http://money.yandex.ru/\" target=_blank><IMG alt=\"\" src=\"images/bank/ban_Yandex.gif\" border=0></A> <A href=\"http://rupay.ru/\" target=_blank><IMG alt=\"\" src=\"images/bank/ban_RuPay.gif\" border=0></A> <A href=\"https://www.moneymail.ru/\" target=_blank><IMG alt=\"\" src=\"images/bank/ban_MoneyMail.gif\" border=0></A> <BR><A href=\"http://www.e-gold.com/\" target=_blank><IMG alt=\"\" src=\"images/bank/ban_Egold.gif\" border=0></A> <A href=\"http://www.ukrmoney.com/\" target=_blank><IMG  src=\"images/bank/ban_UkrMoney.gif\" border=0></A> <A href=\"http://www.emoney.md/\" target=_blank><IMG src=\"images/bank/ban_EmoneyMD.gif\" border=0></A> </DIV>
 <p><br></p>



<form action='https://www.roboxchange.com/ssl/calc.asp' method=POST name=\"pay\">
       <input type=hidden name=MrchLogin  value=$mrh_login>
       <input type=hidden name=OutSum  value=$out_summ>
       <input type=hidden name=InvId  value=$inv_id>
       <input type=hidden name=Desc  value=$inv_desc>
           <input type=hidden name=SignatureValue value=$crc>
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