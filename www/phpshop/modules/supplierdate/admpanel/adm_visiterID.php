<?php




function addFieldOrderInfo($data) {
    global $PHPShopGUI;
    
    // ��������� �������� � ������� actionStart
    $Tab3=$PHPShopGUI->setField('���������',$PHPShopGUI->setTextarea(false, $data['mod_supplier_info'], $float = "none", $width = '99%', $height = '300px'));
    $PHPShopGUI->addTab(array("�������������",$Tab3,350));
}

$addHandler=array(
        'actionStart'=>'addFieldOrderInfo',
        'actionDelete'=>false,
        'actionUpdate'=>false,
        'actionSave'=>false
);

?>