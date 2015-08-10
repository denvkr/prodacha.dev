<?
$_classPath="../../../";
$_templPath="http://template.phpshop.ru/";
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
PHPShopObj::loadClass("xml");

// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name3']);
$my_skin=array();


// база шаблонов
function GetTemplateInfo() {
    global $PHPShopGUI,$my_skin,$_templPath;
    $path=$_templPath;
    if(function_exists("xml_parser_create")) {
        if(@$db=readDatabase($path.'template.php',"template"))

            foreach($db as $template) {
                if(empty($my_skin[$template['name']]))
                    $value[]=array($template['name'],$template['name'],null);
            }
    }

    return $PHPShopGUI->setSelect('template_load',$value,270,'left',false,
            $onchange="document.getElementById('buttonLoad').style.display='block';GetTemplateIcon(this.value,'".$path."','template','')",200,5);
}



// Выбор иконки шаблона
function GetSkinsIcon($skin,$id='icon',$path=null) {
    global $_classPath;
    $dir=$_classPath."templates";
    $filename=$dir.'/'.$skin.'/icon/icon.gif';
    if (file_exists($filename))
        $disp='<img src="'.$filename.'" alt="'.$skin.'" width="150" height="120" border="1" id="'.$id.'">';
    else $disp='<img src="'.$_classPath.'admpanel/img/icon_non.gif" alt="" width="150" height="120" border="1" id="'.$id.'">';
    return '<div align="center" style="padding:5px"><a href="javascript:CheckTemplateIcon(\''.$path.'\',\''.$id.'\');" title="Посмотреть">'.$disp.'</a></div>';
}

// Выбор шаблона
function GetSkins($skin) {
    global $PHPShopGUI,$_classPath,$my_skin;
    $dir=$_classPath."templates";
    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                if($skin == $file) {
                    $sel="selected";
                    $is_system=' (используется системой)';
                }
                else {
                    $sel=null;
                    $is_system=null;
                }

                if($file!="." and $file!=".." and $file!="index.html") {
                    $value[]=array($file.$is_system,$file,$sel);
                    $my_skin[$file]='ready';
                }
            }
            closedir($dh);
        }
    }
    return $PHPShopGUI->setSelect('skin_new',$value,270,'left',false,$onchange="GetTemplateIcon(this.value,'".$_classPath."','icon','icon/');",200,5);
}



function actionUpdate() {
    global $PHPShopGUI,$_classPath,$_templPath,$SysValue;

    // Проверка на обман пути
    if(strlen($_POST['template_load'])<20)
        $load=$_templPath.'templates/'.$_POST['template_load'].'/'.$_POST['template_load'].'.zip';
    else $load=null;

    // Включаем таймер
    $time=explode(' ', microtime());
    $start_time=$time[1]+$time[0];

    $Content=file_get_contents($load);
    if(!empty($Content)) {
        $zip=$_SERVER['DOCUMENT_ROOT'].$SysValue['dir']['dir']."/UserFiles/Image/".$_POST['template_load'].'.zip';
        $handle = fopen($zip, "w+");
        fwrite($handle, $Content);
        fclose($handle);
        if(is_file($zip)) {

            // Попытка изменить атрибуты
            @chmod($_classPath."templates", 0775);

            // Библиотека ZIP
            include($_classPath."lib/zip/pclzip.lib.php");
            $archive = new PclZip($zip);
            if($archive->extract(PCLZIP_OPT_PATH, $_classPath."templates/".$_POST['template_load'])) {

                unlink($zip);

                // Выключаем таймер
                $time=explode(' ', microtime());
                $seconds=($time[1]+$time[0]-$start_time);
                $seconds=substr($seconds,0,6);

                $_SESSION['mod_edit_mes']='Шаблон <b>'.$_POST['template_load'].'</b> загружен за '.$seconds.' сек.';

            } else 'Ошибка распаковки файла '.$_POST['template_load'].'.zip, нет прав записи в папку phpshop/templates/';
        } else 'Ошибка записи файла '.$_POST['template_load'].'.zip, нет прав записи в папку /UserFiles/Image/';
    }
    else {
        $_SESSION['mod_edit_mes']='Ошибка чтения файла '.$_POST['template_load'].'.zip';
    }

    unset($_POST['editID']);
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');
    $PHPShopGUI->getAction();
}


function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Загрузка шаблонов";
    $PHPShopGUI->addJSFiles('edit.js');

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Загрузка шаблонов","База доступных шаблонов для загрузки",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    // Содержание закладки 2
    $Tab2=GetSkins($skin);
    $Tab2.=$PHPShopGUI->setField('Скриншот',GetSkinsIcon($skin),$float="none",$margin_left=5);

    // Содержание закладки 1
    $Tab1=GetTemplateInfo();
    $Tab1.=$PHPShopGUI->setField('Скриншот',GetSkinsIcon(false,'template','http://demo.phpshop.ru'),$float="none",$margin_left=5);
    $button=$PHPShopGUI->setInput("submit","editID","Загрузить шаблон","right",190,"","but","actionUpdate");
    $Tab1.=$PHPShopGUI->setDiv('left',$button,'display:none;','buttonLoad');
    $Tab1.=$PHPShopGUI->setLine();

    if(!empty($_SESSION['mod_edit_mes'])) {
        $Tab1.=$PHPShopGUI->setInfo($PHPShopGUI->setImage('../img/lightbulb.png',16,16).$_SESSION['mod_edit_mes'],false,'95%');
        unset($_SESSION['mod_edit_mes']);
    }



    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Загрузить",$Tab1,270),array("Установленные",$Tab2,270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","Закрыть","right",70,"return onCancel();","but");

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


