<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Редактирование Отзыва";
$PHPShopGUI->ajax = "'gbook','','','core'";
$PHPShopGUI->alax_lib = true;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopModules;

    // Выборка
    $data['datas'] = PHPShopDate::get();
    $data['tema'] = __('Отзыв от ') . $data['datas'];
    $data['name'] = __('Администратор');

    $PHPShopGUI->dir = "../";
    $PHPShopGUI->size = "630,530";
    $PHPShopGUI->addJSFiles('../java/popup_lib.js', '../java/dateselector.js');
    $PHPShopGUI->addCSSFiles('../css/dateselector.css');

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование Отзыва", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_account_properties_med[1].gif");

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('otvet_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "../templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = null;

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInput("text", "datas_new", PHPShopDate::dataV($datas, false), "left", 70) .
            $PHPShopGUI->setCalendar('datas_new') .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox('flag_new', '1', 'Вывод', true)
            , "left");

    $Tab1.=$PHPShopGUI->setField("Автор:", $PHPShopGUI->setText("Имя:&nbsp;&nbsp;", "left") .
                    $PHPShopGUI->setInput("text", "name_new", $data['name'], "none", 300) . $PHPShopGUI->setText("E-mail:", "left") . $PHPShopGUI->setInput("text", "mail_new", $mail, "none", 300), "none", 5) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Тема:", $PHPShopGUI->setTextarea("tema_new", $data['tema'], "left", '97%', '50px'), "none") .
            $PHPShopGUI->setField("Отзыв:", $PHPShopGUI->setTextarea("otsiv_new", 'Отзыв о магазине', "left", '97%', '80px'), "none");

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Ответ", $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.gbook.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['datas_new'] = time();

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