<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.admlog.admlog_log"));

// Функция обновления
function actionUpdate() {
    global $PHPShopSystem;

    $pathinfo = pathinfo($_POST['file_new']);
    $file = $pathinfo['dirname'];
    
    // Карта таблиц БД
    $baseMap = array(
        'page' => 'page',
        'system' => 'table_name3',
        'gbook' => 'gbook',
        'news' => 'news',
        'menu' => 'table_name14',
        'news_writer' => 'table_name9',
        'banner' => 'table_name15',
        'links' => 'table_name17',
        'users' => 'users',
        'opros' => 'table_name20',
        'product' => 'products',
        'catalog'=>'categories'
    );

    // Карта папок
    $dirSearch = array_keys($baseMap);


    foreach ($dirSearch as $val)
        if (strpos($file, $val)) {
            $baseName = $baseMap[$val];
        }

    $contentCode = unserialize(base64_decode($_POST['contentCode']));


    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base'][$baseName]);
    $PHPShopOrm->debug = true;
    //$PHPShopOrm->trace($contentCode);

    if (!empty($contentCode['delID'])) {
        $action = $PHPShopOrm->insert($contentCode);
        $nameHandler = 'Откат удаления';
    } else {

        if (!empty($contentCode['newsID']))
            $itemID = $contentCode['newsID'];
        elseif (!empty($contentCode['pageID']))
            $itemID = $contentCode['pageID'];
        elseif (!empty($contentCode['productID']))
            $itemID = $contentCode['productID'];
        elseif (!empty($contentCode['catalogID']))
            $itemID = $contentCode['catalogID'];
        else
            $itemID = $contentCode['itemID'];

        $action = $PHPShopOrm->update($contentCode, array('id' => '=' . intval($itemID)));
        $nameHandler = 'Откат изменения';
    }

    // Пишем лог
    include_once('writelog.php');
    setLog(false, $nameHandler);

    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm;

    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Откат изменений";
    $PHPShopGUI->size = "500,450";


    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $_GET['id']));
    $contentTemp = unserialize($data['content']);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Откат изменений", "Возвращение данных за " . PHPShopDate::dataV($date, true), $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInput("text", "name_new", PHPShopDate::dataV($data['date'], true), "left", 100));
    $Tab1.=$PHPShopGUI->setField("Пользователь:", $PHPShopGUI->setInput("text", "name_new", $data['user'], "left", 200));
    $Tab1.=$PHPShopGUI->setField("Действие:", $PHPShopGUI->setInput("text", "name_new", $data['title'], "left", 500));
    $Tab1.=$PHPShopGUI->setField("Файл:", $PHPShopGUI->setInput("text", "file_new", $data['file'], "left", 500));


    // Основное содержание
    $titleSearch = array('content_new','description_new');
    if (is_array($contentTemp))
        foreach ($contentTemp as $key => $val) {
            if (in_array($key, $titleSearch)) {
                $contentMain = $contentTemp[$key];
                break;
            }
        }


    // Редактор 1
    $PHPShopGUI->setEditor('none', $mod_enabled = true);
    $oFCKeditor = new Editor('content_temp');
    $oFCKeditor->Height = '280';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "../templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $contentMain;

    $Tab2 = $oFCKeditor->AddGUI();

    // Данные отката
    $Tab1.=$PHPShopGUI->setInput("hidden", "contentCode", base64_encode($data['content']), "left", 1);


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 300));

    if (!empty($contentMain))
        $PHPShopGUI->addTab(array("Содержание", $Tab2, 300));


    $pathinfo = pathinfo($data['file']);
    $ContentFooter = $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but");

    if ($pathinfo['basename'] != "adm_admlog_back.php")
        $ContentFooter.=$PHPShopGUI->setInput("submit", "editID", "Откатить", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();
}
else
    $UserChek->BadUserFormaWindow();
?>