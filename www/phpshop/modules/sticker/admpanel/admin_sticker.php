<?

$TitlePage="Формы";


function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="630,530";
    $PHPShopInterface->link="../modules/sticker/admpanel/adm_stickerID.php";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Название","30%"),array("Маркер","10%"),array("Страницы","20%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");


    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sticker.sticker_forms"));
    $PHPShopOrm->debug=false;
    $data = $PHPShopOrm->select(array('*'),$where,array('order'=>'id DESC'),array('limit'=>100));

    if(is_array($data))
        foreach($data as $row) {
            extract($row);

            // Дополнительнеы поля
            $content=unserialize($row['content']);
            $dop=null;

            if(is_array($content))
                foreach($content as $k=>$v) {
                    $name=str_replace('dop_', '', $k);
                    $dop.=$name.': '.$v.',';
                }
            $dop=substr($dop,0,strlen($dop)-1);


            $PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),$name,$path,$dir);
        }
    $PHPShopInterface->Compile();
}
?>