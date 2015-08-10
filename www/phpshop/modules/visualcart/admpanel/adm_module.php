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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.visualcart.visualcart_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['memory_new'])) $_POST['memory_new']=0;
    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Визульная корзина";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);
    
    // Вывод
    switch($enabled) {
        case 0: $s0='selected';
            break;
        case 1: $s1='selected';
            break;
        case 2: $s2='selected';
            break;
    }

    $e_value[]=array('не выводить',0,$s0);
    $e_value[]=array('слева',1,$s1);
    $e_value[]=array('справа',2,$s2);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Визуальная корзина'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField('Заголовок блока',$PHPShopGUI->setInputText(false,'title_new', $title));
    $Tab1.=$PHPShopGUI->setField('Память корзины',$PHPShopGUI->setCheckbox('memory_new',1,'Хранить незаконченные корзины в базе',$memory));
    $Tab1.=$PHPShopGUI->setField('Место вывода',$PHPShopGUI->setSelect('enabled_new',$e_value,150));
    $Tab1.=$PHPShopGUI->setField('Ширина иконки товара',$PHPShopGUI->setInputText(false,'pic_width_new',$pic_width,30,'px'));

    $info='Для произвольной вставки элемента следует выбрать парамет вывода "Не выводить" и в ручном режиме вставить переменную
        <b>@visualcart@</b> в свой шаблон. Или через панель управления создайте текстовый блок, переключитесь в режим исходного кода (Система - Настройка - Режимы - Визуальный редактор),
        внесите метку @visualcart@ - теперь блок будет выводить корзину в нужном вам месте.
        <p>Для персонализации формы вывода отредактируйте шаблоны phpshop/modules/visualcart/templates/</p>
';

    $Tab2=$PHPShopGUI->setInfo($info, 200, '96%');

    // Форма регистрации
    $Tab3=$PHPShopGUI->setPay($serial,false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("Инструкция",$Tab2,270),array("О Модуле",$Tab3,270));

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