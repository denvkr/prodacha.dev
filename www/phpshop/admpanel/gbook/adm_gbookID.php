<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass("system");
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Редактирование Отзыва";
$PHPShopGUI->ajax = "'gbook','','','core'";
$PHPShopGUI->alax_lib = true;

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['gbook']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));
    extract($data);

    // ID окна для памяти закладок
    $PHPShopGUI->setID(__FILE__, $data['id']);

    $PHPShopGUI->dir = "../";
    $PHPShopGUI->size = "630,530";
    $PHPShopGUI->addJSFiles('../java/popup_lib.js', '../java/dateselector.js');
    $PHPShopGUI->addCSSFiles('../css/dateselector.css');

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Редактирование Отзыва", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_account_properties_med[1].gif");

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('otvet_new');
    $oFCKeditor->Height = '320';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "../templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $otvet;

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата:", $PHPShopGUI->setInput("text", "datas_new", PHPShopDate::dataV($datas, false), "left", 70) .
            $PHPShopGUI->setCalendar('datas_new') .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox('flag_new', '1', 'Вывод', $flag)
            , "left");

    $Tab1.=$PHPShopGUI->setField("Автор:", $PHPShopGUI->setText("Имя:&nbsp;&nbsp;", "left") .
                    $PHPShopGUI->setInput("text", "name_new", $name, "none", 300) . $PHPShopGUI->setText("E-mail:", "left") . $PHPShopGUI->setInput("text", "mail_new", $mail, "none", 300), "none", 5) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Тема:", $PHPShopGUI->setTextarea("tema_new", $tema, "left", '97%', '50px'), "none") .
            $PHPShopGUI->setField("Отзыв:", $PHPShopGUI->setTextarea("otsiv_new", $otsiv, "left", '97%', '80px'), "none");

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Ответ", $Tab2, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.gbook.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.gbook.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.gbook.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция отправки почты
function sendMail($name, $mail) {
    global $PHPShopSystem, $PHPShopBase;

    // Подключаем библиотеку отправки почты
    PHPShopObj::loadClass("mail");

    $zag = "Ваш отзыв добавлен на сайт " . $PHPShopSystem->getValue('name');
    $message = "Уважаемый " . $name . ",

Ваш отзыв добавлен на сайт по адресу: http://" . $PHPShopBase->getSysValue('dir.dir') . $_SERVER['SERVER_NAME'] . "/gbook/

Спасибо за проявленный интерес.";
    new PHPShopMail($PHPShopSystem->getValue('admin_mail'), $mail, $zag, $message);
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;

    // Сохранение данных
    actionUpdate();

    $_GET['id'] = $_POST['newsID'];
    $PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $_POST['datas_new'] = PHPShopDate::GetUnixTime($_POST['datas_new']);
    if (empty($_POST['flag_new']))
        $_POST['flag_new'] = 0;
    else if (!empty($_POST['mail_new']))
        sendMail($_POST['name_new'], $_POST['mail_new']);

    // Описание для редактора default
    if (isset($_POST['EditorContent1']))
        $_POST['otvet_new'] = $_POST['EditorContent1'];

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['newsID']));
    $PHPShopOrm->clean();
    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['newsID']));
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

// Обработка событий
$PHPShopGUI->getAction();
?>