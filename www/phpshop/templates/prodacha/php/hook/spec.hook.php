<?php

/**
 * Изменение сетки товаров в спецпредложениях
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function index_speccore_hook($obj,$row,$rout) {

    if($rout == 'START')
    $obj->cell=1;
}



$addHandler=array
        (
        'index'=>'index_speccore_hook'
);

?>