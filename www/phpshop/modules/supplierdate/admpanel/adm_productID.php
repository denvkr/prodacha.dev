<?php




function addFieldSupplierDate($data) {
    global $PHPShopGUI;
    
    // ��������� �������� � ������� actionStart
    $Tab9=$PHPShopGUI->setField('���� ��������',$PHPShopGUI->setInputText(false, 'mod_supplier_date_new', $data['mod_supplier_date']));
    $PHPShopGUI->addTab(array("����������",$Tab9,450));
}

$addHandler=array(
        'actionStart'=>'addFieldSupplierDate',
        'actionDelete'=>false,
        'actionUpdate'=>false,
        'actionSave'=>false
);

?>