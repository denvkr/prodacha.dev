<?php
/**
 * Обработчик оплаты заказа через Activepay
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


// Регистрационная информация
$secret_key=$SysValue['activepay']['secret_key'];
$merchant_contract=$SysValue['activepay']['merchant_contract'];

$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

$OrderId=$inv_id;
$amount=$GLOBALS['SysValue']['other']['total'];
$currency="RUB";

// Библиотека с функциями
include_once("payment/activepay/lib.php");


// обратный адрес
$ReturnUrl="http://".$_SERVER['SERVER_NAME']."/success/";
$FailedUrl="http://".$_SERVER['SERVER_NAME']."/fail/";


// Подпись
$SecurityKey=sign("GET", 'activepay.ru', "/merchant_pages/create/", $secret_key,array(
        "merchant_contract" => $merchant_contract,
        "merchant_data" => $Order."-".$amount,
        "amount" => $amount,
        "currency" => $currency,
        "merchant_description" => $OrderId,
        "redirect_url_failed"=>$FailedUrl,
        "redirect_url_ok"=>$ReturnUrl,
        "commission_included"=>"false"
));


// вывод HTML страницы с кнопкой для оплаты
$disp= "
<div align=\"center\">

 <p><br></p>
 
 <img src=\"images/bank/visa.gif\" border=\"0\" hspace=5>
  <img src=\"images/bank/mastercard.gif\" border=\"0\" hspace=5>
  <p><br></p>

<p>Вы можете оплатить свои заказы в режиме он-лайн кредитные картами (VISA, MasterCard, DCL, JCB, AmEx). Обработка платежей осуществляется процессинговым центром <b>Activepay</b>. </p>

<form name=\"PaymentForm\" action=\"http://activepay.ru/merchant_pages/create/\" method=\"GET\" target=\"_top\" >
<input type=\"hidden\" name=\"merchant_data\"  value=\"$Order-$amount\">
<input type=\"hidden\" name=\"merchant_description\"  value=\"$OrderId\">
<input type=\"hidden\" name=\"amount\"  value=\"$amount\">
<input type=\"hidden\" name=\"merchant_contract\" value=\"$merchant_contract\">
<input type=\"hidden\" name=\"currency\" value=\"$currency\">
<input type=\"hidden\" name=\"commission_included\" value=\"false\">
<input type=\"hidden\" name=\"redirect_url_ok\" value=\"$ReturnUrl\">
<input type=\"hidden\" name=\"redirect_url_failed\" value=\"$FailedUrl\">
<input type=\"hidden\" name=\"signature\" value=\"$SecurityKey\">

	<table>
<tr><td><img src=\"images/shop/icon-setup.gif\" width=\"16\" height=\"16\" border=\"0\"></td>
	<td align=\"center\"><a href=\"javascript:history.back(1)\"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width=\"20\"></td>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:PaymentForm.submit();\">Оплатить через платежную систему</a></td>
</tr>
</table>
</form>

</div>";

?>