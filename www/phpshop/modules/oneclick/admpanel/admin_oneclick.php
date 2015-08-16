<?php

$TitlePage=" Быстрый Заказ -> Архив заявок";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="630,450";
    $PHPShopInterface->link="../modules/oneclick/admpanel/adm_oneclickID.php";
    $PHPShopInterface->setCaption(array("Дата","5%"),array("Телефон","10%"),array("Имя","20%"),
            array("Наименование","20%"),array("Цена","10%"),
            array("Время","7%"),
            array("Сообщение","10%"),
            array("Статус","10%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");
    
    $status_array=array(
        1=>'Новая заявка',
        2=>'Просили перезвонить',
        3=>'Недоcтупен',
        4=>'Выполнен'
    );

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.oneclick.oneclick_jurnal"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array("limit"=>"1000"));
    if(is_array($data))
        foreach($data as $row) {
            extract($row);
            $PHPShopInterface->setRow($id,PHPShopDate::dataV($date,false),$tel,$name,$product_name,$product_price,PHPShopDate::dataV($date),substr($message,0,150),$status_array[$status]);
        }
    
    $PHPShopInterface->Compile();
}
?>