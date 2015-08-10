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
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("user");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = __("Новый Заказ");
$PHPShopGUI->reload = "top";
$PHPShopGUI->addJSFiles('/phpshop/lib/Subsys/JsHttpRequest/Js.js', '/phpshop/lib/JsHttpRequest/JsHttpRequest.js');

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Генерация номера заказа
 */
function setNum() {
    global $PHPShopBase;

    // Кол-во знаков в постфиксе заказа №_XX, по умолчанию 2
    $format = $PHPShopBase->getParam('my.order_prefix_format');
    if (empty($format))
        $format = 2;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $row = $PHPShopOrm->select(array('uid'), false, array('order' => 'id desc'), array('limit' => 1));
    $last = $row['uid'];
    $all_num = explode("-", $last);
    $ferst_num = $all_num[0];
    $order_num = $ferst_num + 1;
    $order_num = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, $format);
    return $order_num;
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

    // Создаем копию заказа
    if (!empty($_REQUEST['orderAdd'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $PHPShopOrm->debug = false;
        $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['orderAdd'])));
        $data['id'] = null;
        $order = unserialize($data['orders']);
        unset($order['Person']['discount']);
        unset($order['Cart']);
        $data['orders'] = serialize($order);
    } elseif (!empty($_REQUEST['userAdd'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
        $PHPShopOrm->debug = false;
        $user_row = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['userAdd'])));
        $data['user'] = $user_row['id'];
        $order['Person']['org_name'] = $user_row['company'];
        $order['Person']['adr_name'] = $user_row['adres'];
        $order['Person']['name_person'] = $user_row['name'];
        $order['Person']['tel_code'] = $user_row['tel_code'];
        $order['Person']['tel_name'] = $user_row['tel'];
        $order['Person']['org_inn'] = $user_row['inn'];
        $order['Person']['org_kpp'] = $user_row['kpp'];

        // Библиотека пользователей для расчета скидки
        $PHPShopUser = new PHPShopUser($user_row['id'], $user_row);
        $order['Person']['discount'] = $PHPShopUser->getDiscount();
        $data['orders'] = serialize($order);
    }

    // Данные нового заказа
    $data['datas'] = time();
    $data['uid'] = setNum();
    $data['statusi'] = 0;

    // Запись пустого заказа для получения идентификатора заказа
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $PHPShopOrm->insert($data, '');
    $_REQUEST['visitorID'] = mysql_insert_id();

    // Выборка данных по новому заказу
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['visitorID'])));


    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "700,650";
    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($_REQUEST['visitorID'], $data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader(__('Новый Заказ № ') . $data['uid'] . ' / ' . PHPShopDate::dataV(), __("Укажите данные для записи в базу."), $PHPShopGUI->dir . "img/i_commercemanager_med[1].gif");

    // Нет данных
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "Закрыть", "center", 100, "return onCancel();", "but"));
        return true;
    }

    $order = unserialize($data['orders']);
    $status = unserialize($data['status']);

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['statusi']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['statusi']);

    // Время обработки зааказа
    if (empty($status['time']))
        $status['time'] = PHPShopDate::dataV($data['datas'], true, false, ' ', true);

    // Компания
    $Tab1 = $PHPShopGUI->setField(__("Компания"), $PHPShopGUI->setTextarea('person[org_name]', $order['Person']['org_name'], 'none', 200), 'left');

    // Дополнительная информация по заказу
    $Tab1.=$PHPShopGUI->setField(__("Дополнительная информация"), $PHPShopGUI->setTextarea('status[maneger]', $status['maneger'], 'none', '370px'), 'left') . $PHPShopGUI->setLine();

    // Адрес доставки
    $Tab1.=$PHPShopGUI->setField(__("Адрес доставки"), $PHPShopGUI->setTextarea('person[adr_name]', $order['Person']['adr_name'], 'none', 200, 60), 'left');

    // ФИО покупателя
    $Tab1.=$PHPShopGUI->setField(__("Покупатель"), $PHPShopGUI->setTextarea('person[name_person]', $order['Person']['name_person'], 'none', '370px', '30px') . $PHPShopGUI->setLine() .
                    $PHPShopGUI->setInputText(__("Время доставки от"), 'person[dos_ot]', $order['Person']['dos_ot'], 50, false, 'left') .
                    $PHPShopGUI->setInputText(__("до"), 'person[dos_do]', $order['Person']['dos_do'], 50, false, 'left'), 'left') . $PHPShopGUI->setLine();

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField(__("Состояние заказа"), $PHPShopGUI->setSelect('statusi_new', $order_status_value, 170), 'left');

    // Телефон
    $Tab1.= $PHPShopGUI->setField(__("Телефон"), $PHPShopGUI->setInputText(false, 'person[tel_code]', $order['Person']['tel_code'], 50, false, 'left') .
            $PHPShopGUI->setInputText('-', 'person[tel_name]', $order['Person']['tel_name'], 100, false, 'left'), 'left');

    // Доступые типы оплат
    $PHPShopPaymentArray = new PHPShopPaymentArray();
    $PaymentArray = $PHPShopPaymentArray->getArray();
    if (is_array($PaymentArray))
        foreach ($PaymentArray as $payment)
            $payment_value[] = array($payment['name'], $payment['id'], $order['Person']['order_metod']);

    // Тип оплаты
    $Tab1.= $PHPShopGUI->setField(__("Оплата"), $PHPShopGUI->setSelect('person[order_metod]', $payment_value, 200), 'left') . $PHPShopGUI->setLine();

    // Платежные документы
    $Tab1.= $PHPShopGUI->setField(__("Документы"), $PHPShopGUI->loadLib('tab_print', $data));

    // Корзина
    $Tab2 = $PHPShopGUI->loadLib('tab_cart', $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 350), array(__("Корзина"), $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "visitorID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.visitor.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.visitor.edit");

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

        $PHPShopOrm->clean();

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
$PHPShopGUI->setLoader($_POST['visitorID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>