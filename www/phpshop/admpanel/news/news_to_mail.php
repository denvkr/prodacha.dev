<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("mail");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Действие";

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name9']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    $PHPShopGUI->dir = "../";
    $PHPShopGUI->size = "400,300";


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Рассылка новостей за '" . $_GET['data'] . "'", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_mail_forward_med[1].gif");


    // Включаем таймер
    $time = explode(' ', microtime());
    $start_time = $time[1] + $time[0];

    $num = 0;
    $content = Ras_data_content($_GET['data']);
    Ras_data_mail($content, $num);

    // Выключаем таймер
    $time = explode(' ', microtime());
    $seconds = ($time[1] + $time[0] - $start_time);
    $seconds = substr($seconds, 0, 6);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setDiv('left', "Рассылка произведена по <b>" . $num . "</b> адресу(ам).<br>
	Время обработки запроса: $seconds sec.");

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 120));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, false);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("button", "", "Закрыть", "right", 70, "return onCancel();", "but");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();
}else
    $UserChek->BadUserFormaWindow();

// Состав рассылки
function Ras_data_content($data) {
    global $PHPShopSystem, $PHPShopModules;
    $content = null;
    $sql = "select * from " . $GLOBALS['SysValue']['base']['table_name8'] . "  where date='$data' and content IS NOT NULL";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $data = $row['date'];
        $kratko = strip_tags($row['description']);
        $podrob = $row['content'];
        if (!empty($podrob)) {

            // Проверка модуля Seourl
            if (!empty($row['seo_name']))
                $link = __("Подробности") . ": http://" . $_SERVER['SERVER_NAME'] . "/news/ID_" . $row['seo_name'] . ".html";
            else
                $link = __("Подробности") . ": http://" . $_SERVER['SERVER_NAME'] . "/news/ID_" . $row['id'] . ".html";
        }
        else {
            $link = null;
        }
        $content.=
                $kratko . "
" . $link;
    }
    $disp = "
" . __("Здравствуйте, представляем новости с сайта") . "\"" . strtoupper($PHPShopSystem->getParam('name')) . "

" . $content . "

------------
" . __("С уважением,") . "
" . __("Коллектив") . " " . strtoupper($PHPShopSystem->getParam('company')) . "
";
    return $disp;
}

// Рассылка
function Ras_data_mail($content, &$num) {
    global $PHPShopSystem;
    $num = 0;
    $zag = __("Анонсы новостей") . " " . $PHPShopSystem->getParam('name');
    $sql = "select distinct mail from " . $GLOBALS['SysValue']['base']['table_name9'] . " order by id";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $mail_to = $row['mail'];
        $unwrite = '
' . __('Для отказа от рассылки новостей перейдите по ссылке') . ': http://' . $_SERVER['SERVER_NAME'] . '/news/?news_del=true&mail=' . $mail_to;
        new PHPShopMail($mail_to, $PHPShopSystem->getParam('admin_mail'), $zag, $content . $unwrite);
        $num++;
    }
}

?>