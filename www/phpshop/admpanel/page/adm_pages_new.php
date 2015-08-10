<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("page");
PHPShopObj::loadClass("category");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Создание Страниц";
$PHPShopGUI->reload = "right";

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopModules;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    // Начальные данные
    $data = array();
    if (!empty($_GET['categoryID']))
        $data['category'] = $_GET['categoryID'];

    $num = 1;
    $enabled = 1;

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size="650,600";
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Страниц", "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_website_tab[1].gif");

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '400';
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $content;

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Каталог:", $PHPShopGUI->setInput("text", "parent_name", getCatPath($data['category']), "left", 450) .
                    $PHPShopGUI->setInput("hidden", "category_new", $data['category'], "left", 450) .
                    $PHPShopGUI->setButton("Выбрать", "../icon/folder_edit.gif", "100px", "Выбрать", "none", "miniWin('" . $dot . "./page/adm_cat.php?category=" . $category . "',300,400);return false;"), "none") .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Заголовок:", $PHPShopGUI->setInput("text", "name_new", $name, "left", 400), "left") .
            $PHPShopGUI->setField("Позиция вывода:", $PHPShopGUI->setInput("text", "num_new", $num, "left", 50), "none", 5) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("Ссылка:", $PHPShopGUI->setInputText('/page/', "link_new", $link, 200, '.html'), "left");

    $SelectValue[] = array('Вывод в каталоге', 1, $enabled);
    $SelectValue[] = array('Заблокировать', 0, $enabled);

    $Tab1.= $PHPShopGUI->setField("Вывод:", $PHPShopGUI->setSelect("enabled_new", $SelectValue, 150), "none", 5);

    // Содержание закладки 2
    $Tab2 = $oFCKeditor->AddGUI();

    // Содержание закладки 3
    $Tab3 = $PHPShopGUI->setField("Title: ", $PHPShopGUI->setTextarea("title_new", $title), "none");
    $Tab3.=$PHPShopGUI->setField("Description: ", $PHPShopGUI->setTextarea("description_new", $description), "none");
    $Tab3.=$PHPShopGUI->setField("Keywords: ", $PHPShopGUI->setTextarea("keywords_new", $keywords), "none");

    // Безопасноть
    $Tab4 = $PHPShopGUI->loadLib('tab_secure', $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Содержание", $Tab2, 400), array("Размещение", $Tab1, 400), array("Заголовки", $Tab3, 400), array(__("Безопасность"), $Tab4, 400));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.page_site.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Путь каталога
 * @param int $category ИД категории
 * @return string 
 */
function getCatPath($category) {
    $PHPShopCategoryArray = new PHPShopPageCategoryArray();
    $i = 1;
    $str = __('Корень');
    while ($i < 10) {
        $parent = $PHPShopCategoryArray->getParam($category . '.parent_to');
        if (isset($parent)) {
            $path[$category] = $PHPShopCategoryArray->getParam($category . '.name');
            $category = $parent;
        }
        $i++;
    }

    if (is_array($path)) {
        $path = array_reverse($path);

        foreach ($path as $val)
            $str.=' -> ' . $val;

        return $str;
    }
}

// Функция сохранения
function actionInsert() {
    global $PHPShopModules, $PHPShopBase, $PHPShopOrm;

    // Проверка прав редактирования
    if ($PHPShopBase->Rule->CheckedRules('page_site', 'edit')) {
        $sq_new = null;
        $counter = 0;
        $selected = 0;
        if (is_array($_POST['seq']))
            foreach ($_POST['seq'] as $crid => $value) {
                $sq_new.='i' . $crid . '-' . $value . 'i';
                $counter++;
                if ($value) {
                    $selected++;
                }
                if (!empty($_POST['seq']['9999'])) {
                    $sq_new = '';
                    break;
                }
            }
        if (empty($selected) || ($counter == $selected)) {
            $sq_new = '';
        }
        $_POST['secure_groups_new'] = $sq_new;
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $_POST['date_new'] = date('U');

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('enabled_new');

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $action = $PHPShopOrm->insert($_POST);

    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>
