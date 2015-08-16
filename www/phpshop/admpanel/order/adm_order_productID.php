<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("product");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = __("Редактирование Заказа");
$PHPShopGUI->reload = "top";
$PHPShopGUI->addJSFiles('/phpshop/lib/JsHttpRequest/JsHttpRequest.js');
$PHPShopGUI->addJSFiles('gui/tab_cart.gui.js');

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem, $PHPShopBase;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;
    
    // ИД заказа
    $orderID=intval($_GET['orderID']);
    
    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($orderID)));

    $PHPShopGUI->dir = "../";

    // Нет данных
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "Закрыть", "center", 100, "return onCancel();", "but"));
        return true;
    }

    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($orderID);

    $order = unserialize($data['orders']);

    // ИД товара
    $productID = intval($_GET['productID']);
    
    if(empty($order['Cart']['cart'][$productID]['id'])){ 
        foreach($order['Cart']['cart'] as $key=>$val)
            if($val['id'] == $productID){
                $productID=$key;
            }
    }
    
    // Наименование
    $Tab1 = $PHPShopGUI->setField(__("Наименование"), $PHPShopGUI->setInputText(false, 'name_new', $order['Cart']['cart'][$productID]['name'], '100%')) . $PHPShopGUI->setLine();

    // Иконка
    $PHPShopProduct = new PHPShopProduct($productID);
    $productIcon=$PHPShopProduct->getParam('pic_small');
    $productEdIzm=$PHPShopProduct->getParam('ed_izm');
    if (!empty($productIcon)) {
        $img_width = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
        $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopGUI->setFrame('img', $productIcon, $img_width + 20, $img_width, 'none', 0, 'No'), 'width:' . ($img_width + 50) . 'px;float:left');
    }
    
    // Склад
    if (empty($productEdIzm))
        $ed_izm = 'шт.';
    else
        $ed_izm = $productEdIzm;
    
    // Количество
    $Tab1.=$PHPShopGUI->setField(__("Количество"), "<div style=\"padding:5px\"><input type=\"text\" style=\"width: 50px;\" value=\"" . $order['Cart']['cart'][$productID]['num']. "\" id=\"num\" onchange=\"DoUpdateOrderProductSum()\"> ".$ed_izm, 'left');
   
    // Сумма
    $productSum=$PHPShopOrder->ReturnSumma($order['Cart']['cart'][$productID]['price'] * $order['Cart']['cart'][$productID]['num'],0);
    $Tab1.=$PHPShopGUI->setField(__("Сумма ") . '(' . $PHPShopSystem->getDefaultValutaCode() . ')', $PHPShopGUI->setDiv('center', $productSum, 'font-size:25px;font-weight:bold', 'sum'), 'left');

    // Цена
    $Tab1.=$PHPShopGUI->setField(__("Цена ") . '(' . $PHPShopSystem->getDefaultValutaCode() . ')', "<div style=\"padding:5px\"><input type=\"text\" style=\"width: 70px;\" value=\"" . $PHPShopOrder->ReturnSumma($order['Cart']['cart'][$productID]['price'], 0) . "\" id=\"price\" onchange=\"DoUpdateOrderProductSum()\"> ", 'left');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 200));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "DoDelFromOrder('" . $productID . "', $orderID)", "but") .
            $PHPShopGUI->setInput("button", "editID", "Сохранить", "right", 70, "DoUpdateFromOrder('" . $productID . "',$orderID, this.form.name_new.value,this.form.num.value,this.form.price.value)", "but");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


if (CheckedRules($UserStatus["cat_prod"], 0) == 1) {

    // Вывод формы при старте
    $PHPShopGUI->setAction($_GET['orderID'], 'actionStart', 'none');

    // Обработка событий
    $PHPShopGUI->getAction();
} else {

    // Запрет редактирования
    $UserChek->BadUserFormaWindow();
}
?>