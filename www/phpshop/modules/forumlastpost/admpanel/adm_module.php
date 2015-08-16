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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.forumlastpost.ipboard_system"));


// Функция обновления
function actionUpdate(){
global $PHPShopOrm;


if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;

$PHPShopOrm->debug=false;
$action = $PHPShopOrm->update($_POST);
return $action;
}




function actionStart(){
global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


$PHPShopGUI->dir=$_classPath."admpanel/";
$PHPShopGUI->title="Настройка модуля IP.Board";
$PHPShopGUI->size="500,450";


// Выборка
$data = $PHPShopOrm->select();
@extract($data);

if ($enabled==1) $enabled="checked"; else $enabled="";
if($flag==1) $s2="selected";
  else $s1="selected";


$Select[]=array("Слева",0,$s1);
$Select[]=array("Справа",1,$s2);

// Графический заголовок окна
$PHPShopGUI->setHeader("Настройка модуля 'Forum Last Post'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

// Создаем объекты для формы
$ContentField2=$PHPShopGUI->setText("Форум: ").$PHPShopGUI->setInput("text","path_new",$path,$float="left",$size=300);
$ContentField2.=$PHPShopGUI->setText("/lastpost.php");
$ContentField2.=$PHPShopGUI->setLine() ;
$ContentField2.=$PHPShopGUI->setText("Ширина: ").$PHPShopGUI->setInput("text","width_new",$width,$float="none",$size=50);
$ContentField2.=$PHPShopGUI->setText("Высота: ").$PHPShopGUI->setInput("text","height_new",$height,$float="none",$size=50);
$ContentField2.=$PHPShopGUI->setText("Заголовок: ").$PHPShopGUI->setInput("text","title_new",$title,$float="none",$size=300);
$ContentField3=$PHPShopGUI->setField("Расположение:",$PHPShopGUI->setSelect("flag_new",$Select,100,1),"left",5);
$ContentField4=$PHPShopGUI->setField("Сообщений из топиков:",$PHPShopGUI->setInput("text","num_new",$num,$float="left",$size=50),"left",5);

// Содержание закладки 1
$Tab1=$PHPShopGUI->setField($PHPShopGUI->setCheckbox("enabled_new",1,"Вывод блока на сайте",$enabled),$ContentField2).
        $ContentField3.$ContentField4;




// Содержание закладки 2
$Info='Для работы модуля требуется загрузить в корневую директорию форума файл lastpost.php и иконки оформления.
    
Файлы доступен по ссылке: http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/forumlastpost/code/
    
При включении опции "Вывод блока на сайте" информаци о последних сообщений с форума бужет автомаически добавлена в левый или правый
текстовый блок автоматически в конец списка.

Для произвольного включения формы вывода сообщений нужно снять галочку "Вывод блока на сайте" и в вставить перемнную @forumlastpost@
в нужное место шаблонов index.tpl и shop.tpl.
';
$Load=$PHPShopGUI->setInput("button","","Скачать файлы для форума","left",300,"return window.open('/phpshop/modules/forumlastpost/code/');","but");
$Tab2=$PHPShopGUI->setTextarea("",$Info,"left",450,200).$Load;

// Форма регистрации
$Tab3=$PHPShopGUI->setPay($serial,false);

// Вывод формы закладки
$PHPShopGUI->setTab(array("Основное",$Tab1,270),array("Описание",$Tab2,270),array("О Модуле",$Tab3,270));

// Вывод кнопок сохранить и выход в футер
$ContentFooter=
$PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
$PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
$PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

$PHPShopGUI->setFooter($ContentFooter);
return true;
}

if($UserChek->statusPHPSHOP < 2){

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'],'actionStart');

// Обработка событий 
$PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>