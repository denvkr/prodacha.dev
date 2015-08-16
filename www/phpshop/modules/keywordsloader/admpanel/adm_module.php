<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("file");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.keywordsloader.keywordsloader_system"));

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

// Обработка строки CSV
function keywords_product_update($data){
    if(is_array($data)){
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $PHPShopOrm->debug=false;
        $PHPShopOrm->update(array('keywords_new'=>$data[1]),array('id'=>'='.$data[0]));
    }
}

// Функция обновления
function actionUpdate() {

    // Копируем базу товаров
    $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
    if ($_FILES['file']['ext'] == "csv") {
        if (move_uploaded_file($_FILES['file']['tmp_name'], "../../../../UserFiles/Files/" . $_FILES['file']['name']))
        PHPShopFile::readCsv("../../../../UserFiles/Files/" . $_FILES['file']['name'], 'keywords_product_update');
    }
    
    return true;
}



function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="Настройка модуля Keywords Loader";
    $PHPShopGUI->size="500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Keywords Loader'","Настройки подключения",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1=$PHPShopGUI->setField('Выгрузка', $PHPShopGUI->setInput("button", "button1", "Выгрузить CSV", "left", 150, "window.open('file.php')"));
  
    $Tab1.=$PHPShopGUI->setField('Выбериет файл с расширением *.csv',$PHPShopGUI->setInput("file", "file", "", "left", 350));

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial, false, $version, true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное",$Tab1,270),array("О Модуле",$Tab3,270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=
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