<?

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    echo $new_version;
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    if (empty($_POST['key_enabled_new']))
        $_POST['key_enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function getStatus($status_id) {
    global $PHPShopGUI;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
    $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 100));
    if (is_array($data))
        foreach ($data as $row) {
            if ($row['id'] == $status_id)
                $sel = 'selected';
            else
                $sel = null;
            $value[] = array($row['name'], $row['id'], $sel);
        }

    return $PHPShopGUI->setSelect('order_status_new', $value, 200, false, 'Статус заказа выплаты:');
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;

    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Partner'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");


    $Tab1 = $PHPShopGUI->setCheckbox('enabled_new', 1, 'Учет рефералов партнеров', $enabled);
    $Tab1.=$PHPShopGUI->setCheckbox('key_enabled_new', 1, 'Учет персональных ключей API', $key_enabled);
    $Tab1.=$PHPShopGUI->setInputText('Начисление партнерам', 'percent_new', $percent, '50', '% от заказа');
    $Tab1.=getStatus($order_status);
    $Info = 'Страница входа в партнерский раздел находится по адресу: http://' . $_SERVER['SERVER_NAME'] . '/partner/<br>
        Необходимо на своем сайте добавить эту ссылку для пользователей.
        <p>
Правила регистрации в партнерской программе доступны по ссылке
        http://' . $_SERVER['SERVER_NAME'] . '/rulepartner/
     <p>
     Шаблоны оформления находятся в папке /phpshop/modules/partner/templates/<br>
     Языковой файл по адресу /phpshop/modules/partner/inc/config.ini в блоке [lang]
     <p>
     Для добавления персональных полей в форму регистрации пользователей отредактируйте файл /phpshop/modules/partner/templates/partner_forma_register.tpl,
     добавьте необходимые поля с префиксом dop_, например dop_icq.

';

    $Tab1.=$PHPShopGUI->setInfo($Info, '200', '97%');

    // Содержание закладки 2
    $Tab2 = $PHPShopGUI->setPay($serial, false, $version, true);

    $Tab3 = $PHPShopGUI->setTextarea('rule_new', $rule, false, '99%', 320);
    $Tab3.=$PHPShopGUI->setText('* Используйте переменную @partnerPercent@ для обозначения % вознаграждения.');


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Описание", $Tab1, 350), array("Текст правила участия", $Tab3, 350), array("О Модуле", $Tab2, 350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

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
?>


