<?php

// Получение информации по модулю
function getExampleInfo() {
    $PHPShopOrm= new PHPShopOrm('phpshop_modules_example_system');
    $data=$PHPShopOrm->select();
    return $data['example'];
}

// Прорисовка поля вывода информации
function addExampleInfo() {
    global $PHPShopGUI;
    $Tab3=$PHPShopGUI->setInfo(getExampleInfo(),100);
    $PHPShopGUI->addTab(array("Example",$Tab3,250));
}
// Добавляем значения в функцию actionStart
$addHandler=array(
        'actionStart'=>'addExampleInfo',
        'actionDelete'=>false,
        'actionUpdate'=>false
);
?>