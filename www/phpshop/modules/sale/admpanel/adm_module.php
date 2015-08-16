<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sale.sale_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $price = ($price + (($price * $LoadItems['System']['percent']) / 100));
    $pr = $_POST['mod_sale_price'] / 100;

    if (empty($_POST['mod_sale_old']))
        $_POST['mod_sale_old'] = '0';

    $PHPShopOrm->sql = 'update ' . $GLOBALS['SysValue']['base']['products'] . ' set 
    price_n=' . $_POST['mod_sale_old'] . ',
    price=price' . $_POST['mod_sale_opt'] . '(price*' . $pr . ')'; 
    $action=$PHPShopOrm->update();

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля Распродажа";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Распродажа'", "Настройки подключения", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $sel_value[] = array('+', '+', false);
    $sel_value[] = array('-', '-', 'selected');
    $sel = $PHPShopGUI->setSelect('mod_sale_opt', $sel_value, 40);
    $Tab1 = $PHPShopGUI->setField('Новая цена', $PHPShopGUI->setInputText('Поменять цену у всех товаров на ' . $sel, 'mod_sale_price', "10", '30', '%'));

    $sel_value2[] = array('Обнулить', 0, false);
    $sel_value2[] = array('Присвоить значение розничной цены до изменения', 'price', 'selected');

    $Tab1.=$PHPShopGUI->setField('Старая цена', $PHPShopGUI->setSelect('mod_sale_old', $sel_value2, 300));

 $Info = '
Для расчета скидки конкретного каталога используйте закладку "Распродажа" в карточке редактирования требуемого каталога товаров.
<p>Для снятие скидки выберите параметр сложить (+) и опцию "Обнулить" старые цены.
';
    $Tab1.= $PHPShopGUI->setInfo($Info,50,'95%');
    
    $Tab2 = $PHPShopGUI->setPay($serial, false, $version, true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("О Модуле", $Tab2, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();
}else
    $UserChek->BadUserFormaWindow();
?>