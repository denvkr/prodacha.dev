<?php

function robox_users_repay($obj, $PHPShopOrderFunction) {
    global $PHPShopBase, $SysValue;

    // Регистрационная информация
    $mrh_login = $SysValue['roboxchange']['mrh_login'];    //логин
    $mrh_pass1 = $SysValue['roboxchange']['mrh_pass1'];    // пароль1
    
    //параметры магазина
    $mrh_ouid = explode("-", $PHPShopOrderFunction->objRow['uid']);
    $inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];     //номер счета
    //описание покупки
    $inv_desc = 'PHPShopPaymentService';
    $out_summ = $PHPShopOrderFunction->getTotal(); //сумма покупки
    $out_summ = number_format($out_summ, 2, '.', '');

    // формирование подписи
    $crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

    // Если заказ не оплачен
    if ($PHPShopOrderFunction->getParam('statusi') != 101)
        $disp = "<form action='https://www.roboxchange.com/ssl/calc.asp' method=POST name=\"payrobots\">
      <input type=hidden name=MrchLogin  value=$mrh_login>
       <input type=hidden name=OutSum  value=$out_summ>
       <input type=hidden name=InvId  value=$inv_id>
       <input type=hidden name=Desc  value=$inv_desc>
           <input type=hidden name=SignatureValue value=$crc>  
        
        
	<a href=\"javascript:void(0)\" class=b title=\"" . __('Оплатить') . " " . $PHPShopOrderFunction->getOplataMetodName() . "\" onclick=\"javascript:payrobots.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>" . $PHPShopOrderFunction->getOplataMetodName() . "</a>
            </form>";
    else
        $disp = PHPShopText::b($PHPShopOrderFunction->getOplataMetodName());

    return $disp;
}

?>