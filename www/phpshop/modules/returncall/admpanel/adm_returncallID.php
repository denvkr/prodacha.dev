<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->debug_close_window = false;
$PHPShopGUI->reload = 'top';
$PHPShopGUI->ajax = "'modules','returncall'";
$PHPShopGUI->includeJava = '<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir = $_classPath . "admpanel/";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.returncall.returncall_jurnal"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['newsID']));
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Редактирование звонка";
    $PHPShopGUI->size = "630,450";


    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $_GET['id']));
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader('Редактирование звонка от "' . $name . '" номер ' . $tel, "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");



    $Tab1 = $PHPShopGUI->setField('Заказ звонка: ' . PHPShopDate::dataV($date), $PHPShopGUI->setInputText('Имя: ', 'name_new', $name, '300', false, 'left') .
            $PHPShopGUI->setInputText('№: ', 'tel_new', $tel, '200', false, 'left') .
            $PHPShopGUI->setInputText('Время: ', 'time_start_new', $time_start, '50', false, 'left') .
            $PHPShopGUI->setInputText('до', 'time_end__new', $time_end, '50', false, 'left').
             $PHPShopGUI->setText('IP: '.$ip));

    $Tab1.=$PHPShopGUI->setField('Сообщение', $PHPShopGUI->setTextarea('message_new', $message));

    $status_atrray[] = array('Новая', 1, $status);
    $status_atrray[] = array('Просили перезвонить', 2, $status);
    $status_atrray[] = array('Недоcтупен', 3, $status);
    $status_atrray[] = array('Выполнен', 4, $status);

    $Tab1.=$PHPShopGUI->setField('Статус', $PHPShopGUI->setSelect('status_new', $status_atrray, 150));


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "delID", "Удалить", "right", 70, "", "but", "actionDelete") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['newsID']));
    return $action;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

    // Обработка событий
    $PHPShopGUI->getAction();
}else
    $UserChek->BadUserFormaWindow();
?>


