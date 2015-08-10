<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("delivery");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

/*
  // Тестирование
  $_GET['do']='add';
  $_POST['name']='Тестовый товар';
  $_POST['uid']='53';
  $_POST['xid']='1';
  $_POST['num']=10;
 */

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

$PHPShopValutaArray = new PHPShopValutaArray();

// Load JsHttpRequest backend.
require_once $_classPath . "lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest = new JsHttpRequest("windows-1251");

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
$PHPShopOrm->debug = false;

// ИД товара
$productID = $_POST['xid'];

// ИД заказа
$orderID = intval($_POST['uid']);

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

switch ($_GET['do']) {

    // Обновление доставки
    case 'delivery':
        if (CheckedRules($UserStatus["visitor"], 1) == 1) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));
            if (is_array($data)) {
                $order = unserialize($data['orders']);

                $order['Person']['dostavka_metod'] = $productID;

                // Сериализация данных заказа
                $update['orders_new'] = serialize($order);
                $PHPShopOrm->clean();
                $PHPShopOrm->update($update, array('id' => '=' . $orderID));
            }
        }
        break;

    // Обновление скидки
    case 'discount':
        if (CheckedRules($UserStatus["visitor"], 1) == 1) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));
            if (is_array($data)) {
                $order = unserialize($data['orders']);

                $order['Person']['discount'] = $productID;

                // Сериализация данных заказа
                $update['orders_new'] = serialize($order);
                $PHPShopOrm->clean();
                $PHPShopOrm->update($update, array('id' => '=' . $orderID));
            }
        }
        break;


    // Добавление товара в корзину по коду или артикулу
    case 'add':
        if (CheckedRules($UserStatus["visitor"], 1) == 1) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));
            if (is_array($data)) {
                $order = unserialize($data['orders']);

                // Добавление товара по ID
                if (!empty($productID)) {

                    // Библиотека корзины
                    $PHPShopCart = new PHPShopCart($order['Cart']['cart']);

                    // Добавляем новый товар 1 шт по ID
                    if ($PHPShopCart->add($productID, 1)) {

                        // Возвращаем массив измененной корзины
                        $order['Cart']['cart'] = $PHPShopCart->getArray();
                        $order['Cart']['num'] = $PHPShopCart->getNum();
                        $order['Cart']['sum'] = $PHPShopCart->getSum(false);
                    } else {
                        // Добавляем новый товар 1 шт по артикулу
                        $PHPShopCart->add($productID, 1, false, 'uid');

                        // Возвращаем массив измененной корзины
                        $order['Cart']['cart'] = $PHPShopCart->getArray();
                        $order['Cart']['num'] = $PHPShopCart->getNum();
                        $order['Cart']['sum'] = $PHPShopCart->getSum(false);
                    }
                }

                // Сериализация данных заказа
                $update['orders_new'] = serialize($order);
                $PHPShopOrm->clean();
                $PHPShopOrm->update($update, array('id' => '=' . $orderID));
            }
        }
        break;

    // Удаление товара из корзины
    case 'delete':
        if (CheckedRules($UserStatus["visitor"], 2) == 1) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));
            if (is_array($data)) {
                $order = unserialize($data['orders']);

                // Удаление товара из корзины
                unset($order['Cart']['cart'][$productID]);

                // Библиотека корзины
                $PHPShopCart = new PHPShopCart($order['Cart']['cart']);

                $order['Cart']['num'] = $PHPShopCart->getNum();
                $order['Cart']['sum'] = $PHPShopCart->getSum(false);

                // Сериализация данных заказа
                $update['orders_new'] = serialize($order);
                $PHPShopOrm->clean();
                $PHPShopOrm->update($update, array('id' => '=' . $orderID));
            }
        }
        break;

    // Обновление товара в корзине
    case 'update':
        if (CheckedRules($UserStatus["visitor"], 1) == 1) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));
            if (is_array($data)) {
                $order = unserialize($data['orders']);

                // Имя товара
                if (!empty($_POST['name']))
                    $order['Cart']['cart'][$productID]['name'] = $_POST['name'];

                // Количество
                if (!empty($_POST['num']))
                    $order['Cart']['cart'][$productID]['num'] = $_POST['num'];

                // Цена
                if (!empty($_POST['price']))
                    $order['Cart']['cart'][$productID]['price'] = $_POST['price'];

                // Библиотека корзины
                $PHPShopCart = new PHPShopCart($order['Cart']['cart']);

                $order['Cart']['sum'] = $PHPShopCart->getSum(true);

                // Сериализация данных заказа
                $update['orders_new'] = serialize($order);
                $PHPShopOrm->clean();
                $PHPShopOrm->update($update, array('id' => '=' . $orderID));
            }
            break;
        }
}
if (CheckedRules($UserStatus["visitor"], 1) == 1) {

    // Таблица корзины из внешнего файла
    $interfaces = $PHPShopGUI->loadLib('tab_cart', $data, '../', 'ajax');


    $_RESULT = array(
        "interfaces" => $interfaces
    );
}
?>