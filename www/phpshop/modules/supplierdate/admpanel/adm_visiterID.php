<?php




function addFieldOrderInfo($data) {
    global $PHPShopGUI;
    
    // Добавляем значения в функцию actionStart
    $Tab3=$PHPShopGUI->setField('Реквизиты',$PHPShopGUI->setTextarea(false, $data['mod_supplier_info'], $float = "none", $width = '99%', $height = '300px'));
    $PHPShopGUI->addTab(array("Дополнительно",$Tab3,350));
}

$addHandler=array(
        'actionStart'=>'addFieldOrderInfo',
        'actionDelete'=>false,
        'actionUpdate'=>false,
        'actionSave'=>false
);

?>