<?php


/**
 * E-mail XML файла заказа
 */
function mail_pickpoint_hook($obj,$row,$rout) {

    if($rout == 'END' and !empty($_POST['pickpoint_id'])){
        
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['pickpoint']['pickpoint_system']);
        $option=$PHPShopOrm->select();
        

        $content_file='<?xml version="1.0" encoding="windows-1251"?>
<documents>
<document>
<fio>'.$_POST['name_person'].'</fio>
<sms_phone>+7'.$_POST['tel_code'].$_POST['tel_name'].'</sms_phone>
<email>'.$_POST['mail'].'</email>
<additional_phones/>
<order_id>'.$_POST['ouid'].'</order_id>
<summ_rub>'.$obj->get('total').'</summ_rub>
<terminal_id>'.$_POST['pickpoint_id'].'</terminal_id>
<type_service>'.$option['type_service'].'</type_service>
<type_reception>'.$option['type_reception'].'</type_reception>
<embed>'.$obj->PHPShopSystem->getName().'</embed>
<size_x/> 
<size_y/> 
<size_z/> 
</document>
</documents>';

        // Запись в файл
        $file='files/price/'.$_POST['ouid'].'.xml';
        @fwrite(fopen($file,"w+"), $content_file);
        
        // Отсылаем письмо администратору
        $PHPShopMailFile= new PHPShopMailFile($obj->PHPShopSystem->getParam('adminmail2'),$_POST['mail'],'PickPoint N'.$_POST['ouid'],$content,'PickPoint_'.$_POST['ouid'].'.xml',$file);

        // Удаляем файл
        unlink($file);
    }

}

$addHandler=array
        (
        'mail'=>'mail_pickpoint_hook'
);

?>