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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.chat.chat_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    
    // Обязательное заполнение / в конце директории
    if(substr($_POST['upload_dir_new'], -1) != '/')
           $_POST['upload_dir_new'].='/';
    
    // Попытка проставить права 777 на папку для файлов
    @chmod($_SERVER['DOCUMENT_ROOT'] .$GLOBALS['SysValue']['dir']['dir'].'/UserFiles/Image/'.$_POST['upload_dir_new'],777);

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// Выбор шаблона
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir="../templates/skin/";

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                $file=str_replace('.css','',$file);
                
                if($skin == $file)
                    $sel="selected";
                else $sel="";

                if($file!="." and $file!=".." and !strpos($file, '.'))
                    $value[]=array($file,$file,$sel);
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new',$value,100);
}


function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля чата";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);

    // Вывод
    $e_value[]=array('не выводить',0,$enabled);
    $e_value[]=array('слева',1,$enabled);
    $e_value[]=array('справа',2,$enabled);
    
    // Тип вывода
    $w_value[]=array('форма',0,$windows);
    $w_value[]=array('всплывающее окно',1,$windows);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Чат'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField('Заголовок',$PHPShopGUI->setInputText(false,'title_new', $title));
    $Tab1.=$PHPShopGUI->setField('Приветственое сообщение', $PHPShopGUI->setTextarea('title_start_new', $title_start));
    $Tab1.=$PHPShopGUI->setField('Cообщение выключенного режима', $PHPShopGUI->setTextarea('title_end_new', $title_end));
    $Tab1.=$PHPShopGUI->setField('Место вывода',$PHPShopGUI->setSelect('enabled_new',$e_value,100),'left');
    $Tab1.=$PHPShopGUI->setField('Дизайн',GetSkinList($data['skin']),'left');
    $Tab1.=$PHPShopGUI->setField('Файлы пользователей',$PHPShopGUI->setInputText('/UserFiles/Image/','upload_dir_new', $upload_dir,100),'left');
    //$Tab1.=$PHPShopGUI->setField('Тип вывода',$PHPShopGUI->setSelect('windows_new',$w_value,150),'left');
    
    $info='

Для произвольной вставки элемента следует выбрать параметр вывода "Не выводить" и в ручном режиме вставить переменную
        <b>@chat@</b> в свой шаблон.
        <p>Для персонализации формы вывода отредактируйте шаблоны phpshop/modules/chat/templates/. 
        CSS стили цветовой гаммы находятся в phpshop/modules/chat/templates/skin/ 
        Для создания нового стиля достаточно создать новый файл *.css в этой папке.</p>
<p>
Для ответа на вопросы пользователей в чате следует установить пакет утилит EasyControl с выбранным пунктом для установки 
"Чат с посетителями". Чат появится в трее под видом иконки '.
$PHPShopGUI->setImage('../templates/tray.png',16,16,$align='absmiddle',$hspace="3").'
</p>
';
    
    $Load=$PHPShopGUI->setInput("button","","Скачать EasyControl","left",300,"return window.open('http://www.phpshop.ru/loads/ThLHDegJUj/setup.exe');","but");


    $Tab2=$PHPShopGUI->setInfo($info, 200, '96%');
    $Tab2.=$Load;

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial, false, $version, true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,290),array("Инструкция",$Tab2,290),array("О Модуле",$Tab3,290));

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