<?php

// Поддержка ссылок из модулей
if (!empty($_GET['id']))
    $_GET['visitorID'] = $_REQUEST['visitorID'] = $_GET['id'];

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("delivery");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = __("Редактирование Заказа");
$PHPShopGUI->reload = "top";
$PHPShopGUI->ajax = "'orders','" . PHPShopDate::dataV($_REQUEST['pole1'], false) . "','" . PHPShopDate::dataV($_REQUEST['pole2'], false) . "','core'";
$PHPShopGUI->debug_close_window = false;
$PHPShopGUI->debug = false;
$PHPShopGUI->addJSFiles('/phpshop/lib/Subsys/JsHttpRequest/Js.js', '/phpshop/lib/JsHttpRequest/JsHttpRequest.js');
$PHPShopGUI->dir = $_classPath . "admpanel/";

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Списывание со склада
 */
function updateStore($data) {
    global $PHPShopSystem,$PHPShopBase,$_classPath;

    // Статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $GetOrderStatusArray = $PHPShopOrderStatusArray->getArray();

    // SMS оповещение пользователю о смене статуса заказа
    if ($data['statusi'] != $_POST['statusi_new'] and $PHPShopSystem->ifSerilizeParam('admoption.sms_status_order_enabled')) {
       
        $phone=$_POST['person']['tel_code'].$_POST['person']['tel_name'];
        $msg = strtoupper($_SERVER['SERVER_NAME']).': '.$PHPShopBase->getParam('lang.sms_user') . $data['uid'] . " - " .$GetOrderStatusArray[$_POST['statusi_new']]['name'];
         
        // Проверка на первую 7 или 8
        $first_d=substr($phone,0,1);
        if($first_d != 8)
            $phone='7'.$phone;
     
        $lib=str_replace('./phpshop/',$_classPath,$PHPShopBase->getParam('file.sms'));
        include_once $lib;
        SendSMS($msg, $phone);
    }

    // Если новый статус Аннулирован, а был статус не Новый заказ, то мы не списываем, а добавляем обратно
    if ($data['statusi'] != 0 && $_POST['statusi_new'] == 1) {
        if (is_array($data)) {
            $order = unserialize($data['orders']);
            if (is_array($order['Cart']['cart']))
                foreach ($order['Cart']['cart'] as $val) {

                    // Данные по складу
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $product_row = $PHPShopOrm->select(array('items'), array('id' => '=' . $val['id']), false, array('limit' => 1));
                    if (is_array($product_row)) {

                        // Склад
                        $product_update['items_new'] = $product_row['items'] + $val['num'];
                        $product_update['sklad_new'] = 0;
                        $product_update['enabled_new'] = 1;

                        // Обновляем данные 
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                        $PHPShopOrm->debug = false;
                        $PHPShopOrm->update($product_update, array('id' => '=' . $val['id']));
                    }
                }
        }
    } else if ($GetOrderStatusArray[$_POST['statusi_new']]['sklad_action'] == 1 and $GetOrderStatusArray[$data['statusi']]['sklad_action'] != 1) {
        if (is_array($data)) {
            $order = unserialize($data['orders']);
            if (is_array($order['Cart']['cart']))
                foreach ($order['Cart']['cart'] as $val) {

                    // Данные по складу
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $product_row = $PHPShopOrm->select(array('items'), array('id' => '=' . $val['id']), false, array('limit' => 1));
                    if (is_array($product_row)) {

                        // Склад
                        $product_update['items_new'] = $product_row['items'] - $val['num'];

                        // Списывание со склада
                        switch ($PHPShopSystem->getSerilizeParam('admoption.sklad_status')) {

                            case(3):
                                if ($product_update['items_new'] < 1) {
                                    $product_update['sklad_new'] = 1;
                                    $product_update['enabled_new'] = 1;
                                } else {
                                    $product_update['sklad_new'] = 0;
                                    $product_update['enabled_new'] = 1;
                                }
                                break;

                            case(2):
                                if ($product_update['items_new'] < 1) {
                                    $product_update['enabled_new'] = 0;
                                    $product_update['sklad_new'] = 0;
                                } else {
                                    $product_update['enabled_new'] = 1;
                                    $product_update['sklad_new'] = 0;
                                }
                                break;

                            default:
                                break;
                        }

                        // Обновляем данные 
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                        $PHPShopOrm->debug = false;
                        $PHPShopOrm->update($product_update, array('id' => '=' . $val['id']));
                    }
                }
        }
    }
}

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

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['visitorID'])));

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "700,650";
    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($_REQUEST['visitorID'], $data);

    $update_date = $PHPShopOrder->getStatusTime();
    if (!empty($update_date))
        $update_date = __(' / обработан ') . $update_date;

    // Графический заголовок окна
    $PHPShopGUI->setHeader(__('Заказ № ') . $data['uid'] . ' / ' . PHPShopDate::dataV($data['datas']) . $update_date, __("Укажите данные для записи в базу."), $PHPShopGUI->dir . "img/i_commercemanager_med[1].gif");

    // Нет данных
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "Закрыть", "center", 100, "return onCancel();", "but"));
        return true;
    }
    
        // ID окна для памяти закладок
    $PHPShopGUI->setID(__FILE__, $data['id']);

    $order = unserialize($data['orders']);
    $status = unserialize($data['status']);

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['statusi']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['statusi']);

    // Время обработки заказа
    if (empty($status['time']))
        $status['time'] = PHPShopDate::dataV($data['datas'], true, false, ' ', true);


    // Компания
    $Tab1 = $PHPShopGUI->setField(__("Компания"), $PHPShopGUI->setTextarea('person[org_name]', PHPShopSecurity::TotalClean($order['Person']['org_name']), 'none', 200), 'left');

    // Дополнительная информация по заказу
    $Tab1.=$PHPShopGUI->setField(__("Дополнительная информация"), $PHPShopGUI->setTextarea('status[maneger]', PHPShopSecurity::TotalClean($status['maneger']), 'none', '370px'), 'left') . $PHPShopGUI->setLine();

    // Адрес доставки
    $Tab1.=$PHPShopGUI->setField(__("Адрес доставки"), $PHPShopGUI->setTextarea('person[adr_name]', PHPShopSecurity::TotalClean($order['Person']['adr_name']), 'none', 200, 60), 'left');

    // ФИО покупателя
    $Tab1.=$PHPShopGUI->setField(__("Покупатель"), $PHPShopGUI->setTextarea('person[name_person]', PHPShopSecurity::TotalClean($order['Person']['name_person']), 'none', '370px', '30px') . $PHPShopGUI->setLine() .
                    $PHPShopGUI->setInputText(__("Время доставки от"), 'person[dos_ot]', PHPShopSecurity::TotalClean($order['Person']['dos_ot']), 50, false, 'left') .
                    $PHPShopGUI->setInputText(__("до"), 'person[dos_do]', PHPShopSecurity::TotalClean($order['Person']['dos_do']), 50, false, 'left'), 'left') . $PHPShopGUI->setLine();

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField(__("Состояние заказа"), $PHPShopGUI->setSelect('statusi_new', $order_status_value, 170), 'left');

    // Телефон
    $Tab1.= $PHPShopGUI->setField(__("Телефон"), $PHPShopGUI->setInputText(false, 'person[tel_code]', PHPShopSecurity::TotalClean($order['Person']['tel_code']), 50, false, 'left') .
            $PHPShopGUI->setInputText('-', 'person[tel_name]', PHPShopSecurity::TotalClean($order['Person']['tel_name']), 100, false, 'left'), 'left');

    // Доступые типы оплат
    $PHPShopPaymentArray = new PHPShopPaymentArray();
    $PaymentArray = $PHPShopPaymentArray->getArray();
    if (is_array($PaymentArray))
        foreach ($PaymentArray as $payment)
            $payment_value[] = array($payment['name'], $payment['id'], $order['Person']['order_metod']);

    // Тип оплаты
    $Tab1.= $PHPShopGUI->setField(__("Оплата"), $PHPShopGUI->setSelect('person[order_metod]', $payment_value, 200), 'left') . $PHPShopGUI->setLine();

    // Печатные бланки
    $Tab1_1 = $PHPShopGUI->loadLib('tab_print', $data);

    // Дополнительные опции
    $Tab1_2 = $PHPShopGUI->loadLib('tab_advance', $data);

    // Платежные документы
    $PHPShopInterface = new PHPShopInterface('_pretab2_');
    $PHPShopInterface->setTab(array(__("Печатные бланки"), $Tab1_1, 70), array(__("Дополнительно"), $Tab1_2, 70));
    $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopInterface->getContent(), 'float:left;padding-left:5px');

    // Корзина
    $Tab2 = $PHPShopGUI->loadLib('tab_cart', $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 350), array(__("Корзина"), $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "order_num", $data['uid'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "visitorID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "pole1", $_GET['pole1'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "pole2", $_GET['pole2'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.visitor.all") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.visitor.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.visitor.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;

    // Сохранение данных
    actionUpdate();

    $PHPShopGUI->setAction($_POST['visitorID'], 'actionStart', 'none');
}

/**
 * Оповещение пользователя о новом статусе
 * @param array $data массив данных заказа
 */
function sendUserMail($data) {
    global $PHPShopSystem;

    if ($data['statusi'] != $_POST['statusi_new']) {
        PHPShopObj::loadClass("parser");
        PHPShopObj::loadClass("mail");
        PHPShopParser::set('ouid', $data['uid']);
        PHPShopParser::set('date', PHPShopDate::dataV($data['datas']));

        // Доступные статусы заказов
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
        PHPShopParser::set('status', $PHPShopOrderStatusArray->getParam($_POST['statusi_new'] . '.name'));
        PHPShopParser::set('user', $data['user']);
        PHPShopParser::set('company', $PHPShopSystem->getParam('name'));
        $title = $PHPShopSystem->getValue('name') . ' - статус заказа ' . $data['uid'] . ' изменен';
        $order = unserialize($data['orders']);

        PHPShopParser::set('mail', $order['Person']['mail']);
        $content = PHPShopParser::file('../../lib/templates/order/status.tpl', true);
        if (!empty($content)) {
            new PHPShopMail($order['Person']['mail'], $PHPShopSystem->getValue('adminmail2'), $title, $content);
        }
    }
}

/**
 * Экшен обновления
 * @return bool 
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    // Данные по заказу
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['visitorID'])));
    $order = unserialize($data['orders']);

    // Новые данные
    if (is_array($_POST['person']))
        foreach ($_POST['person'] as $k => $v)
            $order['Person'][$k] = $v;

    // Сериализация данных заказа
    $_POST['orders_new'] = serialize($order);

    // Комментарий и время обработки
    $_POST['status']['time'] = PHPShopDate::dataV();
    $_POST['status_new'] = serialize($_POST['status']);
    $_POST['admin_new'] = $_SESSION['idPHPSHOP'];

    $PHPShopOrm->clean();

    // Списывание со склада из корзины
    updateStore($data);

    // Оповещение пользователя о новом статусе
    sendUserMail($data);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['visitorID']));
    $PHPShopOrm->clean();

    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);
    $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['visitorID'])));

    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['visitorID'], 'actionStart', 'none');

// Обработка событий
$PHPShopGUI->getAction();
?>