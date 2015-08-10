<?php

$TitlePage="Элементы";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="630,530";
    $PHPShopInterface->link="../modules/sortproduct/admpanel/adm_sortproductID.php";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Название","70%"),array("Ссылок","20%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");


    // SQL
    $PHPShopOrm = new PHPShopOrm();
    $result=$PHPShopOrm->query('SELECT a.*, b.name FROM '.$PHPShopModules->getParam("base.sortproduct.sortproduct_forms").' AS a JOIN '.$GLOBALS['SysValue']['base']['sort_categories'].' AS b ON a.sort = b.id order by a.id DESC limit 300');

    while($row = mysql_fetch_array($result)) {
        extract($row);
        $PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),$name,$items);
    }
    
    $PHPShopInterface->Compile();
}
?>