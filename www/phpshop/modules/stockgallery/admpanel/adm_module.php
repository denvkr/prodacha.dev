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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.stockgallery.stockgallery_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля";
    $PHPShopGUI->size = "500,450";


    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Витрина'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");


    $Tab1 = $PHPShopGUI->setField('Вывод', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Выводить в блоке спецпредложений на главной странице', $enabled));
    $Tab1.=$PHPShopGUI->setField('Ширина карусели', $PHPShopGUI->setInputText(false, 'width_new', $width, 30, 'px'), 'left');
    $Tab1.=$PHPShopGUI->setField('Ширина изображения товара', $PHPShopGUI->setInputText(false, 'img_width_new', $img_width, 30, 'px'), 'left');
    $Tab1.=$PHPShopGUI->setField('Высота изображения товара', $PHPShopGUI->setInputText(false, 'img_height_new', $img_height, 30, 'px'));
    $Tab1.=$PHPShopGUI->setField('Рамка', $PHPShopGUI->setInputText(false, 'border_new', $border, 30, 'px'), 'left');
    $Tab1.=$PHPShopGUI->setField('Цвет рамки', $PHPShopGUI->setInputText('#', 'border_color_new', $border_color, 100), 'left');
    $Tab1.=$PHPShopGUI->setField('Количество товаров', $PHPShopGUI->setInputText(false, 'limit_new', $limit, 30));

    $info = 'Для произвольной вставки элемента следует выбрать парамет вывода "Не выводить" и в ручном режиме вставить переменную
        <b>@stockgallery@</b> в свой шаблон. Или через панель управления создайте текстовый блок, переключитесь в режим исходного кода (Система - Настройка - Режимы - Визуальный редактор),
        внесите метку @stockgallery@ - теперь блок будет выводиться в нужном вам месте.
        <p>Для персонализации формы вывода отредактируйте шаблон phpshop/modules/stockgallery/templates/stockgallery_forma.tpl</p>
';

    $Tab2 = $PHPShopGUI->setInfo($info, 200, '96%');
    
    // Содержание закладки 3
    $Tab3 = $PHPShopGUI->setPay($serial, false);
    
    $Lib='В модуле использована открытая библиотека <a href="http://caroufredsel.frebsite.nl/" target="_blank">jQuery carouFredSel</a><br>
        Copyright (C) 2012 Fred Heusschen.';
    $Tab3.=$PHPShopGUI->setInfo($Lib,50,'95%');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270),array("Инструкция",$Tab2,270), array("О Модуле", $Tab3, 270));

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


