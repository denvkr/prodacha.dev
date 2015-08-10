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
$PHPShopGUI->title = __("Создание Товара");
$PHPShopGUI->reload = "right";
$PHPShopGUI->addJSFiles('/phpshop/lib/Subsys/JsHttpRequest/Js.js');

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    // Начальные данные
    $data = array();
    if (!empty($_GET['categoryID']))
        $data['category'] = $_GET['categoryID'];

    if (empty($_GET['productID'])) {
        $data['ed_izm'] = $ed_izm = 'шт.';
        $data['baseinputvaluta'] = $PHPShopSystem->getDefaultOrderValutaId();
        $data['name'] = __('Новый товар');
        $data['enabled'] = 1;
        $data['newtip'] = 1;
        $data['p_enabled'] = 1;
        $data['yml'] = 1;
        $data['id'] = getLastID();
    } else {
        // Создание копии товара
        $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['productID'])));
    }

    $PHPShopGUI->dir = "../";
    $PHPShopGUI->size = "700,670";

    // Графический заголовок окна
    $PHPShopGUI->setHeader(__("Создание Товара"), __("Укажите данные для записи в базу."), $PHPShopGUI->dir . "img/i_actionlog_med[1].gif");

    // Выбор каталога
    $Tab1 = $PHPShopGUI->setField(__("Каталог:"), $PHPShopGUI->setInputText(false, "parent_name", getCatPath($data['category']), '500px', false, 'left') .
            $PHPShopGUI->setInput("hidden", "category_new", $data['category'], "left", 450) .
            $PHPShopGUI->setButton(__('Выбрать'), "../img/icon-move-banner.gif", "100px", '25px', "right", "miniWin('" . $dot . "./catalog/adm_cat.php?category=" . intval($data['category']) . "',300,400);return false;"));

    // Наименование
    $Tab1.=$PHPShopGUI->setField("Наименование <b>ID " . $data['id'] . "</b>:", $PHPShopGUI->setInputText(false, 'name_new', $data['name'], '100%'));

    // Артикул
    $Tab1.=$PHPShopGUI->setField('Артикул:', $PHPShopGUI->setInputText('#', 'uid_new', $data['uid']), 'left');

    $Tab1.=$PHPShopGUI->setField('Склад:', $PHPShopGUI->setInputText(false, 'items_new', $data['items'], 50, $ed_izm), 'left');

    // Вес
    $Tab1.=$PHPShopGUI->setField('Вес:', $PHPShopGUI->setInputText(false, 'weight_new', $data['weight'], 50, 'гр.'), 'left');

    $Tab1.=$PHPShopGUI->setField('Единица измерения:', $PHPShopGUI->setInputText(false, 'ed_izm_new', $data['ed_izm'], 70), 'left');

    // Рекомендуемые товары
    $Tab1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField('Рекомендуемые товары для совместной продажи:', $PHPShopGUI->setTextarea('odnotip_new', $data['odnotip'], false, '300px') .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16) .
                    __('Введите ID товаров в формате 1,2,3 без пробелов'), 'left');

    // Дополнительные каталоги
    $Tab1.=$PHPShopGUI->setField('Дополнительные каталоги:', $PHPShopGUI->setTextarea('dop_cat_new', $data['dop_cat'], false, '300px') .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16) .
            __('Введите ID каталогов в формате #1#2#3# без пробелов'), 'left');

    $Tab1.=$PHPShopGUI->setLine();

    // Опции вывода
    $Tab1_1.=$PHPShopGUI->setLine() . $PHPShopGUI->setField('Опции вывода:', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вывод в каталоге', $data['enabled']) .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setCheckbox('spec_new', 1, 'Спецпредложение', $data['spec']) .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setCheckbox('newtip_new', 1, 'Новинка', $data['newtip']) .
                    $PHPShopGUI->setLine() .
                    $PHPShopGUI->setInputText('№', 'num_new', $data['num'], 50, 'по порядку'), 'left');

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    $valuta_area = null;
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $check = 'checked';
                $valuta_def_name = $val['code'];
            }
            else
                $check = false;
            $valuta_area.=$PHPShopGUI->setRadio('baseinputvaluta_new', $val['id'], $val['name'], $check);
            $valuta_area.=$PHPShopGUI->setLine();
        }

    // Цены
    $Tab1_1.=$PHPShopGUI->setField('Цены:', $PHPShopGUI->setInputText('Цена 1', 'price_new', $data['price'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('Цена 2', 'price2_new', $data['price2'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('Цена 3', 'price3_new', $data['price3'], 50, $valuta_def_name), 'left');

    // Валюта
    $Tab1_1.=$PHPShopGUI->setField(__('Валюта:'), $valuta_area, 'left');

    // Цены дополнительные
    $Tab1_2.=$PHPShopGUI->setField('Цены:', $PHPShopGUI->setInputText('Цена 4', 'price4_new', $data['price4'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('Цена 5', 'price5_new', $data['price5'], 50, $valuta_def_name) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setCheckbox('sklad_new', 1, 'Под заказ', $data['sklad']), 'left');

    // Распродажа
    $Tab1_2.=$PHPShopGUI->setField(__('Распродажа:'), $PHPShopGUI->setInputText(__('Старая цена'), 'price_n_new', $data['price_n'], 50, $valuta_def_name), 'left');

    // Иконка
    if (!empty($data['pic_small'])) {
        $PHPShopInterface = new PHPShopInterface('_pretab1_');
        $PHPShopInterface->setTab(array(__("Изображение"), $PHPShopGUI->setLink('../../../shop/UID_' . $data['id'] . '.html', $PHPShopGUI->setImage($data['pic_small'], $PHPShopSystem->getSerilizeParam('admoption.img_tw'), $PHPShopSystem->getSerilizeParam('admoption.img_tw')), '_blank', false, 'Перейти'), 120));
        $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopInterface->getContent(), 'width:' . ($PHPShopSystem->getSerilizeParam('admoption.img_tw') + 50) . 'px;float:left');
    }

    // YML
    $Tab1_3 = $PHPShopGUI->setField($PHPShopGUI->setCheckbox('yml_new', 1, __('Вывод в Яндекс Маркете'), $data['yml']), $PHPShopGUI->setRadio('p_enabled_new', 1, __('В наличии'), $data['p_enabled']) .
            $PHPShopGUI->setRadio('p_enabled_new', 0, __('Под заказ'), $data['p_enabled']));

    // BID
    $data['yml_bid_array'] = unserialize($data['yml_bid_array']);
    $Tab1_3.=$PHPShopGUI->setField(__('BID'), $PHPShopGUI->setInputText('Ставка', 'yml_bid_array[bid]', $data['yml_bid_array']['bid'], 100, $PHPShopGUI->setLink('http://partner.market.yandex.ru/legal/tt/', $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16), false, false, 'Описание поля')), 'left');

    // CBID
    $Tab1_3.=$PHPShopGUI->setField(__('CBID'), $PHPShopGUI->setInputText('Ставка', 'yml_bid_array[cbid]', $data['yml_bid_array']['cbid'], 100, $PHPShopGUI->setLink('http://partner.market.yandex.ru/legal/tt/', $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16), false, false, 'Описание поля')), 'left');

    // Подтипы
    $Tab1_4 = $PHPShopGUI->setField(__('Связи'), $PHPShopGUI->setRadio('parent_enabled_new', 0, __('Обычный товар'), $data['parent_enabled']) .
            $PHPShopGUI->setRadio('parent_enabled_new', 1, __('Добавочная опция для ведущего товара'), $data['parent_enabled']));
    $Tab1_4.=$PHPShopGUI->setTextarea('parent_new', $data['parent'], "none", '99%', '50px') .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setImage('../icon/icon_info.gif', 16, 16) .
            __('Введите ID товаров-подтипов через запятую без пробела (100,101). ');

    // Вывод
    $PHPShopInterface = new PHPShopInterface('_pretab2_');
    $PHPShopInterface->setTab(array(__("Основное"), $Tab1_1, 120), array(__("Дополнительные цены"), $Tab1_2, 120), array(__("YML"), $Tab1_3, 120), array(__("Подтипы"), $Tab1_4, 120));
    $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopInterface->getContent(), 'float:left;padding-left:5px');

    // Редактор краткого описания
    $Tab2 = $PHPShopGUI->loadLib('tab_description', $data);

    // Редактор подробного описания
    $Tab3 = $PHPShopGUI->loadLib('tab_content', $data);

    // Статьи
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data_page = $PHPShopOrm->select(array('*'), false, array('order' => 'name'), array('limit' => 100));

    if (strstr($data['page'], ',')) {
        $data['page'] = explode(",", $data['page']);
    }else
        $data['page'] = array();

    $value = array();
    if (is_array($data_page))
        foreach ($data_page as $val) {
            if (is_numeric(array_search($val['link'], $data['page']))) {
                $check = 'selected';
            }
            else
                $check = false;

            $value[] = array($val['name'], $val['link'], $check);
        }
    $Tab4_1 = $PHPShopGUI->setSelect('page[]', $value, '90%', false, false, false, false, 30, true);

    // Файлы
    $Tab4_2 = $PHPShopGUI->loadLib('tab_files', $data);

    // Документы
    $PHPShopInterfaceDoc = new PHPShopInterface('_doc_');
    $PHPShopInterfaceDoc->setTab(array(__("Статьи"), $Tab4_1, 400), array(__("Файлы"), $Tab4_2, 400));
    $Tab4 = $PHPShopInterfaceDoc->getContent();

    // Фотогалерея
    $Tab6 = $PHPShopGUI->loadLib('tab_img', $data);

    // Характеристики
    $Tab7 = $PHPShopGUI->loadLib('tab_sorts', $data);

    // Заголовки
    $Tab8 = $PHPShopGUI->loadLib('tab_headers', $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 450), array(__("Изображение"), $Tab6, 450), array(__("Описание"), $Tab2, 450), array(__("Подробно"), $Tab3, 450), array(__("Документы"), $Tab4, 450), array(__("Характеристики"), $Tab7, 450), array(__("Заголовки"), $Tab8, 450));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.cat_prod.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Послдений ID в таблице
 * @return integer 
 */
function getLastID() {
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SELECT MAX(id) AS maxid from ' . $GLOBALS['SysValue']['base']['products'];
    $data = $PHPShopOrm->select();
    if (is_array($data)) {
        return $data[0]['maxid'] + 1;
    }
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
 * Экшен записи
 */
function actionInsert() {
    global $PHPShopModules, $PHPShopOrm;


    $_POST['datas_new'] = date('U');

    $_POST['yml_bid_array_new'] = serialize($_POST['yml_bid_array']);

    // Характеристики
    $_POST['vendor_new'] = null;
    if (is_array($_POST['vendor_array_new']))
        foreach ($_POST['vendor_array_new'] as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $p)
                    $_POST['vendor_new'].="i" . $k . "-" . $p . "i";
            }
            else
                $_POST['vendor_new'].="i" . $k . "-" . $v . "i";
        }
    $_POST['vendor_array_new'] = serialize($_POST['vendor_array_new']);

    // Статьи
    $_POST['page_new'] = null;
    if (is_array($_POST['page']))
        foreach ($_POST['page'] as $value)
            $_POST['page_new'].=$value . ",";

    // Файлы
    $_POST['files_new'] = serialize($_POST['filenum']);

    // Описание для редактора default
    if (isset($_POST['EditorContent1']))
        $_POST['description_new'] = $_POST['EditorContent1'];
    if (isset($_POST['EditorContent2']))
        $_POST['content_new'] = $_POST['EditorContent2'];

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('newtip_new', 'enabled_new', 'spec_new', 'yml_new');

    $action = $PHPShopOrm->insert($_POST);
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>