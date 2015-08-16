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
PHPShopObj::loadClass("delivery");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = __("Редактирование Доставки");
$PHPShopGUI->reload = "top";
$PHPShopGUI->addJSFiles('/phpshop/lib/JsHttpRequest/JsHttpRequest.js');
$PHPShopGUI->addJSFiles('gui/tab_cart.gui.js');

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    // ИД доставки
    $deliveryID = intval($_GET['deliveryID']);

    // ИД заказа
    $orderID = intval($_GET['orderID']);

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('is_folder' => "='1'"), array('order' => 'city'), array('limit' => 1000));

    $PHPShopGUI->dir = "../";

    // Нет данных
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "Закрыть", "center", 100, "return onCancel();", "but"));
        return true;
    }

    // Общий массив доставки
    $PHPShopDelivery = new PHPShopDeliveryArray();
    $PHPShopDeliveryArray = $PHPShopDelivery->getArray();
    $PHPShopDeliveryPidArray = $PHPShopDelivery->getKey('PID.id', true);
    
    //unset($PHPShopDeliveryPidArray[0]);
    $delivery_price = null;

    // Значения доставки
    if (is_array($data))
        foreach ($data as $row) {
            $delivery_group_value = array();
            if (is_array($PHPShopDeliveryPidArray[$row['id']])) {
                foreach ($PHPShopDeliveryPidArray[$row['id']] as $value) {

                    $delivery_group_value[] = array($PHPShopDeliveryArray[$value]['city'], $value, $deliveryID);
                    $delivery_price.=$PHPShopGUI->setInput('hidden', $value, $PHPShopDeliveryArray[$value]['price']);
                }
                $delivery_value[] = array($row['city'], $delivery_group_value);
            }
        }


    // Доставка без каталога
    $data_root[0] = array(
        'id' => 0,
        'city' => 'Доставка',
        'is_folder' => 1
    );
    
    if (is_array($data_root))
        foreach ($data_root as $row) {
            $delivery_group_value = array();
            if (is_array($PHPShopDeliveryPidArray[$row['id']])) {
                foreach ($PHPShopDeliveryPidArray[$row['id']] as $value) {
                    if (empty($PHPShopDeliveryArray[$value]['PID']) and empty($PHPShopDeliveryArray[$value]['is_folder'])){
                    $delivery_group_value[] = array($PHPShopDeliveryArray[$value]['city'], $value, $deliveryID);
                    $delivery_price.=$PHPShopGUI->setInput('hidden', $value, $PHPShopDeliveryArray[$value]['price']);
                    }
                }
                $delivery_value[] = array($row['city'], $delivery_group_value);
            }
        }


    // Стоимость
    $Tab1 = $PHPShopGUI->setDiv(false, $delivery_price, 'display:none');

    // Выбор доставки
    $Tab1.= $PHPShopGUI->setField(__("Город доставки"), $PHPShopGUI->setSelect('delivery', $delivery_value, 250, false, false, 'javascript:document.getElementById(\'sum\').innerHTML=document.getElementById(this.value).value'), 'left');

    // Сумма
    $Tab1.=$PHPShopGUI->setField(__("Сумма ") . '(' . $PHPShopSystem->getDefaultValutaCode() . ')', $PHPShopGUI->setDiv('center', $PHPShopDeliveryArray[$deliveryID]['price'], 'font-size:25px;font-weight:bold', 'sum'), 'left');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 150));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "editID", "Сохранить", "right", 70, "DoUpdateDeliveryFromOrder(delivery.value,$orderID)", "but");

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