<?php

// Выбор иконки шаблона
function GetSkinsIcon($skin) {
    global $PHPShopSystem;
    if(empty($skin)) $skin=$PHPShopSystem->getParam('skin');
    $dir="../../templates";
    $filename=$dir.'/'.$skin.'/icon/icon.gif';
    if (file_exists($filename))
        $disp='<img src="'.$filename.'" alt="'.$skin.'" width="150" height="120" border="1" id="icon">';
    else $disp='<img src="../../admpanel/img/icon_non.gif" alt="Изображение не доступно" width="150" height="120" border="1" id="icon">';
    return '<div align="center" style="padding:5px">'.$disp.'</div>';
}

// Выбор шаблона
function GetSkinList($skin) {
    global $SysValue,$PHPShopGUI;
    $dir="../../templates";

    if(empty($skin)) $sel='selected';
    $value[]=array('default system','',$sel);
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if($skin == $file)
                    $sel="selected";
                else $sel="";

                if($file!="." and $file!=".." and $file!="index.html")
                    $value[]=array($file,$file,$sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skincat_new',$value,270,'left',false,$onchange="GetSkinIcon(this.value)",200,5);
}

function addSkin($data) {
    global $PHPShopGUI;

    // Добавляем значения в функцию actionStart
    $Tab3=GetSkinList($data['skincat']);
    $Tab3.=$PHPShopGUI->setField('Скриншот',GetSkinsIcon($data['skincat']),$float="none",$margin_left=5);
    $PHPShopGUI->addTab(array("Skin",$Tab3,450));
}

$addHandler=array(
        'actionStart'=>'addSkin',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>