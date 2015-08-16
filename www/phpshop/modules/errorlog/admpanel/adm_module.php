<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->reload='none';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.errorlog.errorlog_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля";
    $PHPShopGUI->size="500,450";


    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Error Log'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    switch($enabled) {
        case 0: $enabled_chek_0='selected';
            break;
        case 1: $enabled_chek_1='selected';
            break;
        default: $enabled_chek_2='selected';
        
    }
    
    $option[]=array('Блокировать вывод всех ошибок',0,$enabled_chek_0);
    $option[]=array('Показывать ошибки',1,$enabled_chek_1);
    $option[]=array('Показывать ошибки и отладки',2,$enabled_chek_2);
    $Tab1=$PHPShopGUI->setSelect('enabled_new',$option,200,$float="none",$caption='Уровень ошибок');

    $Info='
Для внесения пользовательской отладочной информации в общий лог необходимо указать следующий код в месте отладки своей функции:

trigger_error("Текст отладки", E_USER_NOTICE);


';
    $Tab2=$PHPShopGUI->setTextarea("",$Info,"left",'98%',250);

    // Содержание закладки 2
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


