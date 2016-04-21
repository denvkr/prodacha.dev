<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_icon_bottom_text($iconid,$region) {
//массив содержащий названия
 $icons_text_array=array(
    'm_181'=>array('bottom_text'=>'Предпродажная подготовка','description'=>'Предпродажная подготовка при самовывозе из наших магазинов бесплатно. Заправим, заведем, проверим!<br /><br />'),
    'm_184'=>array('bottom_text'=>'Напрямую с завода','description'=>'Без посредников, напрямую от производителя'),
    'm_185'=>array('bottom_text'=>'Расширенная гарантия','description'=>'Обращение по гарантии в магазин, в котором вы совершили покупку.<br /><br />Результат диагностики в течение 5 рабочих дней, ремонт или замена в течение 10 рабочих дней.'),
    'm_186'=>array('bottom_text'=>'Самовывоз в день заказа','description'=>'Самовывоз в день заказа'),
    'm_302'=>array('bottom_text'=>'Подарок при покупке!','description'=>''),
    'other_181'=>array('bottom_text'=>'Предпродажная подготовка','description'=>'Предпродажная подготовка при самовывозе из наших магазинов бесплатно. Заправим, заведем, проверим!<br /><br />'),
    'other_184'=>array('bottom_text'=>'Напрямую с завода','description'=>'Без посредников, напрямую от производителя'),
    'other_185'=>array('bottom_text'=>'Расширенная гарантия','description'=>'Обращение по гарантии в магазин, в котором вы совершили покупку.<br /><br />Результат диагностики в течение 5 рабочих дней, ремонт или замена в течение 10 рабочих дней.'),
    'other_186'=>array('bottom_text'=>'Самовывоз в день заказа','description'=>'Самовывоз в день заказа'),
    'other_302'=>array('bottom_text'=>'Подарок при покупке!','description'=>''),
    'sp_181'=>array('bottom_text'=>'Предпродажная подготовка','description'=>'Предпродажная подготовка при самовывозе из наших магазинов бесплатно. Заправим, заведем, проверим!<br /><br />'),
    'sp_184'=>array('bottom_text'=>'Напрямую с завода','description'=>'Без посредников, напрямую от производителя'),
    'sp_185'=>array('bottom_text'=>'Расширенная гарантия','description'=>'Обращение по гарантии в магазин, в котором вы совершили покупку.<br /><br />Результат диагностики в течение 5 рабочих дней, ремонт или замена в течение 10 рабочих дней.'),
    'sp_186'=>array('bottom_text'=>'Самовывоз в день заказа','description'=>'Самовывоз в день заказа'),
    'sp_302'=>array('bottom_text'=>'Подарок при покупке!','description'=>''),
    'chb_181'=>array('bottom_text'=>'Предпродажная подготовка','description'=>'Предпродажная подготовка при самовывозе из наших магазинов бесплатно. Заправим, заведем, проверим!<br /><br />'),
    'chb_184'=>array('bottom_text'=>'Напрямую с завода','description'=>'Без посредников, напрямую от производителя'),
    'chb_185'=>array('bottom_text'=>'Расширенная гарантия','description'=>'Обращение по гарантии в магазин, в котором вы совершили покупку.<br /><br />Результат диагностики в течение 5 рабочих дней, ремонт или замена в течение 10 рабочих дней.'),
    'chb_186'=>array('bottom_text'=>'Самовывоз в день заказа','description'=>'Самовывоз в день заказа'),
    'chb_302'=>array('bottom_text'=>'Подарок при покупке!','description'=>''),
    'm_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки">Все условия доставки</a>'),
    'other_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки">Все условия доставки</a>'),
    'sp_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки">Все условия доставки</a>'),
    'chb_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки">Все условия доставки</a>')
    );
 
    foreach($icons_text_array as $key=>$val) {
        if ($key== $region.'_'.$iconid)
            //var_dump($val);
           return $val;
    }
}