<?php




function addFieldSupplierDate($data) {
    global $PHPShopGUI;
    
    // Добавляем значения в функцию actionStart
    $Tab9=$PHPShopGUI->setField('Дата поставки',$PHPShopGUI->setInputText(false, 'mod_supplier_date_new', $data['mod_supplier_date']));
    $PHPShopGUI->addTab(array("Поставщики",$Tab9,450));
}

$addHandler=array(
        'actionStart'=>'addFieldSupplierDate',
        'actionDelete'=>false,
        'actionUpdate'=>false,
        'actionSave'=>false
);

?>