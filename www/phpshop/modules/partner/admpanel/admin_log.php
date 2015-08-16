<?php

$TitlePage="Партнеры -> Журнал заказов";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="700,550";
    $PHPShopInterface->link="order/adm_visitorID.php";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("№","5%"),array("Дата","10%"),array("Покупатель","25%"),array("Партнер","25%"),array("PID","5%"),array("Ссылка","30%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");


    // SQL
    $PHPShopOrm = new PHPShopOrm();
    $result = $PHPShopOrm->query('SELECT a.*, b.login, c.id FROM '.$PHPShopModules->getParam("base.partner.partner_log").' AS a JOIN '.$PHPShopModules->getParam("base.partner.partner_users").' AS b ON a.partner_id = b.id JOIN '.$GLOBALS['SysValue']['base']['table_name1'].' AS c ON a.order_id = c.uid order by a.order_id desc limit 100;');

    while($row = mysql_fetch_array($result)) {
        extract($row);
        $sum=number_format($sum,"2",".","");
        $PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),$order_id,PHPShopDate::dataV($date),$order_user,$login,$partner_id,TotalClean($path,2));
    }

    $PHPShopInterface->Compile();
}
?>