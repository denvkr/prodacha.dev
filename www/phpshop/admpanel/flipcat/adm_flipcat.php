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
$PHPShopGUI->form_enabled = false;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$PHPShopSystem;

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание мобильной версии сайта", "Укажите данные для регистрации.", $PHPShopGUI->dir . "img/flipcat.png");

    
    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("E-mail:", $PHPShopGUI->setInput("text", "username", $PHPShopSystem->getParam('adminmail2'), "left", 300).
            $PHPShopGUI->setInput("hidden", "partner", 'QKIuH48U', "left", 10).
            $PHPShopGUI->setInput("hidden", "company_url", $_SERVER['SERVER_NAME'], "left", 10).
            $PHPShopGUI->setInput("hidden", "logotype", $PHPShopSystem->getParam('logo'), "none", 10).
            $PHPShopGUI->setInput("hidden", "description", $PHPShopSystem->getParam('name'), "none", 10).
            $PHPShopGUI->setInput("hidden", "yandex_yml", "http://".$_SERVER['SERVER_NAME']."/yml/yandex.php", "none", 10)
            );
    
    $Tab1 .= $PHPShopGUI->setField("Организация:", $PHPShopGUI->setInput("text", "company_name", $PHPShopSystem->getParam('company'), "none", 300));
    $Tab1 .= $PHPShopGUI->setField("Телефон:", $PHPShopGUI->setInput("text", "contact_phone", $PHPShopSystem->getParam('tel'), "none", 300));

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Регистрация", $PHPShopGUI->setForm($Tab1, 'http://flipcat.ru/user/register','flipcatreg',null,'_blank') , 180));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "", "Регистрация", "right", 100, "document.forms.flipcatreg.submit();", "but");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>
