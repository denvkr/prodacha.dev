<?php

/**
 * Изменение кол-ва новостей на главной странице
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function index_hook($obj,$row,$rout) {

    if($rout == 'START')
    $obj->limit=1;
}



$addHandler=array
        (
        'index'=>'index_hook',
);

?>