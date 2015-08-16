<?php

function success_mod_liqpay_hook($obj, $value) {
  
    // Преобразуем строку в переменные
    parse_str(base64_decode($value['payment']), $value);
	
    if ($value['payment'] == 'liqpay') {
        
        $return=array();
        $return['order_metod']='modules';
        $return['success_function']=false;// Включаем функцию обновления статуса заказа
        $return['crc'] = null;
        $return['my_crc'] = null;
        $return['inv_id'] = $value['inv_id'];
        $return['out_summ'] = false;

        return $return;
    }
}

$addHandler = array
    (
    'index' => 'success_mod_liqpay_hook'
);
?>