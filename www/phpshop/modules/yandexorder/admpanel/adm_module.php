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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexorder.yandexorder_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}




function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Яндекс Быстрый Заказ";
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
    $PHPShopGUI->setHeader("Настройка модуля 'Яндекс Быстрый Заказ'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

// Содержание закладки
    $Info='Для работы модуля требуется зарегистрировать свой магазин в программе "Быстрый Заказ", для этого перейдите по ссылке
        <a href="http://partner.market.yandex.ru/delivery-registration.xml" target="_blank">
        http://partner.market.yandex.ru/delivery-registration.xml</a> и укажите в качестве имени сайта адрес
        <p><b>http://'.$_SERVER['SERVER_NAME'].$SysValue['dir']['dir'].'/order/</b></p>
';
    $Tab1=$PHPShopGUI->setInfo($Info,false,'96%');
    $Tab1.=$PHPShopGUI->setLine('<br>');
    $Tab1.=$PHPShopGUI->setField('Иконка:',$PHPShopGUI->setInputText(false,'img_new',$img));

// Форма регистрации
    $Tab2=$PHPShopGUI->setPay($serial,false);


// Вывод формы закладки
    $PHPShopGUI->setTab(array("Описание",$Tab1,270),array("О Модуле",$Tab2,270));

// Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

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