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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sape.sape_system"));


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
$PHPShopGUI->title="Настройка модуля Sape";
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
$PHPShopGUI->setHeader("Настройка модуля 'Sape'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

// Создаем объекты для формы
$ContentField2=$PHPShopGUI->setText("Sape ID: ").$PHPShopGUI->setInput("text","sape_user_new",$sape_user,$float="none",$size=315);
$ContentField2.=$PHPShopGUI->setText("Заголовок: ").$PHPShopGUI->setInput("text","title_new",$title,$float="none",$size=300);
$ContentField3=$PHPShopGUI->setField("Расположение:",$PHPShopGUI->setSelect("flag_new",$Select,100,1),"left",5);
$ContentField4=$PHPShopGUI->setField("Кол-во ссылок:",$PHPShopGUI->setInput("text","num_new",$num,$float="left",$size=50),"left",5);

// Содержание закладки 1
$Tab1=$PHPShopGUI->setField($PHPShopGUI->setCheckbox("enabled_new",1,"Вывод блока на сайте",$enabled),$ContentField2).
        $ContentField3.$ContentField4;




// Содержание закладки 2
$Info='Для работы модуля требуется загрузить в корневую директорию форума папку 4cb48833f491686a2500f80310e072da.
Папку переименуйте в свой уникальный SAPE USER номер и проставьте для права на запись CHMOD 777.
Файлы доступны по ссылке: http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/sape/code/
    
При включении опции "Вывод блока на сайте" Sape ссылки будут автоматически добавлены в левый или правый текстовый блок  в конец списка.

Для произвольного включения формы вывода ссылок нужно снять галочку "Вывод блока на сайте" и в вставить переменную @sape@
в нужное место шаблонов index.tpl и shop.tpl.

Для размещения в текстовых блоках черех админ-панель следует отключить визуальный редактор через настройку системы, создать новый тестовый блок, отключить опцию "вывод блока на сайте" и вставить код:

вариант 1
@php echo $GLOBALS["SysValue"]["other"]["sape"]; php@

вариант 2
@php
if (defined("_SAPE_USER")) {
$PHPShopSapeElement = &new PHPShopSapeElement();
$PHPShopSapeElement->links(4);
} else echo "<b>Вывод ссылок не работает!</b><br>Модуль Sape не установлен!";
php@
// где 4 - кол-во ссылок для вывода
';
$Load=$PHPShopGUI->setInput("button","","Скачать файлы Sape","left",300,"return window.open('/phpshop/modules/sape/code/');","but");
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