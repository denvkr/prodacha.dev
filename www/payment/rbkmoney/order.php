<?php
/**
 * Обработчик оплаты заказа через RBKMoney
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if (empty($GLOBALS['SysValue'])) exit(header("Location: /"));

// регистрационная информация
$MerchantId = $SysValue['rbk']['eshopId'];
$serviceName = $SysValue['rbk']['serviceName'];
$Currency = $SysValue['rbk']['Currency'];

$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];     //номер счета

$OrderId = $inv_id;
$Amount = $GLOBALS['SysValue']['other']['total'];

// обратный адрес
$ReturnUrl = urlencode("http://" . $_SERVER['SERVER_NAME'] . "/success/?payment=rbkmoney&inv_id=".$inv_id);
$FailUrl = urlencode("http://" . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . "/fail/");

// вывод HTML страницы с кнопкой для оплаты
$disp = "
<div align=\"center\">

 <p><br></p>
 
 <img src=\"images/bank/visa.gif\" border=\"0\" hspace=5>
  <img src=\"images/bank/mastercard.gif\" border=\"0\" hspace=5>
  <p><br></p>

<p>Вы можете оплатить свои заказы в режиме он-лайн кредитными картами и другими способами оплаты. Обработка платежей осуществляется процессинговым центром <b>RBK money</b>. </p>

                <form name=\"PaymentForm\" action=\"https://rbkmoney.ru/acceptpurchase.aspx\" method=post>
                   <input type=\"hidden\" name=\"eshopId\" value=\"$MerchantId\">
                   <input type=\"hidden\" name=\"user_email\" value=\"" . $_POST['mail'] . "\">
                   <input type=\"hidden\" name=\"orderId\" value=\"$OrderId\">
                   <input type=\"hidden\" name=\"serviceName\" value=\"$serviceName\">
                   <input type=\"hidden\" name=\"recipientAmount\" value=\"$Amount\">
                   <input type=\"hidden\" name=\"recipientCurrency\" value=\"$Currency\">
                   <input type=\"hidden\" name=\"version\" value=\"2\">
                   <input type=\"hidden\" name=\"successUrl\" value=\"$ReturnUrl\">
                   <input type=\"hidden\" name=\"failUrl\" value=\"$FailUrl\">
	<table>
<tr><td><img src=\"images/shop/icon-setup.gif\" width=\"16\" height=\"16\" border=\"0\"></td>
	<td align=\"center\"><a href=\"javascript:history.back(1)\"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width=\"20\"></td>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:PaymentForm.submit();\">Оплатить через платежную систему RBK money</a></td>
</tr>
</table>
</form>

</div>";
?>