<?php

/**
 * Изменение сетки товаров в поиске
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function words_searchcore_hook($obj,$row,$rout) {

    if($rout == 'START'){
    $obj->cell=1;
    $obj->line=true;
    $obj->grid=true;
    }
}



$addHandler=array
        (
        'words'=>'words_searchcore_hook',
);

?>
