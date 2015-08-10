<?php

$TitlePage = __("Блог");

function actionStart() {
    global $PHPShopInterface,$_classPath;
    $PHPShopInterface->size = "630,550";
    $PHPShopInterface->link = "../modules/blog/admpanel/adm_blogID.php";
    $PHPShopInterface->setCaption(array("Дата", "10%"), array("Заголовок", "45%"), array("Краткая информация", "45%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");
    
    
    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.blog.blog_log"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
            extract($row);
            $PHPShopInterface->setRow($id, $date, $title, substr(strip_tags($description), 0, 150) . "...");
        }

    $PHPShopInterface->setAddItem('../modules/blog/admpanel/adm_blog_new.php');
    $PHPShopInterface->Compile();
}

?>
