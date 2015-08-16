<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->reload="parent";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));


// Функция обновления
function actionInsert() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->insert($_POST);
    return $action;
}



// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Создание Новой Задачи";
    $PHPShopGUI->size="500,450";


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Новой Задачи Cron","Укажите данные для записи в базу.",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField("Название задачи:",$PHPShopGUI->setInput("text","name_new",'Новая задача',"left",400));
    $Tab1.=$PHPShopGUI->setField("Запускаемый Файл:".$PHPShopGUI->setCheckbox("enabled_new",1,"Включить",1),$PHPShopGUI->setInput("text","path_new",false,"left",400).$PHPShopGUI->setLine("* phpshop/modules/cron/sample/dump.php"));
    $Tab1.=$PHPShopGUI->setSelect('execute_day_num_new',$PHPShopGUI->setSelectValue(false),50,1,'Количество запусков в день:');
    
    

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionInsert");

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