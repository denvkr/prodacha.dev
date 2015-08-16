<?php

function rbkmoney_users_repay($obj,$PHPShopOrderFunction) {
    global $PHPShopBase;

    // регистрационная информация
$MerchantId = $PHPShopBase->getParam('rbk.eshopId');
$serviceName = $PHPShopBase->getParam('rbk.serviceName');
$Currency = $PHPShopBase->getParam('rbk.Currency');

$mrh_ouid = explode("-", $PHPShopOrderFunction->objRow['uid']);
$inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];     //номер счета

$OrderId = $inv_id;
$Amount = $PHPShopOrderFunction->getTotal();
$email = $PHPShopOrderFunction->getmail();

// обратный адрес
$ReturnUrl = urlencode("http://" . $_SERVER['SERVER_NAME'] . "/success/?payment=rbkmoney&inv_id=".$inv_id);
$FailUrl = urlencode("http://" . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . "/fail/");  
    
    // Если заказ не оплачен
    if($PHPShopOrderFunction->getParam('statusi') != 101)
        $disp="<form action=\"https://rbkmoney.ru/acceptpurchase.aspx\" method=post name=\"payrbkmoney\">
                   <input type=\"hidden\" name=\"eshopId\" value=\"$MerchantId\">
                   <input type=\"hidden\" name=\"user_email\" value=\"" . $email . "\">
                   <input type=\"hidden\" name=\"orderId\" value=\"$OrderId\">
                   <input type=\"hidden\" name=\"serviceName\" value=\"$serviceName\">
                   <input type=\"hidden\" name=\"recipientAmount\" value=\"$Amount\">
                   <input type=\"hidden\" name=\"recipientCurrency\" value=\"$Currency\">
                   <input type=\"hidden\" name=\"version\" value=\"2\">
                   <input type=\"hidden\" name=\"successUrl\" value=\"$ReturnUrl\">
                   <input type=\"hidden\" name=\"failUrl\" value=\"$FailUrl\">        
	<a href=\"javascript:void(0)\" class=b title=\"".__('Оплатить')." ".$PHPShopOrderFunction->getOplataMetodName()."\" onclick=\"javascript:payrbkmoney.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>".$PHPShopOrderFunction->getOplataMetodName()."</a></form>";
    else $disp=PHPShopText::b($PHPShopOrderFunction->getOplataMetodName());

    return $disp;
}
?>