<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("admgui");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Создание Новости";
$PHPShopGUI->ajax = "'news','','','core'";
$PHPShopGUI->alax_lib = true;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$PHPShopSystem;

    // Выборка
    $data['datas'] = PHPShopDate::get();
    $data['zag'] = __('Новость за ') . $data['datas'];

    // ID окна для памяти закладок
    $PHPShopGUI->setID(__FILE__, $data['id']);

    //$PHPShopGUI->size = "630,530";
    $PHPShopGUI->addJSFiles('../java/popup_lib.js', '../java/dateselector.js');
    $PHPShopGUI->addCSSFiles('../css/dateselector.css');


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Новости", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_balance_med[1].gif");

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('kratko_new');
    $oFCKeditor->Height = '270';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['kratko'];
    $oFCKeditor->Mod = 'textareas';

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInput("text", "datas_new", $data['datas'], "left", 70) .
                    $PHPShopGUI->setImage("../icon/date.gif", 16, 16, 'absmiddle', "5", $style = 'float:left', $onclick = "popUpCalendar(this, product_edit.datas_new, 'dd-mm-yyyy');"), "left") .
            $PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text", "zag_new", $data['zag'], "left", 450), "none", 5);

    $Tab1.=$PHPShopGUI->setField("Анонс:", $oFCKeditor->AddGUI());


    // Редактор 2
    $oFCKeditor = new Editor('podrob_new');
    $oFCKeditor->Height = '350';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Config['EditorAreaCSS'] = $MyStyle;
    $oFCKeditor->Value = $data['podrob'];
    $oFCKeditor->Mod = 'textareas';

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 370), array("Подробно", $Tab2, 370));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.news.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['datau_new'] = time();

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
