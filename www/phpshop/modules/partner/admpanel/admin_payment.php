<?

$TitlePage="Партнеры -> Заявки на вывод средств";




function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->link="../modules/partner/admpanel/adm_paymentID.php";
    $PHPShopInterface->size="530,530";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Дата","10%"),array("Пользователь","30%"),array("E-mail","30%"),array("Сумма","20%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    // SQL
    $PHPShopOrm = new PHPShopOrm();
    $result=$PHPShopOrm->query('SELECT a.*, b.login, b.mail FROM '.$PHPShopModules->getParam("base.partner.partner_payment").' AS a JOIN '.$PHPShopModules->getParam("base.partner.partner_users").' AS b ON a.partner_id = b.id order by a.id DESC limit 300');

    while($row = mysql_fetch_array($result)) {
            extract($row);
            $sum=number_format($sum,"2",".","");
            $PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),PHPShopDate::dataV($date),$login,$mail,$sum);
        }

    $PHPShopInterface->Compile();
}
?>