<?
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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.edit.edit_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;
    
    
    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";
    
    
    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Edit'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
     
    $Tab1.=$PHPShopGUI->setField('CHMOD',$PHPShopGUI->setInputText(false, 'chmod_new', $chmod,100,'* Права за запись файлов в формате 0775'));
    $Info='
     Для возможности редактирования файлов шаблонов необходимо проставить права CHMOD 775 на требуемые файлы в папке /phpshop/templates/
     При использовании программного комплекса PHPShop EasyControl "Мой сайт" или любого другого виртуального сервера для ОС Windows
     права CHMOD на файлы проставлять не нужно.
     <p>
     Инструкция с иллюстрациями по смене прав на файлы: <a href="http://www.phpshop.ru/gbook/ID_476.html" target="_blank">
     http://www.phpshop.ru/gbook/ID_476.html</a>
     </p>
     Шаблоны можно скачать в ручном режиме из <a href="http://template.phpshop.ru/templates/" target="_blank">http://template.phpshop.ru</a>.
     Архив с шаблоном нужно распаковать в директорию /phpshop/templates/имя шаблона';
     
    $Tab1.=$PHPShopGUI->setLine('<br>');
    $Tab1.=$PHPShopGUI->setInfo($Info,150,'97%');
    $Tab2=$PHPShopGUI->setPay($serial,false);

    $Lib='В модуле использована открытая библиотека <a href="http://codemirror.net/" target="_blank">Сodemirror</a><br>
        Copyright (C) 2011 by Marijn Haverbeke.';
    $Tab2.=$PHPShopGUI->setInfo($Lib,50,'95%');
    
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


