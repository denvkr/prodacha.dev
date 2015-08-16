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
$PHPShopGUI->title = "Создание Текстового Блока";
$PHPShopGUI->ajax = "'menu','','','core'";
$PHPShopGUI->alax_lib = true;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name14']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Заполняем выбор
function setSelectChek($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected"; else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopModules;

    $PHPShopGUI->dir = "../";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Текстового Блока", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_select_another_account_med[1].gif");

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = '';

    $Select1 = setSelectChek(1);

    $Select2[] = array("Слева", 0, "");
    $Select2[] = array("Справа", 1, "");

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Название:", $PHPShopGUI->setInput("text", "name_new", $name, "none", 300) . $PHPShopGUI->setRadio("flag_new", 1, "Показывать", "checked", "left") . $PHPShopGUI->setRadio("flag_new", 0, "Скрыть", ""), "left") .
            $PHPShopGUI->setField("Позиция:", $PHPShopGUI->setSelect("num_new", $Select1, 50, 1), "left", 5) .
            $PHPShopGUI->setField("Расположение:", $PHPShopGUI->setSelect("element_new", $Select2, 100, 1), "none", 5) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Привязка к странице:", $PHPShopGUI->setInput("text", "dir_new", $dir, "left", 500) .
                    $PHPShopGUI->setLine(__('* Пример: /page/,/news/. Можно указать несколько адресов через запятую без пробела')), "none");

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
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.page_menu.create");

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