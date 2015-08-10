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
function actionUpdate() {
    global $PHPShopOrm;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(!empty($_POST['last_execute_new'])) $_POST['used_new']=0;
    $action = $PHPShopOrm->update($_POST,array('id'=>'='.$_POST['newsID']));
    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id'=>'='.$_POST['newsID']));
    return $action;
}


// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;
    
    
    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Редактирование Задачи";
    $PHPShopGUI->size="500,450";
    
    
    // Выборка
    $data = $PHPShopOrm->select(array('*'),array('id'=>'='.$_GET['id']));
    @extract($data);
    
    
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование Задачи Cron","Укажите данные для записи в базу.",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");
    
    $Tab1=$PHPShopGUI->setField("Название задачи:",$PHPShopGUI->setInput("text","name_new",$name,"left",400));
    $Tab1.=$PHPShopGUI->setField("Запускаемый Файл:".$PHPShopGUI->setCheckbox("enabled_new",1,"Включить",$enabled),$PHPShopGUI->setInput("text","path_new",$path,"left",400).$PHPShopGUI->setLine("* phpshop/modules/cron/sample/dump.php"));
    $Tab1.=$PHPShopGUI->setCheckbox("last_execute_new",'100',"Обнулить данные последенего выполнения",0);
    $Tab1.=$PHPShopGUI->setLine();
    $Tab1.=$PHPShopGUI->setSelect('execute_day_num_new',$PHPShopGUI->setSelectValue($execute_day_num),50,false,'Количество запусков в день:');
   
    
    
    
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270));
    
    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","delID","Удалить","right",70,"","but","actionDelete").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");
    
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if($UserChek->statusPHPSHOP < 2) {

// Вывод формы при старте
    $PHPShopGUI->setAction($_GET['id'],'actionStart','none');

// Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>