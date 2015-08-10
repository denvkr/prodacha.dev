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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sortbrand.sort_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

/**
 * Выбор характеристики
 */
function getSortValue($n) {
    global $PHPShopGUI;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
    $PHPShopOrm->debug=false;
    $data = $PHPShopOrm->select(array('*'),array('filtr'=>"='1'",'goodoption'=>"!='1'"),array('order'=>'num'),array('limit'=>100));
    if(is_array($data))
        foreach($data as $row) {

            if($n == $row['id']) $sel='selected';
            else $sel=false;

            $value[]=array($row['name'],$row['id'],$sel);
        }

    return $PHPShopGUI->setSelect('sort_new',$value,300);
}

function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Подбор по параметрам";
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

    switch($flag) {
        case 1: $s3='selected';
            break;
        case 2: $s4='selected';
            break;
    }

    $f_value[]=array('выпадающий список',1,$s3);
    $f_value[]=array('ссылки',2,$s4);




    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Подбор по параметрам'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField('Заголовок',$PHPShopGUI->setInputText(false,'title_new', $title));
    $Tab1.=$PHPShopGUI->setField('Характеристика',getSortValue($sort));
    $Tab1.=$PHPShopGUI->setField('Место вывода',$PHPShopGUI->setSelect('enabled_new',$e_value,150));
    $Tab1.=$PHPShopGUI->setField('Шаблон вывода',$PHPShopGUI->setSelect('flag_new',$f_value,150));

    $info='Для произвольной вставки элемента следует выбрать парамет вывода "Не выводить" и в ручном режиме вставить перменную
        <b>@brand@</b> в свой шаблон.
        <p>Для персонализации формы вывода отредактируйте шаблоны phpshop/modules/sortbrand/templates/</p>
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