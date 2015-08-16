<?php

$TitlePage=" ReCall -> Архив заявок";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="630,450";
    $PHPShopInterface->link="../modules/returncall/admpanel/adm_returncallID.php";
    $PHPShopInterface->setCaption(array("Дата","5%"),array("Телефон","10%"),array("IP","5%"),array("Имя","20%"),array("Время","7%"),
            array("Сообщение","20%"),array("Статус","10%"));

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
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.returncall.returncall_jurnal"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array("limit"=>"1000"));
    if(is_array($data))
        foreach($data as $row) {
        $time=null;
            extract($row);
            if(!empty($time_start)) $time.=' с '.$time_start;
            if(!empty($time_end)) $time.=' до '.$time_end;
            $PHPShopInterface->setRow($id,PHPShopDate::dataV($date,false),$tel,$ip,$name,$time,substr($message,0,150),$status_array[$status]);
        }
    
    $PHPShopInterface->Compile();
}
?>