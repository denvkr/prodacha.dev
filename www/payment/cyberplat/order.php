<?php
/**
 * Обработчик оплаты заказа через CyberPlat
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


//параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

//описание покупки
$inv_desc  = "$inv_id";
$amount=$GLOBALS['SysValue']['other']['total'];

// вывод HTML страницы с кнопкой для оплаты
 $disp=('
	 <table width=420 align=center bgcolor="#ffffff">
       <tr>
	   <td align=center style="padding-top:30" class=style11>
	   <div align="center">
	   <img src="
	   images/paysyslogo/logo_sm.jpg" alt="" width="96" height="61" border="0">
	   </div>
	  <img src="images/line2.jpg" alt="" width="100" height="1" border="0">
<p><br></p>
<P class=l><BR><IMG height=23 alt=VISA src="images/paysyslogo/card_visa.gif" width=36 border=0> <IMG height=23 alt=EUROCARD hspace=0 src="images/paysyslogo/card_eurocard.gif" width=39 border=0> <IMG height=23 alt=MasterCard hspace=0 src="images/paysyslogo/card_mastercard.gif" width=42 border=0> <IMG height=23 alt="Diners Club" hspace=0 src="images/paysyslogo/card_dinersclub.gif" width=39 border=0> <IMG height=29 alt=JCB hspace=0 src="images/paysyslogo/card_jcblogo.gif" width=24 border=0> <IMG height=23 alt="American Express" hspace=0 src="images/paysyslogo/card_amex.gif" width=39 border=0> <IMG height=23 alt="Union Card" hspace=0 src="images/paysyslogo/card_union.gif" width=34 border=0> <IMG height=23 alt="STB Card" hspace=0 src="images/paysyslogo/card_stb.gif" width=37 border=0>
<p><br></p>
	  <form action="/cgi-bin/Shop/cybercrd.cgi" method="post" name="pay">
	  
<INPUT NAME="OrderID" TYPE=HIDDEN VALUE="$inv_desc">
<INPUT NAME="Currency" TYPE=HIDDEN VALUE="RUR">
<INPUT NAME="Amount" TYPE=HIDDEN VALUE="'.$amount.'">
<INPUT NAME="PaymentDetails" TYPE=HIDDEN VALUE="'.$cart_list[0].'">
<INPUT NAME="FirstName" TYPE=HIDDEN VALUE="'.$_POST['name_person'].'">
<INPUT NAME="LastName" TYPE=HIDDEN VALUE="'.$_POST['name_person'].'">
<INPUT NAME="Email" TYPE=HIDDEN VALUE="'.$_POST['mail'].'">
<INPUT NAME="Phone" TYPE=HIDDEN VALUE="'.$_POST['tel_code'].' '.$_POST['tel_name'].'">
<INPUT NAME="return_url" TYPE=HIDDEN VALUE="http://'.$_SERVER['SERVER_NAME'].'"/">
<table>
<tr>
	<td><img src="images/shop/icon-setup.gif" width="16" height="16" border="0"></td>
	<td align="center"><a href="javascript:history.back(1)"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width="20"></td>
	<td></td>
	<td><img src="images/shop/icon-client-new.gif" alt="" width="16" height="16" border="0" align="left">
	<a href="javascript:pay.submit();">Продолжить оплату</a></td>
</tr>
</table>
</form>
	   </td>
       </tr>
       </table>
	   ');

?>