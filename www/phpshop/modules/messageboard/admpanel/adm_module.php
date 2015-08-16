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

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.messageboard.messageboard_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['enabled_menu_new'])) $_POST['enabled_menu_new']=0;
    if(empty($_POST['flag_new'])) $_POST['flag_new']=0;

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
    $PHPShopGUI->setHeader("Настройка модуля 'Message Board'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    if($flag==0) $s0="selected";
    else $s1="selected";
    $Select[]=array("Справа",1,$s1);
    $Select[]=array("Слева",0,$s0);


    $Tab1=$PHPShopGUI->setField("Расположение блока объявлений:",
            $PHPShopGUI->setCheckbox("enabled_new",1,"Вывод блока на сайте",$enabled).
            $PHPShopGUI->setSelect("flag_new",$Select,100,1).
            $PHPShopGUI->setCheckbox("enabled_menu_new",1,"Добавить в топ-меню ссылку",$enabled_menu),"none",5);
    $Tab1.=$PHPShopGUI->setLine();
    $Tab1.=$PHPShopGUI->setInputText(false,'num_new', $num,30,'Записей на странице');
    $Info='
     Для произвольного размещения формы вывода последних объявлений отключите опцию вывода блока на сайте и используйте переменную @lastmessageForma@
     для вставки в свой шаблон в произвольное место.
';
    $Tab2=$PHPShopGUI->setInfo($Info,250,'97%');

    // Содержание закладки 2
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

if($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();
?>