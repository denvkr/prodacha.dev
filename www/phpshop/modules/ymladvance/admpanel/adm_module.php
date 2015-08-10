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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ymladvance.ymladvance_system"));

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля расширенной выгрузки в Яндекс.Маркет";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    $vendor=unserialize($data['vendor']);

    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Яндекс.Маркет'", "Настройки подключения", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");


    $Tab1 = $PHPShopGUI->setField('Характеристика A', $PHPShopGUI->setInputText('Имя тега', 'sort1_tag', $vendor['sort1_tag'], 180, false, 'left') .
            $PHPShopGUI->setInputText('Имя характеристики', 'sort1_name',  $vendor['sort1_name'], 180, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Характеристика B', $PHPShopGUI->setInputText('Имя тега', 'sort2_tag', $vendor['sort2_tag'], 180, false, 'left') .
            $PHPShopGUI->setInputText('Имя характеристики', 'sort2_name', $vendor['sort2_name'], 180, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Характеристика C', $PHPShopGUI->setInputText('Имя тега', 'sort3_tag', $vendor['sort3_tag'], 180, false, 'left') .
            $PHPShopGUI->setInputText('Имя характеристики', 'sort3_name', $vendor['sort3_name'], 180, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Характеристика D', $PHPShopGUI->setInputText('Имя тега', 'sort4_tag', $vendor['sort4_tag'], 180, false, 'left') .
            $PHPShopGUI->setInputText('Имя характеристики', 'sort4_name', $vendor['sort4_name'], 180, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Гарантия от производителя', $PHPShopGUI->setCheckbox('warranty_enabled_new', 1, __('Добавить тег гарантии manufacturer_warranty'), $warranty_enabled),'left');

    $Info = '
Модуль позволяет добавить специфические теги для отображения товарных позиций согласно рекомендациям по классификациям размещения товаров в Яндексе.
Например, для категории "Одежда и обувь" существует спецификация "<a href="http://help.yandex.ru/partnermarket/?id=1124379#4" target="_blank">Яндекс Гардероб</a>".
<p>
Для включения тегов производителя(Бренда) следует указать в поле Имя тега <b>vendor</b>, а в поле Имя характеристики указать 
имя существующей характеристики в системе (Производитель или свое название). Характеристика должна быть активной и заполненной у всех товаров.
</p>
<p>
Для включения тегов размера следует указать в поле Имя тега <b>param name="Цвет"</b>, а в поле Имя характеристики указать 
имя существующей характеристики в системе (Цвет или свое название). Характеристика должна быть активной и заполненной у всех товаров.
</p>
<p>
Модуль позволяет вывести дополнительные настройки в файл выгрузки для Яндекс.Маркета. Добавляет в файл 
http://' . $_SERVER['SERVER_NAME'] . '/yml/yandex.php тег наличия гарантии от производителя <b>manufacturer_warranty</b>.
</p>';
    $Tab2 = $PHPShopGUI->setInfo($Info, 200, '95%');

    $Tab3 = $PHPShopGUI->setPay($serial, false, $version, true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270),array("Инструкция", $Tab2, 270), array("О Модуле", $Tab3, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $vendor = array(
        'sort1_tag' => $_POST['sort1_tag'],
        'sort1_name' => $_POST['sort1_name'],
        'sort2_tag' => $_POST['sort2_tag'],
        'sort2_name' => $_POST['sort2_name'],
        'sort3_tag' => $_POST['sort3_tag'],
        'sort3_name' =>$_POST['sort3_name'],
        'sort4_tag' => $_POST['sort4_tag'],
        'sort4_name' => $_POST['sort4_name'],
    );

    $_POST['vendor_new'] = serialize($vendor);
    
    if(empty($_POST['warranty_enabled_new'])) $_POST['warranty_enabled_new']=0;


    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();
}else
    $UserChek->BadUserFormaWindow();
?>