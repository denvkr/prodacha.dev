<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Создание Нового Баннера";
$PHPShopGUI->ajax = "'banner','','','core'";
$PHPShopGUI->alax_lib = true;
$PHPShopSystem = new PHPShopSystem();

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name15']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopModules;

    $PHPShopGUI->dir = "../";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Нового Банера", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_select_another_account_med[1].gif");

    $Field1 = $PHPShopGUI->setInput("text", "name_new", "Баннер", "none", 300) .
            $PHPShopGUI->setRadio("flag_new", 1, "Показывать банер") .
            $PHPShopGUI->setRadio("flag_new", 0, "Скрыть банер", false);

    $Field2 = $PHPShopGUI->setInput("text", "limit_all_new", 10000000000, "none", 100);

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Наименование:", $Field1, "none") .
            $PHPShopGUI->setField("Привязка к странице:", $PHPShopGUI->setInput("text", "dir_new", null, "left", '550') .
                    $PHPShopGUI->setLine(__('* Пример: /,/page/,/shop/UID_1.html. Можно указать несколько адресов через запятую без пробела')), "none");


    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = '';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Содержание", $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.baner.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>



