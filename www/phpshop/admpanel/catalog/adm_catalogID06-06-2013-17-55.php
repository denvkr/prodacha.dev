<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("page");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("category");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = __("Редактирование Каталога");
$PHPShopGUI->reload = "left";

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem, $PHPShopBase;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['catalogID'])));

    // ID окна для памяти закладок
    $PHPShopGUI->setID(__FILE__, $data['id']);

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "700,650";
    // Графический заголовок окна
    $PHPShopGUI->setHeader(__('Редактирование Каталога "' . $data['name'] . '"'), __("Укажите данные для записи в базу."), $PHPShopGUI->dir . "img/i_actionlog_med[1].gif");

    // Нет данных
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "Закрыть", "center", 100, "return onCancel();", "but"));
        return true;
    }

    // Наименование
    $Tab1 = $PHPShopGUI->setField(__("Наименование <b>CID " . $data['id'] . "</b>:"), $PHPShopGUI->setInputText(false, 'name_new', $data['name'], '100%'));

    // Выбор каталога
    $Tab1.= $PHPShopGUI->setField(__("Каталог:"), $PHPShopGUI->setInputText(false, "parent_name", getCatPath($data['id']), '450px', false, 'left') .
            $PHPShopGUI->setInput("hidden", "parent_to_new", $data['parent_to'], "left", 400) .
            $PHPShopGUI->setButton(__('Выбрать'), "../img/icon-move-banner.gif", "100px", '25px', "right", "miniWin('" . $dot . "./catalog/adm_cat.php?category=" . $data['parent_to'] . "',300,400);return false;"));

    // Сетка
    $num_row_area = $PHPShopGUI->setRadio('num_row_new', 1, 1, $data['num_row']);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 2, 2, $data['num_row']);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 3, 3, $data['num_row']);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 4, 4, $data['num_row']);
    $Tab1.=$PHPShopGUI->setField(__("Товаров в длину:"), $num_row_area, 'left');

    // Вывод
    $Tab1.=$PHPShopGUI->setField(__("Опции вывода:"), $PHPShopGUI->setCheckbox('vid_new', 1, __('Выводить подкаталоги списком в основном окне'), $data['vid']) .
            $PHPShopGUI->setCheckbox('skin_enabled_new', 1, __('Скрыть каталог'), $data['skin_enabled']));

    // Товаров на странице
    $Tab1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField(__("Товаров на странице:"), $PHPShopGUI->setInputText(false, 'num_cow_new', $data['num_cow'], '50px', __('шт.')), 'left');

    // Тип сортировки
    $order_by_value[] = array('по имени', 1, $data['order_by']);
    $order_by_value[] = array('по цене', 2, $data['order_by']);
    $order_by_value[] = array('по популярности', 3, $data['order_by']);
    $order_to_value[] = array('возрастанию', 1, $data['order_to']);
    $order_to_value[] = array('убыванию', 2, $data['order_to']);
    $Tab1.=$PHPShopGUI->setField(__("Сортировка:"), $PHPShopGUI->setInputText('№', "num_new", $data['num'], '50px', false, 'left') .
            $PHPShopGUI->setSelect('order_by_new', $order_by_value, 120) . $PHPShopGUI->setSelect('order_to_new', $order_to_value, 120), 'left');

    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '450';
    $oFCKeditor->Config['EditorAreaCSS'] = chr(47) . "phpshop" . chr(47) . "templates" . chr(47) . $PHPShopSystem->getValue('skin') . chr(47) . $PHPShopBase->getParam('css.default');
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];
    $Tab2 = $oFCKeditor->AddGUI();

    // Характеристики
    $Tab4 = $PHPShopGUI->loadLib('tab_sorts', $data);

    // Заголовки
    $Tab7 = $PHPShopGUI->loadLib('tab_headers', $data);

    // Безопасноть
    $Tab8_1 = $PHPShopGUI->loadLib('tab_secure', $data);

    // Мультибаза
    $Tab8_2 = $PHPShopGUI->loadLib('tab_multibase', $data);

    $PHPShopInterfaceDoc = new PHPShopInterface('_dop_');
    $PHPShopInterfaceDoc->setTab(array(__("Безопасность"), $Tab8_1, 400), array(__("Мультибаза"), $Tab8_2, 400));
    $Tab8 = $PHPShopInterfaceDoc->getContent();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 450), array(__("Описание"), $Tab2, 450), array(__("Заголовки"), $Tab7, 450), array(__("Дополнительно"), $Tab8, 450));

    // Добавление закладки характеристики если нет подкаталогов
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $subcategory_data = $PHPShopOrm->select(array('id'), array('parent_to' => '=' . intval($data['id'])), false, array('limit' => 2));
    if (!is_array($subcategory_data))
        $PHPShopGUI->addTab(array(__("Характериcтики"), $Tab4, 450));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "catalogID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.cat_prod.remove") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.cat_prod.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.cat_prod.edit");

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

    $PHPShopCategoryArray = new PHPShopCategoryArray();
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


/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;

    // Сохранение данных
    actionUpdate();

    $PHPShopGUI->setAction($_POST['catalogID'], 'actionStart', 'none');
}

/**
 * Экшен обновления
 * @return bool 
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopBase;

    // Проверка прав редактирования
    if ($PHPShopBase->Rule->CheckedRules('cat_prod', 'rule')) {

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

    if (empty($_POST['vid_new']))
        $_POST['vid_new'] = 0;

    if (empty($_POST['skin_enabled_new']))
        $_POST['skin_enabled_new'] = 0;

    // Характеристики
    $_POST['sort_new'] = serialize($_POST['sort_new']);

    // Описание
    if (isset($_POST['EditorContent1']))
        $_POST['content_new'] = $_POST['EditorContent1'];

    // Мультибаза
    $_POST['servers_new'] = null;
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            $_POST['servers_new'].="i" . $v . "i";

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['catalogID']));
    $PHPShopOrm->clean();

    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);
    $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['catalogID'])));

    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['catalogID'], 'actionStart', 'none');

// Обработка событий
$PHPShopGUI->getAction();
?>