<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.wiswigload.wiswigload_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;
    
    
    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";
    
    
    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'WISWIG Load'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
     
    $Info='
     Для возможности загрузки новых визуальных редакторов необходимо проставить права CHMOD 775 на папку /phpshop/admpanel/editors
     При использовании программного комплекса PHPShop EasyControl "Мой сайт" или любого другого виртуального сервера для ОС Windows
     права CHMOD на файлы проставлять не нужно.
     ';
     
    $Tab1.=$PHPShopGUI->setLine('<br>');
    $Tab1.=$PHPShopGUI->setInfo($Info,150,'97%');
    $Tab2=$PHPShopGUI->setPay($serial,false);

    $Lib='В модуле использованы открытые библиотеки:
<p>
<a href="http://http://www.tinymce.com/" target="_blank">TinyMCE</a>, Copyright (C) Moxiecode Systems AB.<br>
<a href="http://http://www.fckeditor.net/" target="_blank">FCKeditor</a>, Copyright (C) Frederico Caldeira Knabben<br>
</p>
        ';
    $Tab2.=$PHPShopGUI->setInfo($Lib,70,'95%');
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Описание",$Tab1,270),array("О Модуле",$Tab2,270));
    
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Закрыть","right",70,"return onCancel();","but");
    
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if($UserChek->statusPHPSHOP < 2) {
    
    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');
    
    // Обработка событий 
    $PHPShopGUI->getAction();
    
}else $UserChek->BadUserFormaWindow();

?>


