<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Настройка реквизитов";
$PHPShopGUI->reload = "none";

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();

    // ID окна для памяти закладок
    $PHPShopGUI->setID(__FILE__, $data['id']);

    // Реквизиты
    $bank = unserialize($data['bank']);

    $PHPShopGUI->dir = "../";
    $PHPShopGUI->size = "500,600";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка реквизитов", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_website_statistics_med[1].gif");


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField(__("Название магазина"), $PHPShopGUI->setInputText(null, "name_new", $data['name'], '97%'), "none");
    $Tab1 .= $PHPShopGUI->setField(__("Владелец"), $PHPShopGUI->setInputText(null, "company_new", $data['company'], '97%'), "none");
    $Tab1 .= $PHPShopGUI->setField(__("Телефоны"), $PHPShopGUI->setInputText(null, "tel_new", $data['tel'], '97%'), "none");
    $Tab1 .= $PHPShopGUI->setField(__("Почта для заказов"), $PHPShopGUI->setInputText(null, "adminmail2_new", $data['adminmail2'], '97%', __('<br>* можно указать несколько адресов через запятую')), "none");

    // Содержание закладки 2
    $Tab2 .= $PHPShopGUI->setField(__("Наименование организации"), $PHPShopGUI->setInputText(null, "bank_new[org_name]", $bank['org_name'], '97%'), "none");
    $Tab2 .= $PHPShopGUI->setField(__("Юридический адрес"), $PHPShopGUI->setInputText(null, "bank_new[org_ur_adres]", $bank['org_ur_adres'], '97%'), "none");
    $Tab2 .= $PHPShopGUI->setField(__("Фактический адрес"), $PHPShopGUI->setInputText(null, "bank_new[org_adres]", $bank['org_adres'], '97%'), "none");
    $Tab2 .= $PHPShopGUI->setField(__("ИНН"), $PHPShopGUI->setInputText(null, "bank_new[org_inn]", $bank['org_inn'], '97%'), "left");
    $Tab2 .= $PHPShopGUI->setField(__("КПП"), $PHPShopGUI->setInputText(null, "bank_new[org_kpp]", $bank['org_kpp'], '97%'), "left");
    $Tab2 .= $PHPShopGUI->setField(__("№ Счета организации"), $PHPShopGUI->setInputText(null, "bank_new[org_schet]", $bank['org_schet'], '97%'), "none");
    $Tab2 .= $PHPShopGUI->setField(__("Наименование банк"), $PHPShopGUI->setInputText(null, "bank_new[org_bank]", $bank['org_bank'], '97%'), "none");
    $Tab2 .= $PHPShopGUI->setField(__("БИК"), $PHPShopGUI->setInputText(null, "bank_new[org_bic]", $bank['org_bic'], '150'), "left");
    $Tab2 .= $PHPShopGUI->setField(__("№ Счета банка"), $PHPShopGUI->setInputText(null, "bank_new[org_bank_schet]", $bank['org_bank_schet'], '250'), "left");

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 330), array("Реквизиты", $Tab2, 330));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "thisID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.option.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.option.edit");

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

    $PHPShopGUI->setAction($_POST['thisID'], 'actionStart', 'none');
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['bank_new'] = serialize($_POST['bank_new']);

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['thisID']));
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['thisID'], 'actionStart');

// Обработка событий 
$PHPShopGUI->getAction();
?>