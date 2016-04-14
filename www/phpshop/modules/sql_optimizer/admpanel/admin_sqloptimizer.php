<?php

$TitlePage="SQL Оптимизатор";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->razmer='800px';
    $PHPShopInterface->setCaption(array("Таблица","10%"),array("Кол-во строк","10%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    $PHPShopInterface->setRow('test1','test2');    
    $PHPShopInterface->Compile();
}
?>