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
$PHPShopGUI->title = "Редактирование Ссылки";
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
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    extract($data);

    if ($data['enabled'] == 1)
        $enabled = "checked"; else
        $enabled = "";

    $PHPShopGUI->dir = "../";
    $PHPShopGUI->size = "630,530";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование Ссылки", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_register_domain_med[1].gif");

    $Select1 = setSelectChek($num);

    // Содержание закладки 1
    $Tab1 =
            $PHPShopGUI->setField("Ресурс:", $PHPShopGUI->setTextarea("name_new", $name, "left", '97%', '30px'), "none") .
            $PHPShopGUI->setField("Позиция:", $PHPShopGUI->setSelect("num_new", $Select1, 50, 1) . $PHPShopGUI->setCheckbox("enabled_new", 1, "Показывать", $enabled), "left", 5) .
            $PHPShopGUI->setField("Ссылка:", $PHPShopGUI->setInput("text", "link_new", $link, "none", 330), "none") .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Описание:", $PHPShopGUI->setTextarea("opis_new", $opis, "left", '97%', '100px'), "none");


    $Tab2 = $PHPShopGUI->setField("Код кнопки:", $PHPShopGUI->setTextarea("image_new", $image, "left", '97%', '100px'), "none");

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Подробно", $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.links.edit") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate.links.edit");

// Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['newsID']));
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['newsID']));
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

// Обработка событий 
$PHPShopGUI->getAction();
?>
