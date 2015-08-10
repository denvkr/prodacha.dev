<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seourl.seourl_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;


    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля SEO Url";
    $PHPShopGUI->size = "500,450";


    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);

    if ($enabled == 1)
        $enabled = "checked"; else
        $enabled = "";
    if ($flag == 1)
        $s2 = "selected";
    else
        $s1 = "selected";


    $Select[] = array("Слева", 0, $s1);
    $Select[] = array("Справа", 1, $s2);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'SEO Url'", "Настройки подключения", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    // Содержание закладки
    $Info = '
<p>Модуль создает SEO ссылки для товаров, каталогов товаров, каталогов страниц и новостей путем дописывания в имя файлы

Для работы модуля требуется внести изменения в шаблон дизайна. Если у вас новые шаблоны 2012 года( aqua, skyblue, lime и т.д.),
то необходимо скопировать обновленные файлы шаблона из папки на ftp <b>/phpshop/modules/seourl/tepmplates/seo/</b> в папку со своим шаблоном.
Шаблоны изменяют лишь форму вывода товаров, статей и новостей. Общий вид сайта они не затрагивают.
<p>Если у вас старые шаблоны до 2012 года, то для работы модуля требуется добавить переменную <b>@nameLat@</b> в шаблоны вывода товаров в папке phpshop/templates/имя шаблона/product/:
   <ol>
   <li>main_product_forma_1.tpl
   <li>main_product_forma_2.tpl
   <li>main_product_forma_3.tpl
   <li>main_product_forma_4.tpl
   <li>main_spec_forma_icon.tpl
   <li>product_page_list.tpl
   </ol>
> Пример:<p>
"/shop/UID_@productUid@.html" заменить на /shop/UID_@productUid@<b>@nameLat@</b>.html</p>
> Пример для product_page_list.tpl:<p>
"./CID_@productId@_@productPageThis@.html" заменить на ./CID_@productId@_@productPageThis@<b>@nameLat@</b>.html</p>

По аналогии требуется добавить переменную @nameLat@ в шаблоны вывода каталогов в папке phpshop/templates/имя шаблона/catalog/:
   <ol>
   <li>catalog_forma_2.tpl
   <li>catalog_forma_3.tpl
   <li>catalog_table_forma.tpl
   <li>podcatalog_forma.tpl
   <li>podcatalog_page_forma.tpl
   <li>catalog_page_forma_2.tpl
   </ol>
   
> Пример:<p>
"/shop/CID_@catalogId@.html" заменить на /shop/CID_@catalogId@<b>@nameLat@</b>.html</p>

По аналогии требуется добавить переменную @nameLat@ в шаблоны вывода новостей в папке phpshop/templates/имя шаблона/news/:
   <ol>
   <li>main_news_forma.tpl
   <li>news_main_mini.tpl
   </ol>
';
    $Tab1 = $PHPShopGUI->setInfo($Info, 250, '95%');

    // Форма регистрации
    $Tab2 = $PHPShopGUI->setPay($serial, false);

    // История изменений
    if(method_exists($PHPShopGUI,'setLine'))
    $Tab2.= $PHPShopGUI->setLine('<br>').$PHPShopGUI->setHistory();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Описание", $Tab1, 270), array("О Модуле", $Tab2, 270));

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