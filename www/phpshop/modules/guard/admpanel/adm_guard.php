<?

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

PHPShopObj::loadClass("orm");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI($reload='none');
$PHPShopGUI->title="Настройки антивируса";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.guard.guard_system"));


function setSelectValue($n) {
    $i=1;
    while($i<=10) {
        if($n==$i) $s="selected"; else $s="";
        $select[]=array($i,$i,$s);
        $i++;
    }
    return $select;
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    extract($data);

    if($flag==1) $fl="checked";
    else $fl2="checked";

    if($element==0) $s1="selected";
    else $s2="selected";

    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->size="630,530";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройки антивируса","Укажите данные для записи в базу.","../install/shield_green.png");


    // Содержание закладки 1
    $Tab1=$PHPShopGUI->setField($PHPShopGUI->setCheckbox("enabled_new",1,"Автоматическая проверка файлов",$enabled),
            $PHPShopGUI->setSelect('chek_day_num_new',setSelectValue($chek_day_num),50,false,'Количество проверок в день: ').
            $PHPShopGUI->setCheckbox("mode_new",1,"Расширенный режим проверки файлов",$mode)
            );
    $Tab1.=$PHPShopGUI->setField('Уведомления',
            $PHPShopGUI->setCheckbox("stop_new",1,"Блокировка сайта при обнаружении вируса",$stop).$PHPShopGUI->setLine().
            $PHPShopGUI->setCheckbox("mail_enabled_new",1,"Уведомление администратора по E-mail",$mail_enabled)
            ,false,false,10);


    // Таблица
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->size="600,500";
    $PHPShopInterface->window=true;
    $PHPShopInterface->idRows='p';
    $PHPShopInterface->imgPath=$PHPShopGUI->dir."img/";
    $PHPShopInterface->setCaption(array("Название","40%"),array("Дата","30%"),array('Действия','30%'));

    // Проверка дат событий
    if((date('U')-$last_chek)<(86400*3)) $flag_chek=$PHPShopGUI->setImage('icon/icon-activate.gif', 19, 15);
    else $flag_chek=$PHPShopGUI->setImage('icon/error.gif', 15, 15);

    if((date('U')-$last_update)<(86400*5)) $flag_update=$PHPShopGUI->setImage('icon/icon-activate.gif', 19, 15);
    else $flag_update=$PHPShopGUI->setImage('icon/error.gif', 15, 15);

    if((date('U')-$last_crc)<(86400*3)) $flag_crc=$PHPShopGUI->setImage('icon/icon-activate.gif', 19, 15);
    else $flag_crc=$PHPShopGUI->setImage('icon/error.gif', 15, 15);

    $PHPShopInterface->setRow(1,'Последняя проверка файлов',$flag_chek.PHPShopDate::dataV($last_chek),$PHPShopGUI->setButton('Проверить','icon/accept.png',130,25,
            false, "miniWin('http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/phpshop/modules/guard/admin.php?do=chek',500,500)"));
    $PHPShopInterface->setRow(2,'Последнее обновление сигнатур',$flag_update.PHPShopDate::dataV($last_update),$PHPShopGUI->setButton('Обновить','icon/add.png',130,25,
            false, "miniWin('http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/phpshop/modules/guard/admin.php?do=update',500,500)"));
    $PHPShopInterface->setRow(2,'Файловая база',$flag_crc.PHPShopDate::dataV($last_crc),$PHPShopGUI->setButton('Пересчитать','icon/database_refresh.png',130,25,
            false, "miniWin('http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/phpshop/modules/guard/admin.php?do=create',500,500)"));

    $Tab1.=$PHPShopInterface->Compile();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,300));



    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Отмена","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","ОК","right",70,"","but","actionUpdate");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if(empty($_POST['mode_new'])) $_POST['mode_new']=0;
    if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;
    if(empty($_POST['stop_new'])) $_POST['stop_new']=0;
    if(empty($_POST['mail_enabled_new'])) $_POST['mail_enabled_new']=0;

    $action = $PHPShopOrm->update($_POST,array('id'=>'='.$_POST['newsID']));
    return $action;
}

if(CheckedRules($UserStatus["option"],1) == 1){

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();
?>