<?php

function payonlinesystem_users_repay($obj,$PHPShopOrderFunction) {
    global $PHPShopBase;

    // Регистрационная информация
    $PrivateSecurityKey=$PHPShopBase->getParam('payonlinesystem.PrivateSecurityKey');
    $MerchantId=$PHPShopBase->getParam('payonlinesystem.MerchantId');
    $Currency=$PHPShopBase->getParam('payonlinesystem.currency');

    // параметры магазина
    $mrh_ouid = explode("-", $PHPShopOrderFunction->objRow['uid']);
    $OrderId = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета
    $Amount=number_format($TotalSumOrder,2,".","");
    $Amount = $PHPShopOrderFunction->getTotal(); //сумма покупки

    // Проверочный ключ
    $SecurityKey=md5("MerchantId=$MerchantId&OrderId=$OrderId&Amount=$Amount&Currency=$Currency&PrivateSecurityKey=$PrivateSecurityKey");
    
    // Если заказ не оплачен
    if($PHPShopOrderFunction->getParam('statusi') != 101)
        $disp="<form name=\"PaymentForm\" action=\"https://secure.payonlinesystem.com/ru/payment/\" method=\"get\" target=\"_top\" >
<input type=\"hidden\" name=\"OrderId\" id=\"OrderId\" value=\"$OrderId\">
<input type=\"hidden\" name=\"Amount\" id=\"Amount\" value=\"$Amount\">
<input type=\"hidden\" name=\"MerchantId\" value=\"$MerchantId\">
<input type=\"hidden\" name=\"Currency\" value=\"$Currency\">
<input type=\"hidden\" name=\"SecurityKey\" value=\"$SecurityKey\">
	<a href=\"javascript:void(0)\" class=b title=\"".__('Оплатить')." ".$PHPShopOrderFunction->getOplataMetodName()."\" onclick=\"javascript:PaymentForm.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>".$PHPShopOrderFunction->getOplataMetodName()."</a>
            </form>";
    else $disp=PHPShopText::b($PHPShopOrderFunction->getOplataMetodName());

    return $disp;
}
?>