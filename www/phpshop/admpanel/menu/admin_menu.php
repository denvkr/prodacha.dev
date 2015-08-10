<?php

$TitlePage = __("Текстовые блоки");

function actionStart() {
    global $PHPShopInterface;
    $PHPShopInterface->size = "650,530";
    $PHPShopInterface->link = "menu/adm_menuID.php";
    $PHPShopInterface->setCaption(array("&plusmn;", "5%"), array("Название", "50%"), array("Цель", "10%"), array("Расположение", "10%"));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['menu']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
            extract($row);

            if ($element == 0)
                $element = __("Слева");
            else
                $element = __("Справа");

            $PHPShopInterface->setRow($id, $PHPShopInterface->icon($flag), $name, $dir, $element);
        }

    $PHPShopInterface->setAddItem('menu/adm_menu_new.php');
    $PHPShopInterface->Compile();
}

?>
