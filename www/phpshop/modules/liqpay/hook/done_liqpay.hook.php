<?php

function send_to_order_mod_liqpay_hook($obj, $value, $rout) {

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10001) {

        // Настройки модуля
        include_once(dirname(__FILE__) . '/mod_option.hook.php');
        $PHPShopLiqpayArray = new PHPShopLiqpayArray();
        $option = $PHPShopLiqpayArray->getArray();

        // Контроль оплаты от статуса заказа
        if (empty($option['status'])) {

            // Номер счета
            $mrh_ouid = explode("-", $value['ouid']);
            $inv_id = $mrh_ouid[0] . $mrh_ouid[1];

            // Сумма покупки
            $out_summ = number_format($obj->get('total'), 2, '.', '');

            $xml = '<request>      
      <version>1.2</version>
      <merchant_id>' . $option['merchant_id'] . '</merchant_id>
      <server_url>http://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/liqpay/payment/result.php</server_url>
      <result_url>http://' . $_SERVER['SERVER_NAME'] . '/success/?payment=' . base64_encode('payment=liqpay&inv_id=' . $inv_id) . '</result_url>
      <order_id>' . $inv_id . '</order_id>
      <amount>' . $out_summ . '</amount>
      <currency>' . $obj->PHPShopSystem->getDefaultValutaIso() . '</currency>
      <description>PHPShopPaymentService</description>
      <default_phone>' . $value['tel_code'] . "-" . $value['tel_name'] . '</default_phone>
      <pay_way>card,liqpay,delayed</pay_way>
      </request>';

            // Подпись
            $sign = base64_encode(sha1($option['merchant_sig'] . $xml . $option['merchant_sig'], 1));

            // Платежная форма
            $payment_forma = PHPShopText::setInput('hidden', 'operation_xml', base64_encode($xml));
            $payment_forma.=PHPShopText::setInput('hidden', 'signature', $sign);
            $payment_forma.=PHPShopText::setInput('submit', 'send', 'Оплатить через платежную систему', $float = "none", 250);
            $obj->set('payment_forma', PHPShopText::form($payment_forma, 'pay', 'post', 'https://www.liqpay.com/?do=clickNbuy'));
            $obj->set('payment_info', $option['title_end']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['liqpay']['liqpay_payment_forma'], true);
        } else {

            $clean_cart = "
<script language=\"JavaScript1.2\">
if(window.document.getElementById('num')){
window.document.getElementById('num').innerHTML='0';
window.document.getElementById('sum').innerHTML='0';
}
</script>";
            $obj->set('mesageText', $option['title_end'] . $clean_cart);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);

            // Очищаем корзину
            unset($_SESSION['cart']);
        }

        $obj->set('orderMesage', $forma);
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mod_liqpay_hook'
);
?>