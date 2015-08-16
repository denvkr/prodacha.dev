<?php
/**
 * Изменение сетки товаров в "Сейчас покупают"
 * @param array $obj объект
 */
function nowBuy_hook($obj) {
   $obj->limitpos = 5; // Количество выводимых позиций
   $obj->limitorders = 5; // Количество запрашиваемых заказов
}
 
$addHandler=array
        (
        'nowBuy'=>'nowBuy_hook'
         );
?>