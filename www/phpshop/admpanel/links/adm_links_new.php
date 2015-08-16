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
$PHPShopGUI->title = "Создание Новой Ссылки";
$PHPShopGUI->ajax = "'links','','','core'";
$PHPShopGUI->alax_lib = true;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['links']);

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
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->dir = "../";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Новой Ссылки", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_register_domain_med[1].gif");

    $Select1 = setSelectChek($num);

    // Содержание закладки 1
    $Tab1 =
            $PHPShopGUI->setField("Ресурс:", $PHPShopGUI->setTextarea("name_new", $name, "left", '97%', '30px'), "none") .
            $PHPShopGUI->setField("Позиция:", $PHPShopGUI->setSelect("num_new", $Select1, 50, 1) . $PHPShopGUI->setRadio("enabled_new", 1, "Показывать", "checked", "left") . $PHPShopGUI->setRadio("enabled_new", 0, "Скрыть", false), "left", 5) .
            $PHPShopGUI->setField("Ссылка:", $PHPShopGUI->setInput("text", "link_new", '', "none", 330), "none") .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Описание:", $PHPShopGUI->setTextarea("opis_new", '', "left", '97%', '100px'), "none");


    $Tab2 = $PHPShopGUI->setField("Код кнопки:", $PHPShopGUI->setTextarea("image_new", '', "left", '97%', '100px'), "none");

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Подробно", $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.links.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    if (!empty($_POST['otsiv_new']))
        $_POST['flag_new'] = 1;
    $action = $PHPShopOrm->insert($_POST);
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий 
$PHPShopGUI->getAction();
?>