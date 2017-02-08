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
    'kur_181'=>array('bottom_text'=>'Предпродажная подготовка','description'=>'Предпродажная подготовка при самовывозе из наших магазинов бесплатно. Заправим, заведем, проверим!<br /><br />'),
    'kur_184'=>array('bottom_text'=>'Напрямую с завода','description'=>'Без посредников, напрямую от производителя'),
    'kur_185'=>array('bottom_text'=>'Расширенная гарантия','description'=>'Обращение по гарантии в магазин, в котором вы совершили покупку.<br /><br />Результат диагностики в течение 5 рабочих дней, ремонт или замена в течение 10 рабочих дней.'),
    'kur_186'=>array('bottom_text'=>'Самовывоз в день заказа','description'=>'Самовывоз в день заказа'),
    'kur_302'=>array('bottom_text'=>'Подарок при покупке!','description'=>''),
    'm_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки" style="color:#588910">Все условия доставки</a>'),
    'other_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки" style="color:#588910">Все условия доставки</a>'),
    'sp_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки" style="color:#588910">Все условия доставки</a>'),
    'chb_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки" style="color:#588910">Все условия доставки</a>'),
    'kur_dostavka'=>array('bottom_text'=>'Доставка бесплатно',  'description'=>'&nbsp;&nbsp;Заказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="Все условия доставки" style="color:#588910">Все условия доставки</a>')
    );
 
    foreach($icons_text_array as $key=>$val) {
        if ($key== $region.'_'.$iconid)
            //var_dump($val);
           return $val;
    }
}

function get_more_gift($siteurl,$prodid) {
$searchgift=false;
//массив содержащий названия
 $gifts_array=array(
     364=>'<table><tr><td><span><ul><li><a href="//'.$siteurl.'/shop/UID_8896.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">Бензиновый снегоуборщик MTD SMART M 56</a></li><br/>'
                       . '<li><a href="//'.$siteurl.'/shop/UID_8896.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">Бензиновая газонокосилка Parton PA550N21RH3 несамоходная</a></li><br/></ul></span><span style="color: #e7193f;font: 14px Arial,Helvetica,sans-serif;font-weight: bold;"><strike>0 руб.</strike></span><br><span style="font: 14px Arial,Helvetica,sans-serif;font-weight: bold;color:#6C4B46;">В подарок!</span></td></tr></table>'
    );
 
    foreach($gifts_array as $key=>$val) {
        if ($key === $prodid){
            $searchgift=true;
           return $val;            
        }
    }
    return $searchgift;
}

function get_icon_dealer($iconid) {
$searchdealer=false;
//массив содержащий названия
 $icons_text_array=array(
    445=>array('icon'=>'images/certificate-icon-2.png','description'=>'<table><tr><td><a id="showsertificatehref" href="#" onclick="showsertificate(\'/UserFiles/Image/445.jpg\')"><div style="width:80px;height:40px;background: url(/UserFiles/Image/mtd.png); background-repeat: no-repeat;background-position: center;-webkit-background-size: 80px auto;-moz-background-size: 80px auto;-o-background-size: 80px auto;"></div><div id="showsertificate" onclick="showsertificate(\'/UserFiles/Image/445.jpg\')" style="width:80px;height:40px;color: #588910;"><p>Посмотреть сертификат</p></div></a></td><td><p>PROДАЧА - официальный дилер MTD. Покупая у нас, вы получаете профессиональную консультацию по изделию, высокое качество продукции и официальную гарантию производителя.</p></td></tr></table>')
    );
 
    foreach($icons_text_array as $key=>$val) {
        if ($key === $iconid){
            //$searchdealer=true;
           return $val;            
        }
    }
    return $searchdealer;
}
