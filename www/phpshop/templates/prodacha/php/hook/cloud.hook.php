<?php

/**
 * Изменение цвета текста в облаке тегов
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function index_hook($obj,$row,$rout) {
    if($rout == 'START')
    $obj->color='0x4C9DD5';
}



$addHandler=array
        (
        'index'=>'index_hook',
);

?>
