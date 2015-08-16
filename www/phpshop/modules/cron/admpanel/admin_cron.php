<?

$TitlePage="Журнал выполнения задач Cron";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->razmer='800px';
    $PHPShopInterface->setCaption(array("Дата","10%"),array("Задача","20%"),array("Исполняемый файл","40%"),array("Статус","10%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_log"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array('limit'=>100));
    if(is_array($data))
        foreach($data as $row) {
            extract($row);

            $PHPShopInterface->setRow($id,PHPShopDate::dataV($date),$name,$path,$status);
        }

    $PHPShopInterface->Compile();
}
?>