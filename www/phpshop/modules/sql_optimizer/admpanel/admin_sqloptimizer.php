<?php

$TitlePage="SQL �����������";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->razmer='800px';
    $PHPShopInterface->setCaption(array("�������","10%"),array("���-�� �����","10%"));

    // ��������� ������
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    $PHPShopInterface->setRow('test1','test2');    
    $PHPShopInterface->Compile();
}
?>