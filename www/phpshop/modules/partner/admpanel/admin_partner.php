<?

$TitlePage="Партнеры";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="530,530";
    $PHPShopInterface->link="../modules/partner/admpanel/adm_partnerID.php";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Регистрация","10%"),array("ID","10%"),array("Баланс (руб.)","10%"),array("Партнер","30%"),array("E-mail","30%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_users"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array('limit'=>100));
    if(is_array($data))
        foreach($data as $row) {
            extract($row);

            $PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),$date,$id,$money,$login,$mail);
        }

    $PHPShopInterface->Compile();
}
?>