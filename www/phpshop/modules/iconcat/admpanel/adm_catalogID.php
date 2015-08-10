<?php

function addIcon($data) {
    global $PHPShopGUI;

    // Иконка
    $Tab10 = $PHPShopGUI->setField(__('Изображение'), $PHPShopGUI->setInputText(false, "icon_new", $data['icon'], '450px', false, 'left') .
            $PHPShopGUI->setButton(__('Выбрать'), "../img/icon-move-banner.gif", "100px", '25px', "right", "ReturnPic('icon_new');return false;"));

    $Tab10.=$PHPShopGUI->setField(__('Описание изображения'),$PHPShopGUI->setTextArea('icon_description_new',$data['icon_description']));
    
    $PHPShopGUI->addTab(array("Иконка", $Tab10, 450));
}

$addHandler = array(
    'actionStart' => 'addIcon',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>