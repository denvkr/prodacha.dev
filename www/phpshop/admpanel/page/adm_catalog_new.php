<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Создание Нового Каталога";
$PHPShopGUI->reload = "left";

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page_categories']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function Disp_cat($parent_to) {// вывод каталогов в выборе
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);

    if (PHPShopSecurity::true_num($parent_to)) {
        $data = $PHPShopOrm->select(array('name'), array('id' => '=' . $parent_to));
        if (is_array($data))
            extract($data);
        return "$name => ";
    } else
        return "";
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopModules;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    if (empty($_GET['id']))
        $_GET['id'] = 0;

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "650,630";
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Нового Каталога", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_filemanager_med[1].gif");

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '420';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "../templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = '';

    // Содержание закладки 1
    $Tab1 =
            $PHPShopGUI->setField("Название:", $PHPShopGUI->setInput("text", "name_new", 'Новый каталог', "left", 450), "none") .
            $PHPShopGUI->setField("Каталог:", $PHPShopGUI->setInput("text", "parent_name", Disp_cat($_GET['id']), "left", 450) .
                    $PHPShopGUI->setInput("hidden", "parent_to_new", $_GET['id'], "left", 450) .
                    $PHPShopGUI->setButton("Выбрать", "../icon/folder_edit.gif", "100px", "Выбрать", "none", "miniWin('" . $dot . "./page/adm_cat.php?category=" . $category . "',300,400);return false;"), "none") .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Сортировка:", $PHPShopGUI->setInput("text", "num_new", 0, "left", 100), "left");

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 450), array("Содержание", $Tab2, 450));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.page_site.create");

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