<?
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

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_system"));


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->update($_POST);
    return true;
}

// Таблица
function listJob(){
    global $PHPShopModules,$PHPShopGUI;
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_job"));
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->size="600,500";
    $PHPShopInterface->window=true;
    $PHPShopInterface->link="./adm_cronID.php";
    $PHPShopInterface->realpath=true;
    $PHPShopInterface->imgPath=$PHPShopGUI->dir."img/";
    $PHPShopInterface->setCaption(array("&plusmn;","7%"),array("Название","60%"),array("Последний запуск","25%"));

    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'num'),array('limit'=>30));
    
    if(is_array($data))
        foreach($data as $row)
        $PHPShopInterface->setRow($row['id'],$PHPShopInterface->icon($row['enabled']),$row['name'],PHPShopDate::dataV($row['last_execute']));

    $PHPShopInterface->setAddItem('./adm_cron_new.php');
    return $PHPShopInterface->Compile();
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
    $PHPShopGUI->setHeader("Настройка модуля 'Cron'","Настройки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    // Содержание закладки 1
    $Tab1=listJob();

    // Содержание закладки 2
    $Tab2=$PHPShopGUI->setPay($serial,false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("О Модуле",$Tab2,270));

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


