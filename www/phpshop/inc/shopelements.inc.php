<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/config_functions.php');
//определяем локаль для системной сортировки
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    setlocale(LC_ALL, 'rus'); 
    else
    setlocale(LC_ALL, 'ru_RU.CP1251'); 
/*
 *  определяем настройку сортировки в левом меню, по умолчанию 1-включена кастомная сортировка
 */
define(CustomSortOrder,1);
/**
 * Элемент характеристик товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSortElements
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopSortElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopSortElement() {
        parent::PHPShopElements();
    }

    /**
     * Вывод списка характеристики для отбора
     * @param string $var имя переменной в шаблонизаторе
     * @param int $n ИД характеристики для вывода значений
     * @param string $title заголовок блока
     * @param string $target цель формы [/selection/  |  /selectioncat/]
     */
    function brand($var, $n, $title, $target = '/selection/') {

        // ИД характеристики для вывода значений
        $this->n = $n;

        // Подгружаем библиотеку
        PHPShopObj::loadClass('sort');

        $PHPShopSort = new PHPShopSort();
        $value = $PHPShopSort->value($n, $title);
        $forma = PHPShopText::p(PHPShopText::form($value . PHPShopText::button('OK', 'SortSelect.submit()'), 'SortSelect', 'get', $target, false, 'ok'));
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $title);

        // Подключаем шаблон
        $dis = $this->parseTemplate($this->getValue('templates.left_menu'));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $value);

        // Назначаем переменную шаблона
        $this->set($var, $dis);
    }

}

/**
 * Элемент оформления вывода товаров в колонку
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductIconElements
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopProductIconElements extends PHPShopProductElements {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;

    /**
     * Память событий
     * @var bool
     */
    var $memory = true;

    /**
     * шаблон товара
     * @var string 
     */
    var $template = 'main_spec_forma_icon';

    /**
     * ограничение на вывод
     * @var string 
     */
    var $limitspec;

    /**
     * сетка товара [1-5]
     * @var int 
     */
    var $celll;

    /**
     * Констурктор
     */
    function PHPShopProductIconElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();
    }

    /**
     * Элемент "Спецпредложения-Новинки" для всех страниц
     * @param bool $force параметр отображения для подробного описания товара
     * @param int $category Ид категории для выборки
     * @param int $cell сетка товара [1-5]
     * @param int $limit ограничение на вывод
     * @return string
     */
    function specMainIcon($force = false, $category = null, $cell = 3, $limit = null, $line = false) {

        $this->limitspec = $limit;
        $this->cell = $cell;


        // Условие вывода из текущего каталога
        if ($GLOBALS['SysValue']['nav']['path'] == 'shop') {

            switch ($GLOBALS['SysValue']['nav']['nav']) {

                // Раздел списка товаров
                case "CID":

                    if (!empty($category))
                        $where['category'] = '=' . $category;

                    elseif (PHPShopSecurity::true_num($this->PHPShopNav->getId())) {
                        $category = $this->PHPShopNav->getId();
                        $where['category'] = '=' . $category;
                    }
                    break;

                // Раздел подробного описания
                case "UID":
                    if (empty($force))
                        return false;
                    else
                        $where['category'] = '=' . $category;

                    $where['id'] = '!=' . $this->PHPShopNav->getId();
                    break;
            }
        }

        // Кол-во товаров на странице
        if (empty($this->limitspec))
            $this->limitspec = $this->PHPShopSystem->getParam('new_num');

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;


        // Завершение если отключен вывод
        if (empty($this->limitspec))
            return false;

        // Случайные товары для больших баз
        //$where['id']=$this->setramdom($limit);
        // Параметры выборки учета товара в новинках и наличия
        $where['newtip'] = "='1'";
        $where['enabled'] = "='1'";
        $where['parent_enabled'] = "='0'";

        // Проверка на единичную выборку
        if ($limit == 1) {
            $array_pop = true;
            $limit++;
        }

        // Память режима выборки новинок из каталогов
        $memory_spec = $this->memory_get('product_spec.' . $category);

        // Выборка новинок
        if ($memory_spec != 2 and $memory_spec != 3)
            $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

        // Проверка на единичную выборку
        if (!empty($array_pop) and is_array($this->dataArray)) {
            array_pop($this->dataArray);
        }

        if (!empty($this->dataArray) and is_array($this->dataArray)) {
            $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
            $this->set('specMainTitle', $this->lang('newprod'));

            // Заносим в память
            $this->memory_set('product_spec.' . $category, 1);
        } else {
            // Выборка спецпредложение
            unset($where['newtip']);
            $where['spec'] = "='1'";

            if ($memory_spec != 1 and $memory_spec != 3)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

            // Проверка на единичную выборку
            if (!empty($array_pop) and is_array($this->dataArray)) {
                array_pop($this->dataArray);
            }

            if (!empty($this->dataArray) and is_array($this->dataArray)) {
                $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                $this->set('specMainTitle', $this->lang('specprod'));

                // Заносим в память
                $this->memory_set('product_spec.' . $category, 2);
            } else {
                // Выборка последних добавленных товаров
                unset($where['id']);
                unset($where['spec']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => $this->limitspec), __FUNCTION__);

                // Проверка на единичную выборку
                if (!empty($array_pop) and is_array($this->dataArray)) {
                    array_pop($this->dataArray);
                }

                if (is_array($this->dataArray)) {
                    $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                    $this->set('specMainTitle', $this->lang('newprod'));

                    // Заносим в память
                    $this->memory_set('product_spec.' . $category, 3);
                }
            }
        }

        // Собираем и возвращаем таблицу с товарами
        return $this->compile();
    }

    /**
     * Элемент простой формы вывода товаров (заготовка)
     * @param array $row массив данных товаров
     * @param int $cell разрядность сетки [1|2|3|4|5]
     * @param string $template шаблон вывода
     * @param bool $line наличие разделителя между сетками
     * @return string
     */
    function seamply_forma($row, $cell = false, $template = 'main_spec_forma_icon', $line = false) {

        // Количество ячеек для вывода товара
        if (empty($cell))
            $cell = $this->PHPShopSystem->getParam('num_vitrina');

        $this->set('productInfo', $this->lang('productInfo'));

        // Добавляем в дизайн ячейки с товарами
        $this->product_grid($row, $cell, $template, $line);

        // Собираем и возвращаем таблицу с товарами
        return $this->compile();
    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null, $d6 = null, $d7 = null) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5, $d6, $d7);
    }

    /**
     * Сбор данных по товарам в таблицу
     * @return string
     */
    function compile() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook) {
            return $hook;
        }

        return parent::compile();
    }

}

/**
 * Элемент оформления вывода товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductIndexElements
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopProductIndexElements extends PHPShopProductElements {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;

    /**
     * Сетка товара
     * @var int
     */
    var $cell;

    /**
     * Память событий
     * @var bool
     */
    var $memory = false;

    /**
     * Констурктор
     */
    function PHPShopProductIndexElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();
    }

    /**
     * Шаблон компактного вывода "Сейчас покупают"
     * @param array $row массив данных
     * @return string
     */
    function template_nowbuy($row) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
        if ($hook)
            return $hook;

        return PHPShopText::li($row['name'], 'shop/UID_' . $row['id'] . '.html');
    }

    /**
     * Элемент "сейчас покупают" для главной страницы
     * @return string
     */
    function nowBuy() {

        // Проверка запуска главной страницы
        if ($this->PHPShopNav->index()) {
            $i = 1;
            //$this->limitpos = 9; // Количество выводимых позиций
            //$this->limitorders = 9; // Количество запрашиваемых заказов
            
            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->sql = "select num_row from " . $GLOBALS['SysValue']['base']['system'] . " LIMIT 1";
            $data = $PHPShopOrm->select();
            if (is_array($data)) {
            	foreach ($data as $row) {
            		$this->limitpos=$row['num_row'];
            		$this->limitorders=$row['num_row'];
            	}
            } else {
            	$this->limitpos = 9; // Количество выводимых позиций
            	$this->limitorders = 9; // Количество запрашиваемых заказов
            }        

            $disp = $li = null;
            $enabled = $this->PHPShopSystem->getSerilizeParam('admoption.nowbuy_enabled');
            $sort = null;

            // Перехват модуля
            $hook = $this->setHook(__CLASS__, __FUNCTION__);
            if ($hook)
                return $hook;

            // Количество ячеек
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

            if (!empty($enabled)) {

                // Последние заказы
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $PHPShopOrm->debug = $this->debug;
                $data = $PHPShopOrm->select(array('orders'), false, array('order' => 'id desc'), array('limit' => $this->limitorders));

                if (is_array($data)) {
                    foreach ($data as $row) {
                        $order = unserialize($row['orders']);
                        $cart = $order['Cart']['cart'];
                        if (is_array($cart))
                            foreach ($cart as $good) {
                                if ($i > $this->limitpos)
                                    break;
                                $sort.=' id=' . intval($good['id']) . ' OR';
                            }
                    }
                    $sort = substr($sort, 0, strlen($sort) - 2);

                    // Если есть товары
                    if (!empty($sort)) {
                        $PHPShopOrm = new PHPShopOrm();
                        $PHPShopOrm->debug = $this->debug;
                        $PHPShopOrm->sql = "select * from " . $this->objBase . " where (" . $sort . ") and enabled='1' LIMIT 0," . $this->limitpos;
                        $PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
                        $dataArray = $PHPShopOrm->select();
                        if (is_array($dataArray)) {

                            // Товары таблицей
                            if ($enabled == 2) {

                                // Количество ячеек для вывода товара
                                if (empty($this->cell))
                                    $this->cell = $this->PHPShopSystem->getParam('num_vitrina');
                                $this->set('productInfo', $this->lang('productInfo'));

                                // Добавляем в дизайн ячейки с товарами
                                $this->product_grid($dataArray, $this->cell);

                                // Собираем и возвращаем таблицу с товарами
                                $disp = $this->compile();
                            }
                            // Товары списком
                            else {
                                foreach ($dataArray as $row) {
                                    $li.=$this->template_nowbuy($row);
                                    $i++;
                                }

                                $disp = PHPShopText::ol($li);
                            }

                            return $disp;
                        }
                    }
                }
            }
        }
    }

    /**
     * Проверка прав каталога режима Multibase
     * @param int $category
     * @return boolean 
     */
    function randMultibase() {

        $multi_cat = null;

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            $where['parent_to'] = " > 0";
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->cache = true;
            $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 1),__CLASS__,__FUNCTION__);
            if (is_array($data)) {
                foreach ($data as $row) {
                    $multi_cat='=' . $row['id'];
                }
            }

            return $multi_cat;
        }
    }

    /**
     * Элемент "Спецпредложения" на главную страницу
     * @return string
     */
    function specMain() {

        // Проверка запуска главной страницы
        if ($this->PHPShopNav->index()) {

            // Количество ячеек для вывода товара
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getParam('num_vitrina');

            // Кол-во товаров на странице
            $this->limit = $this->PHPShopSystem->getParam('spec_num');

            // Завершение если отключен вывод
            if ($this->limit < 1)
                return false;

            // Перехват модуля
            $hook = $this->setHook(__CLASS__, __FUNCTION__);
            if ($hook)
                return $hook;

            $this->set('productInfo', $this->lang('productInfo'));

            // Случайные товары
            $where['id'] = $this->setramdom($this->limit);

            // Параметры выборки учета товара в спецпредложении и наличия
            $where['spec'] = "='1'";
            $where['enabled'] = "='1'";
            
            $randMultibase = $this->randMultibase();
            if(!empty($randMultibase))
                $where['category'] = $randMultibase;


            // Выборка
            if ($this->limit > 1)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);
            else
                $this->dataArray[] = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);

            // Вторая попытка вывести спецпредложения, оптимизатор RAND выключен
            $count = count($this->dataArray);
            if ($count < $this->limit) {
                unset($where['id']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);
            }


            // Добавляем в дизайн ячейки с товарами
            $this->product_grid($this->dataArray, $this->cell);

            // Собираем и возвращаем таблицу с товарами
            return $this->compile();
        }
    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null, $d6 = null, $d7 = null) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5, $d6, $d7);
    }

    /**
     * Сбор данных по товарам в таблицу
     * @return string
     */
    function compile() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook) {
            return $hook;
        }

        return parent::compile();
    }

}

/**
 * Элемент оформления дерева категорий товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShopCatalogElement
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopShopCatalogElement extends PHPShopProductElements {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;
    var $cache = false;

    /**
     * Массив полей для очистки в кэше для оптимизации кэша. Вырезаем описание каталога и YML настройки.
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');
    var $memory = true;

    /**
     * Проверять на единичные каталоги. [false] - для больших каталогов, сокращает запросы к БД
     * @var bool
     */
    var $chek_catalog = true;
    var $grid = true;

    /**
     * Загружает массив из конфигурационного файла menu-lvl1-href-modify_catalog_add-analog.txt
     * @var array
     */

    private $custom_menu_1=array();

    /**
     * Конструктор
     */
    function PHPShopShopCatalogElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['categories'];
        parent::PHPShopElements();
    }

    /**
     * Шаблон вывода категорий каталогов с иконками
     * @param array $val массив данных
     * @return string
     */
    function template_cat_table($val) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $val);
        if ($hook)
            return $hook;

        return PHPShopText::a('/shop/CID_' . $val['id'] . '.html', $val['name'], $val['name']) . ' | ';
    }

    
    /**
     * Форма ячеек для leftCatalTable
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5);
    }
    
    /**
     * Таблица категорий с иконками
     * @return string
     */
    function leftCatalTable() {

        // Выполнение только в Index
        if ($this->PHPShopNav->index()) {

            $dis = null;
            $podcatalog = null;

            $this->cell = $this->PHPShopSystem->getParam('num_row_adm');

            $table = null;
            $j = 1;
            $item = 1;

            // Перехват модуля
            $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
            if ($hook)
                return $hook;

            if (is_array($this->data))
                foreach ($this->data as $row) {
                    $dis = null;
                    $podcatalog = null;
                    $this->set('catalogId', $row['id']);
                    $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47));
                    $this->set('catalogTitle', $row['name']);
                    $this->set('catalogName', $row['name']);

                    // Проверка на наличие иконки в описании категории
                    if (stristr($row['content'], 'img') and strlen($row['content']) < 150)
                        $this->set('catalogContent', $row['content']);
                    else
                        $this->set('catalogContent', null);

                    // Обход массива категорий из кэша, список подкаталогов
                    if (is_array($GLOBALS['Cache'][$this->objBase]))
                        foreach ($GLOBALS['Cache'][$this->objBase] as $val) {
                            if ($val['parent_to'] == $row['id'])
                                $podcatalog.=$this->template_cat_table($val);
                        }

                    $this->set('catalogPodcatalog', $podcatalog);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // Подключаем шаблон
                    $dis.= ParseTemplateReturn("catalog/catalog_table_forma.tpl");

                    // Ячейки с каталогами (1-5)
                    if ($j < $this->cell) {
                        $cell_name = 'd' . $j;
                        $$cell_name = $dis;
                        $j++;
                        if ($item == count($this->data)) {
                            $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                        }
                    } else {
                        $cell_name = 'd' . $j;
                        $$cell_name = $dis;
                        $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                        $d1 = $d2 = $d3 = $d4 = $d5 = null;
                        $j = 1;
                    }
                    $item++;
                }

            $this->product_grid = $table;
            return $this->compile();
        }
    }

    /**
     * Вывод навигации каталогов
     * @param array $replace массив замены стилей
     * @param array $where массив параметров выборки, используется для вывода определенного каталога
     * PHPShopShopCatalogElement::leftCatal(false,$where['id']=1);
     * @return string
     */
    function leftCatal($replace = null, $where = null) {
        $dis = null;
        $i = 0;

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $where, 'START');
        if ($hook)
            return $hook;

        // Параметр выборки
        if (empty($where))
            $where['parent_to'] = '=0';

        // Не выводить скрытые каталоги
        $where['skin_enabled '] = "!='1'";

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $this->data = $PHPShopOrm->select(array('id',"case name_rambler when '' then name else name_rambler end as name",'num','parent_to','yml','num_row','num_cow','sort','content','vid','name_rambler','servers','title','title_enabled','title_shablon','descrip','descrip_enabled','descrip_shablon','keywords','keywords_enabled','keywords_shablon','skin','skin_enabled','order_by','order_to','secure_groups','content_h','filtr','icon_description'), $where, array('order' => 'num'), array("limit" => 100), __CLASS__, __FUNCTION__);
        if (is_array($this->data)){
            //читаем данные из файла конфигурации по кастомизированным меню
            $this->custom_menu_1=array();
            $this->custom_menu_1=custom_menu_1($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-lvl1-href-modify_catalog_add-analog.txt');
            //print_r($this->custom_menu_1);
            foreach ($this->data as $row) {
                //print_r($row['name']);
		$dis.=$this->custommenuoutput($row,$i);
                $i++;               
            }
        }

/*
        // Замена стилей
        if (is_array($replace)) {
            foreach ($replace as $key => $val)
                $dis = str_replace($key, $val, $dis);
        }
*/
		// Перехват модуля
		$hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'END');
		if ($hook)
			return $hook;
			
        return $dis;
    }

    /**
     * Вывод подкаталогов по типу
     * @param int $n ИД каталога
     * @return string
     */
    function subcatalog($n) {

    	//переменные для построения кастомизированных ссылок типа: товар (аналог)
    	$id_custom_menu_array=array();
    	$id_custom_menu_1_true=false;
    	$dis = null;
        
        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        //if ($hook)
        //	return $hook;

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $where['parent_to'] = '=' . $n;

        // Не выводить скрытые каталоги
        $where['skin_enabled'] = "!='1'";

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $data = $PHPShopOrm->select(array('id',"case name_rambler when '' then name else name_rambler end as name",'num','parent_to','yml','num_row','num_cow','sort','content','vid','name_rambler','servers','title','title_enabled','title_shablon','descrip','descrip_enabled','descrip_shablon','keywords','keywords_enabled','keywords_shablon','skin','skin_enabled','order_by','order_to','secure_groups','content_h','filtr','icon_description'), $where, array('order' => 'num'), array('limit' => 100), __CLASS__, __FUNCTION__);
	//print_r($data);
        $cnt=0;
        //собираем массив для предварительной сортировки по правилу сначала русские элементы потом английские
        $array_submenuhead=array();
        if(is_array($data)){
            foreach($data as $row) {
                // Каталог по типу
                if (strpos($row['name'],"(")===false)
                {
                    $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');
                    if (!empty($hook))
                    $row['name']=trim($hook);
                    //$data[$cnt]['name']=trim($hook);
                    $array_submenuhead[]=array('cnt'=>$cnt,'category_id'=>$row[category_id],'id'=>$row[id],'name'=>$row[name]);
                }
                $cnt++;
            }
        }

        if (count($array_submenuhead)>1) {
            if ($n==472 && CustomSortOrder==1)
                usort($array_submenuhead, PHPShopText::array_submenuhead2_cmp('name'));
            else
                usort($array_submenuhead, PHPShopText::array_submenuhead1_cmp('name'));
        }
        
        foreach($array_submenuhead as $row) {
            $id_custom_menu_1_true=false;

                foreach ($this->custom_menu_1 as $custom_menu_1_item) {
                    //делаем исключения
                    if (in_array($row['id'],$custom_menu_1_item)===true) {
                        if ($custom_menu_1_item['id']==$row['id'] && in_array($row['id'],$id_custom_menu_array)===false) {
                            $custom_href=$custom_menu_1_item['href'];
                            $custom_css_option_width=$custom_menu_1_item['css_option_width'];

                            $this->set('catalogName',$row['name'].$custom_href);
                            $this->set('width',"$custom_css_option_width");
                            $this->set('catalogUid',$row['id']);
                            $this->set('catalogTitle',$row['name']);

                            // Подключаем шаблон
                            $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
                            $id_custom_menu_1_true=true;
                            array_push($id_custom_menu_array,$custom_menu_1_item['id'],$custom_menu_1_item['sub_id']);
                            break;
                        }
                    }				
                }

                if ($id_custom_menu_1_true===false && in_array($row['id'],$id_custom_menu_array)===false){
                        //echo ' '.$row['id'].' ';
                        $this->set('catalogName',$row['name']);
                        $this->set('width','');
                        $this->set('catalogUid',$row['id']);
                        $this->set('catalogTitle',$row['name']);
                        // Подключаем шаблон
                        $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
                }				

        }
        /*
        if(is_array($data)){
            foreach($data as $row) {
            	$id_custom_menu_1_true=false;
                // Каталог по типу
                if (strpos($row['name'],"(")===false)
                {
                    $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');
                    if (!empty($hook))
                    $row['name']=$hook;
                    foreach ($this->custom_menu_1 as $custom_menu_1_item) {
                        //делаем исключения
                        if (in_array($row['id'],$custom_menu_1_item)===true) {
                            if ($custom_menu_1_item['id']==$row['id'] && in_array($row['id'],$id_custom_menu_array)===false) {
                                $custom_href=$custom_menu_1_item['href'];
                                $custom_css_option_width=$custom_menu_1_item['css_option_width'];

                                $this->set('catalogName',$row['name'].$custom_href);
                                $this->set('width',"$custom_css_option_width");
                                $this->set('catalogUid',$row['id']);
                                $this->set('catalogTitle',$row['name']);

                                // Подключаем шаблон
                                $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
                                $id_custom_menu_1_true=true;
                                array_push($id_custom_menu_array,$custom_menu_1_item['id'],$custom_menu_1_item['sub_id']);
                                break;
                            }
                        }				
                    }

                    if ($id_custom_menu_1_true===false && in_array($row['id'],$id_custom_menu_array)===false){
                            //echo ' '.$row['id'].' ';
                            $this->set('catalogName',$row['name']);
                            $this->set('width','');
                            $this->set('catalogUid',$row['id']);
                            $this->set('catalogTitle',$row['name']);

                            // Подключаем шаблон
                            $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
                    }				
                }
            }		            
        }
         */
        return $dis;
    }

    /**
     * Вывод подкаталогов по производителю
     * @param int $n ИД каталога
     * @return string
     */
    function subcatalog2($n) {

        $dis= null;
        
        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        //if ($hook)
        //	return $hook;
        
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format=$this->cache_format;
        $PHPShopOrm->cache=$this->cache;
        $PHPShopOrm->debug=$this->debug;

        $where['parent_to']='='.$n;
        // Не выводить скрытые каталоги
        $where['skin_enabled'] = "!='1'";
        // Мультибаза
        if($this->PHPShopSystem->ifValue($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled'))) {
            $where['servers']=" REGEXP 'i".$this->PHPShopSystem->getSerilizeParam('admoption.base_id')."i'";
        }

        $data=$PHPShopOrm->select(array('id',"case name_rambler when '' then name else name_rambler end as name",'num','parent_to','yml','num_row','num_cow','sort','content','vid','name_rambler','servers','title','title_enabled','title_shablon','descrip','descrip_enabled','descrip_shablon','keywords','keywords_enabled','keywords_shablon','skin','skin_enabled','order_by','order_to','secure_groups','content_h','filtr','icon_description'),$where,array('order'=>'num'),array('limit'=>100),__CLASS__,__FUNCTION__);

        $cnt=0;
        //собираем массив для предварительной сортировки по правилу сначала русские элементы потом английские
        $array_submenuhead=array();
        if(is_array($data)){
            foreach($data as $row) {
                // Каталог по типу
                if (strpos($row['name'],"(")!==false)
                {
                    //от конца строки ищем (
                    $start_curl_brace_pos=strrpos($row['name'],"(");
                    //от конца строки ищем )
                    $stop_curl_brace_pos=strrpos($row['name'],")");

                    $row['name']=substr_replace($row['name'],'',$start_curl_brace_pos,($stop_curl_brace_pos-$start_curl_brace_pos)+1);
                    
                    $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');
                    if (!empty($hook))
                    $row['name']=trim($hook);
                    //$data[$cnt]['name']=trim($hook);
                    $array_submenuhead[]=array('cnt'=>$cnt,'category_id'=>$row['category_id'],'id'=>$row['id'],'name'=>$row['name']);
                }
                $cnt++;
            }
        }
        //print_r($array_submenuhead);
        if (count($array_submenuhead)>1 && CustomSortOrder==1) 
            usort($array_submenuhead, PHPShopText::array_submenuhead2_cmp('name'));
        elseif (count($array_submenuhead)>1 && CustomSortOrder==0)
            usort($array_submenuhead, PHPShopText::array_submenuhead1_cmp('name'));

        foreach($array_submenuhead as $row) {
            $this->set('catalogName',$row['name']);
            $this->set('catalogUid',$row['id']);
            $this->set('catalogTitle',$row['name']);				
            // Подключаем шаблон
            $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
        }
        return $dis;
    }

    /**
     * Проверка подкатлогов
     * @param Int $id ИД каталога
     * @return bool
     */
    function chek($n) {

        // Если проверка в памяти есть, подкаталогов нет
        if ($this->memory_get('product_enabled.' . $n) == 1)
            return true;
        // Если проверка в памяти есть, подкаталоги есть
        elseif ($this->memory_get('product_enabled.' . $n) == 2)
            return false;
        // Если проверки в памяти нет, запрос к БД
        elseif (!empty($this->chek_catalog)) {

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->cache_format = $this->cache_format;
            $PHPShopOrm->cache = $this->cache;
            $PHPShopOrm->debug = $this->debug;

            $where['parent_to'] = '=' . $n;

            // Мультибаза
            if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
                $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            }

            $num = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 1), __CLASS__, __FUNCTION__);
            if (empty($num['id'])) {
                // Заносим в память
                $this->memory_set('product_enabled.' . $n, 1);
                return true;
            }
            else
                $this->memory_set('product_enabled.' . $n, 2);
        }
    }
	
	function custommenuoutput ($row,$i) {
            //echo CustomSortOrder.'<br>';
                $t1=$this->microtime_float();
		if ($row['id']=='288') {
			$PHPShopOrm->cache=true;
			$PHPShopOrm->cache_format=array('content'); // Удаляем больше данные
			// Определяем переменные
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			$sql="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 37 then 1 when 44 then 2 when 16 then 3 when 5 then 4 when 36 then 5 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (37,44,16,5,36)";
			$sql_1="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 37 then 1 when 44 then 2 when 16 then 3 when 5 then 4 when 36 then 5 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (37,44,16,5,36) and cat1.name not like '%(%)%' order by num";
			$sql_2="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 37 then 1 when 44 then 2 when 16 then 3 when 5 then 4 when 36 then 5 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (37,44,16,5,36) and cat1.name like '%(%)%' order by num";

			//********************************************************************* 37 ***********************************************************************************
			//генерация первого раздела меню "по типу"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql;
			$res1=$this->PHPShopOrm->select();
			foreach ($res1 as $prod_row1) {
				switch ($prod_row1[category_id]) {
					case 1: $parent_id1=$prod_row1[parent_to];
							$parent_cat_name1=$prod_row1[parent_cat_name];
							break;
					case 2:	$parent_id2=$prod_row1[parent_to];
							$parent_cat_name2=$prod_row1[parent_cat_name];
							break;
					case 3:	$parent_id3=$prod_row1[parent_to];
							$parent_cat_name3=$prod_row1[parent_cat_name];
							break;
					case 4:	$parent_id4=$prod_row1[parent_to];
							$parent_cat_name4=$prod_row1[parent_cat_name];
							break;
					case 5:	$parent_id5=$prod_row1[parent_to];
							$parent_cat_name5=$prod_row1[parent_cat_name];
							break;
				}
			}
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>Мотоблоки</span>',true);
			if (strlen($parent_cat_name1)>24) {
				$sdvig_vverh='style="position:relative;top:-14px;"';
			} else {
				$sdvig_vverh='';
			}
			$submenuhead='<li id-info="'.$parent_id1.'" onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span '.$sdvig_vverh.'>'.$parent_cat_name1.'</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li class="inside_menu_head"><a href="/shop/CID_'.$row[id].'.html">'.$row[name].'</a></li>';
			$submenuhead.='<li class="inside_menu_head"><a href="/shop/CID_'.$parent_id1.'.html">'.$parent_cat_name1.'</a></li>';
			$submenuhead.='</ul>';
			//генерация первого раздела меню "по типу"
			$this->PHPShopOrm->cache = false;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();
				
			//$res1=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row1=array();

			//$db_rows1=mysql_num_rows($res1);

			$submenuhead.='<p id="catpage1_'.$parent_id1.'" class="menublock" >По типу:</p><ul id="ul_cat_page_1_'.$parent_id1.'" class="catalogPodcatalog1">';
                        //вывод заголовков меню
			$submenuhead1_1='';
			$submenuhead2_1='';
			$submenuhead3_1='';
			$submenuhead4_1='';
			$submenuhead5_1='';

                        //для изменения значения массива после хука с подстановкой измененного названия в меню
                        $cnt=0;
                        //меню с сортировкой сначала русские названия потом английские
                        $array_submenuhead1_1=array();
                        $array_submenuhead2_1=array();
                        $array_submenuhead3_1=array();
                        $array_submenuhead4_1=array();
                        $array_submenuhead5_1=array();
                        //цикл заносим в массивы меню по типу данные плюс применяем хук с подстановкой измененного названия в меню

			foreach ($res1 as $prod_row1) {
                            // Перехват модуля
                            $hook=$this->setHook(__CLASS__, __FUNCTION__, $prod_row1, 'MIDDLE');
                            if (!empty($hook)){
                            $res1[$cnt][name]=trim($hook);
                            $prod_row1[name]=trim($hook);
                            }
                           //нужно сформировать массив для вывода и в нем осуществить сортировку по полю name
                            if ($prod_row1[category_id]==1) {
                                $array_submenuhead1_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==2) {
                                $array_submenuhead2_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==3) {
                                $array_submenuhead3_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==4) {
                                $array_submenuhead4_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==5) {
                                $array_submenuhead5_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            //print_r($res2);
                            $cnt++;
                        }
                        if (count($array_submenuhead1_1)>1)
                            usort($array_submenuhead1_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead2_1)>1)
                            usort($array_submenuhead2_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead3_1)>1)
                            usort($array_submenuhead3_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead4_1)>1)
                            usort($array_submenuhead4_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead5_1)>1)
                            usort($array_submenuhead5_1, PHPShopText::array_submenuhead1_cmp('name'));
                        
                        reset($array_submenuhead1_1);
                        reset($array_submenuhead2_1);
                        reset($array_submenuhead3_1);
                        reset($array_submenuhead4_1);
                        reset($array_submenuhead5_1);

                        //циклы формируем вывод меню по типу с учетом сортировки
                        foreach ($array_submenuhead1_1 as $prod_row1){
                                $submenuhead1_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead2_1 as $prod_row1) {
                            if ($prod_row1[id]==13) {
                                    $submenuhead2_1.='<li class="inside_menu_head" style="font-size:12px;"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a>&nbsp;';
                                    $submenuhead2_1.='(<a href="/shop/CID_328.html" style="font-size:12px;">Мотокультиваторы</a>)</li>';
                            } else if ($prod_row1[id]!=328 && $prod_row1[id]!=13) {
                                    $submenuhead2_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                            }
                        }
                        foreach ($array_submenuhead3_1 as $prod_row1){
                                $submenuhead3_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead4_1 as $prod_row1){
                                $submenuhead4_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead5_1 as $prod_row1){
                                $submenuhead5_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }

                        $array_submenuhead1_1=array();
                        $array_submenuhead2_1=array();
                        $array_submenuhead3_1=array();
                        $array_submenuhead4_1=array();
                        $array_submenuhead5_1=array();

			$submenuhead.=$submenuhead1_1;
			$submenuhead.='</ul>';
			//генерация второго раздела меню "по производителю"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();
			//$res2=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row2=array();

			//$db_rows2=mysql_num_rows($res2);

			$submenuhead.='<p id="catpage2_'.$parent_id1.'" class="menublock" >По производителю:</p><ul id="ul_cat_page_2_'.$parent_id1.'" class="catalogPodcatalog2">';

			$submenuhead1_2='';
			$submenuhead2_2='';
			$submenuhead3_2='';
			$submenuhead4_2='';
			$submenuhead5_2='';

                        $cnt=0;
                        $array_submenuhead1_2=array();
                        $array_submenuhead2_2=array();
                        $array_submenuhead3_2=array();
                        $array_submenuhead4_2=array();
                        $array_submenuhead5_2=array();
			foreach ($res2 as $prod_row2) {
			    //от конца строки ищем (
			    $start_curl_brace_pos=strrpos($prod_row2[name],"(");
			    //от конца строки ищем )
			    $stop_curl_brace_pos=strrpos($prod_row2[name],")");
					
        		    $prod_row2[name]=trim(substr_replace($prod_row2[name],'',$start_curl_brace_pos,($stop_curl_brace_pos-$start_curl_brace_pos)+1));

                            // Перехват модуля
                            $hook=$this->setHook(__CLASS__, __FUNCTION__, $prod_row2, 'MIDDLE');
                            if (!empty($hook)){
                            $res2[$cnt][name]=trim($hook); 
                            $prod_row2[name]=trim($hook);
                            }
                            //нужно сформировать массив для вывода категории 1 и в нем осуществить сортировку по полю name
                            if ($prod_row2[category_id]==1) {
                                $array_submenuhead1_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==2) {
                                $array_submenuhead2_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==3) {
                                $array_submenuhead3_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==4) {
                                $array_submenuhead4_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==5) {
                                $array_submenuhead5_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            //print_r($res2);
                            $cnt++;
                        }
                        //$t1=$this->microtime_float();
                        //echo microtime().'<br>';
                        if (count($array_submenuhead1_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead1_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead2_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead2_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead2_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead2_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead3_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead3_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead3_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead3_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead4_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead4_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead1_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead4_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead5_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead5_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead5_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead5_2, PHPShopText::array_submenuhead1_cmp('name'));
                        //$t2=$this->microtime_float();
                        //echo microtime().'<br>';
                        //$t3=$t2-$t1;
                        //echo $t3.'<br>';
                        //print_r($array_submenuhead1_2);
                        //print_r($array_submenuhead1_2);
                        reset($array_submenuhead1_2);
                        reset($array_submenuhead2_2);
                        reset($array_submenuhead3_2);
                        reset($array_submenuhead4_2);
                        reset($array_submenuhead5_2);
                        foreach ($array_submenuhead1_2 as $prod_row2){
       				$submenuhead1_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead2_2 as $prod_row2){
                                $submenuhead2_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead3_2 as $prod_row2){
                                $submenuhead3_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead4_2 as $prod_row2){
                                $submenuhead4_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead5_2 as $prod_row2){
                                $submenuhead5_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        $array_submenuhead1_2=array();
                        $array_submenuhead2_2=array();
                        $array_submenuhead3_2=array();
                        $array_submenuhead4_2=array();
                        $array_submenuhead5_2=array();
                        //echo $submenuhead1_2;
                        //reset($res2);
                        //sort($res2,SORT_LOCALE_STRING);

			$submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';

			//$sql_1="select * from `phpshop_categories` where parent_to=44 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=44 and name like '%(%)%' order by num";
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id2,$parent_cat_name2,$row[id],$row[name],$submenuhead2_1,$submenuhead2_2);
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id3,$parent_cat_name3,$row[id],$row[name],$submenuhead3_1,$submenuhead3_2);
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id4,$parent_cat_name4,$row[id],$row[name],$submenuhead4_1,$submenuhead4_2);
						
			$submenuhead.=$this->custommenuoutput_submenu($parent_id5,$parent_cat_name5,$row[id],$row[name],$submenuhead5_1,$submenuhead5_2);
			
			$this->set('sub_li',$submenuhead,true);
			
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);
			$this->set('catalogid_4',$row['id']);
			// Перехват модуля
			$this->setHook(__CLASS__, __FUNCTION__, $row, 'END'); 		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));
			//$this->memory_set('sub_li',$submenuhead);                       
		} 
		else if ($row['id']=='290') {

			//if ($this->memory_get('sub_li_290',true)==1) {

			// Определяем переменные
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));
			
			$sql="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 18 then 1 when 85 then 2 when 215 then 3 when 299 then 4 when 297 then 5 when 254 then 6 when 272 then 7 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (18,85,215,299,297,254,272)";
			$sql_1="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 18 then 1 when 85 then 2 when 215 then 3 when 299 then 4 when 297 then 5 when 254 then 6 when 272 then 7 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (18,85,215,299,297,254,272) and name not like '%(%)%' order by num";
			$sql_2="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 18 then 1 when 85 then 2 when 215 then 3 when 299 then 4 when 297 then 5 when 254 then 6 when 272 then 7 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (18,85,215,299,297,254,272) and name like '%(%)%' order by num";

			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql;
			$res1=$this->PHPShopOrm->select();
			
			foreach ($res1 as $prod_row1) {
				switch ($prod_row1[category_id]) {
					case 1: $parent_id1=$prod_row1[parent_to];
					$parent_cat_name1=$prod_row1[parent_cat_name];
					break;
					case 2:	$parent_id2=$prod_row1[parent_to];
					$parent_cat_name2=$prod_row1[parent_cat_name];
					break;
					case 3:	$parent_id3=$prod_row1[parent_to];
					$parent_cat_name3=$prod_row1[parent_cat_name];
					break;
					case 4:	$parent_id4=$prod_row1[parent_to];
					$parent_cat_name4=$prod_row1[parent_cat_name];
					break;
					case 5:	$parent_id5=$prod_row1[parent_to];
					$parent_cat_name5=$prod_row1[parent_cat_name];
					break;
					case 6:	$parent_id6=$prod_row1[parent_to];
					$parent_cat_name6=$prod_row1[parent_cat_name];
					break;
					case 7:	$parent_id7=$prod_row1[parent_to];
					$parent_cat_name7=$prod_row1[parent_cat_name];
					break;	
				}
			}
				
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>Газонокосилки</span>',true);
			if (strlen($parent_cat_name1)>24) {
				$sdvig_vverh='style="position:relative;top:-14px;"';
			} else {
				$sdvig_vverh='';
			}
			$submenuhead='<li id-info="'.$parent_id1.'" onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span '.$sdvig_vverh.'>'.$parent_cat_name1.'</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li class="inside_menu_head"><a href="/shop/CID_'.$row[id].'.html">'.$row[name].'</a></li>';
			$submenuhead.='<li class="inside_menu_head"><a href="/shop/CID_'.$parent_id1.'.html">'.$parent_cat_name1.'</a></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 18 ***********************************************************************************					
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();
				

			//$this->PHPShopOrm->clean();

			//$prod_row1=array();

			//$db_rows1=mysql_num_rows($res1);

			$submenuhead.='<p id="catpage1_'.$parent_id1.'" class="menublock" >По типу:</p><ul id="ul_cat_page_1_'.$parent_id1.'" class="catalogPodcatalog1">';

			$submenuhead1_1='';
			$submenuhead2_1='';
			$submenuhead3_1='';			
			$submenuhead4_1='';
			$submenuhead5_1='';	
			$submenuhead6_1='';	
			$submenuhead7_1='';

                        //для изменения значения массива после хука с подстановкой измененного названия в меню
                        $cnt=0;
                        //меню с сортировкой сначала русские названия потом английские
                        $array_submenuhead1_1=array();
                        $array_submenuhead2_1=array();
                        $array_submenuhead3_1=array();
                        $array_submenuhead4_1=array();
                        $array_submenuhead5_1=array();
                        $array_submenuhead6_1=array();
                        $array_submenuhead7_1=array();
                        //цикл заносим в массивы меню по типу данные плюс применяем хук с подстановкой измененного названия в меню

			foreach ($res1 as $prod_row1) {
                            // Перехват модуля
                            $hook=$this->setHook(__CLASS__, __FUNCTION__, $prod_row1, 'MIDDLE');
                            if (!empty($hook)){
                            $res1[$cnt][name]=trim($hook);
                            $prod_row1[name]=trim($hook);
                            }
                           //нужно сформировать массив для вывода и в нем осуществить сортировку по полю name
                            if ($prod_row1[category_id]==1) {
                                $array_submenuhead1_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==2) {
                                $array_submenuhead2_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==3) {
                                $array_submenuhead3_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==4) {
                                $array_submenuhead4_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==5) {
                                $array_submenuhead5_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==6) {
                                $array_submenuhead6_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==7) {
                                $array_submenuhead7_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            //print_r($res2);
                            $cnt++;
                        }
                        if (count($array_submenuhead1_1)>1)
                            usort($array_submenuhead1_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead2_1)>1)
                            usort($array_submenuhead2_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead3_1)>1)
                            usort($array_submenuhead3_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead4_1)>1)
                            usort($array_submenuhead4_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead5_1)>1)
                            usort($array_submenuhead5_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead6_1)>1)
                            usort($array_submenuhead6_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead7_1)>1)
                            usort($array_submenuhead7_1, PHPShopText::array_submenuhead1_cmp('name'));
                        
                        reset($array_submenuhead1_1);
                        reset($array_submenuhead2_1);
                        reset($array_submenuhead3_1);
                        reset($array_submenuhead4_1);
                        reset($array_submenuhead5_1);
                        reset($array_submenuhead6_1);
                        reset($array_submenuhead7_1);

                        //циклы формируем вывод меню по типу с учетом сортировки
                        foreach ($array_submenuhead1_1 as $prod_row1){
                                $submenuhead1_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead2_1 as $prod_row1) {
				if ($prod_row1[id]==35) {
					$submenuhead2_1.='<li class="inside_menu_head" style="font-size:12px;"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a>&nbsp;';
					$submenuhead2_1.='(<a href="/shop/CID_333.html" style="font-size:12px;">Мотокосы</a>,&nbsp;';
					$submenuhead2_1.='<a href="/shop/CID_334.html" style="font-size:12px;">Бензокосы</a>)</li>';
				} else if ($prod_row1[id]!=333 && $prod_row1[id]!=334 && $prod_row1[id]!=35) {
					$submenuhead2_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
				}
                        }
                        foreach ($array_submenuhead3_1 as $prod_row1){
                                $submenuhead3_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead4_1 as $prod_row1){
                                $submenuhead4_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead5_1 as $prod_row1){
                                $submenuhead5_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead6_1 as $prod_row1){
                                $submenuhead6_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead7_1 as $prod_row1){
                                $submenuhead7_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        $array_submenuhead1_1=array();
                        $array_submenuhead2_1=array();
                        $array_submenuhead3_1=array();
                        $array_submenuhead4_1=array();
                        $array_submenuhead5_1=array();
                        $array_submenuhead6_1=array();
                        $array_submenuhead7_1=array();
                        
			$submenuhead.=$submenuhead1_1;
			
			$submenuhead.='</ul>';
			//генерация второго раздела меню "по производителю"	
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();			
			//$res2=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row2=array();

			//$db_rows2=mysql_num_rows($res2);

			$submenuhead.='<p id="catpage2_'.$parent_id1.'" class="menublock" >По производителю:</p><ul id="ul_cat_page2_'.$parent_id1.'"  class="catalogPodcatalog2">';
			$submenuhead1_2='';
			$submenuhead2_2='';
			$submenuhead3_2='';
			$submenuhead4_2='';
			$submenuhead5_2='';
			$submenuhead6_2='';
			$submenuhead7_2='';
                        $cnt=0;
                        $array_submenuhead1_2=array();
                        $array_submenuhead2_2=array();
                        $array_submenuhead3_2=array();
                        $array_submenuhead4_2=array();
                        $array_submenuhead5_2=array();
                        $array_submenuhead6_2=array();
                        $array_submenuhead7_2=array();
			foreach ($res2 as $prod_row2) {
			    //от конца строки ищем (
			    $start_curl_brace_pos=strrpos($prod_row2[name],"(");
			    //от конца строки ищем )
			    $stop_curl_brace_pos=strrpos($prod_row2[name],")");
					
        		    $prod_row2[name]=trim(substr_replace($prod_row2[name],'',$start_curl_brace_pos,($stop_curl_brace_pos-$start_curl_brace_pos)+1));

                            // Перехват модуля
                            $hook=$this->setHook(__CLASS__, __FUNCTION__, $prod_row2, 'MIDDLE');
                            if (!empty($hook)){
                            $res2[$cnt][name]=trim($hook); 
                            $prod_row2[name]=trim($hook);
                            }
                            //нужно сформировать массив для вывода категории 1 и в нем осуществить сортировку по полю name
                            if ($prod_row2[category_id]==1) {
                                $array_submenuhead1_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==2) {
                                $array_submenuhead2_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==3) {
                                $array_submenuhead3_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==4) {
                                $array_submenuhead4_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==5) {
                                $array_submenuhead5_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==6) {
                                $array_submenuhead6_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==7) {
                                $array_submenuhead7_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            //print_r($res2);
                            $cnt++;
                        }
                        //$t1=$this->microtime_float();
                        //echo microtime().'<br>';
                        if (count($array_submenuhead1_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead1_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead2_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead2_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead2_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead3_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead3_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead3_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead3_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead4_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead4_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead4_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead4_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead5_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead5_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead5_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead5_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead6_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead6_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead6_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead6_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead7_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead7_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead7_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead7_2, PHPShopText::array_submenuhead1_cmp('name'));
                        //$t2=$this->microtime_float();
                        //echo microtime().'<br>';
                        //$t3=$t2-$t1;
                        //echo $t3.'<br>';
                        //print_r($array_submenuhead1_2);
                        //print_r($array_submenuhead1_2);
                        reset($array_submenuhead1_2);
                        reset($array_submenuhead2_2);
                        reset($array_submenuhead3_2);
                        reset($array_submenuhead4_2);
                        reset($array_submenuhead5_2);
                        reset($array_submenuhead6_2);
                        reset($array_submenuhead7_2);
                        foreach ($array_submenuhead1_2 as $prod_row2){
       				$submenuhead1_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead2_2 as $prod_row2){
                                $submenuhead2_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead3_2 as $prod_row2){
                                $submenuhead3_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead4_2 as $prod_row2){
                                $submenuhead4_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead5_2 as $prod_row2){
                                $submenuhead5_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead6_2 as $prod_row2){
                                $submenuhead6_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead7_2 as $prod_row2){
                                $submenuhead7_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        $array_submenuhead1_2=array();
                        $array_submenuhead2_2=array();
                        $array_submenuhead3_2=array();
                        $array_submenuhead4_2=array();
                        $array_submenuhead5_2=array();
                        $array_submenuhead6_2=array();
                        $array_submenuhead7_2=array();                        

			$submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select id,name from `phpshop_categories` where parent_to=85 and name not like '%(%)%'";
			//$sql_2="select id,name from `phpshop_categories` where parent_to=85 and name like '%(%)%' order by num";			
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id2,$parent_cat_name2,$row[id],$row[name],$submenuhead2_1,$submenuhead2_2);
						
			$submenuhead.=$this->custommenuoutput_submenu($parent_id3,$parent_cat_name3,$row[id],$row[name],$submenuhead3_1,$submenuhead3_2);
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id4,$parent_cat_name4,$row[id],$row[name],$submenuhead4_1,$submenuhead4_2);
				
			$submenuhead.=$this->custommenuoutput_submenu($parent_id5,$parent_cat_name5,$row[id],$row[name],$submenuhead5_1,$submenuhead5_2);

			$submenuhead.=$this->custommenuoutput_submenu($parent_id6,$parent_cat_name6,$row[id],$row[name],$submenuhead6_1,$submenuhead6_2);
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id7,$parent_cat_name7,$row[id],$row[name],$submenuhead7_1,$submenuhead7_2);
						
			$this->set('sub_li',$submenuhead,true);
			//$this->memory_set('sub_li_290',$submenuhead);
			//$this->set('sub_li','</li>',true);

			//} else {
			//	$this->set('sub_li',$this->memory_get('sub_li_290'),true);
			//}
			
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);
			$this->set('catalogid_4',$row['id']);
			// Перехват модуля
			$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));			
		} else if ($row['id']=='292') {
			// Определяем переменные
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			$sql="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 142 then 1 when 293 then 2 when 503 then 3 when 88 then 4 when 224 then 5 when 256 then 6 when 298 then 7 when 172 then 8 when 536 then 9 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (293,88,503,142,172,224,256,298,536)";
			$sql_1="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 142 then 1 when 293 then 2 when 503 then 3 when 88 then 4 when 224 then 5 when 256 then 6 when 298 then 7 when 172 then 8 when 536 then 9 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (293,88,503,142,172,224,256,298,536) and name not like '%(%)%' order by num";
			$sql_2="select id,case name_rambler when '' then name else name_rambler end as name,name_rambler,case cat1.parent_to when 142 then 1 when 293 then 2 when 503 then 3 when 88 then 4 when 224 then 5 when 256 then 6 when 298 then 7 when 172 then 8 when 536 then 9 end as category_id,parent_to,(select name from ".$GLOBALS['SysValue']['base']['categories']." where id=cat1.parent_to limit 1) as parent_cat_name from ".$GLOBALS['SysValue']['base']['categories']." as cat1 where cat1.parent_to in (293,88,503,142,172,224,256,298,536) and name like '%(%)%' order by num";

			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql;
			$res1=$this->PHPShopOrm->select();
				
			foreach ($res1 as $prod_row1) {
				switch ($prod_row1[category_id]) {
					case 1: $parent_id1=$prod_row1[parent_to];
					$parent_cat_name1=$prod_row1[parent_cat_name];
					break;
					case 2:	$parent_id2=$prod_row1[parent_to];
					$parent_cat_name2=$prod_row1[parent_cat_name];
					break;
					case 3:	$parent_id3=$prod_row1[parent_to];
					$parent_cat_name3=$prod_row1[parent_cat_name];
					break;
					case 4:	$parent_id4=$prod_row1[parent_to];
					$parent_cat_name4=$prod_row1[parent_cat_name];
					break;
					case 5:	$parent_id5=$prod_row1[parent_to];
					$parent_cat_name5=$prod_row1[parent_cat_name];
					break;
					case 6:	$parent_id6=$prod_row1[parent_to];
					$parent_cat_name6=$prod_row1[parent_cat_name];
					break;
					case 7:	$parent_id7=$prod_row1[parent_to];
					$parent_cat_name7=$prod_row1[parent_cat_name];
					break;
					case 8:	$parent_id8=$prod_row1[parent_to];
					$parent_cat_name8=$prod_row1[parent_cat_name];
					break;
					case 9:	$parent_id9=$prod_row1[parent_to];
					$parent_cat_name9=$prod_row1[parent_cat_name];
					break;
				}
			}
				
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>Мотопомпы</span>',true);
			if (strlen($parent_cat_name1)>24) {
				$sdvig_vverh='style="position:relative;top:-14px;"';
			} else {
				$sdvig_vverh='';
			}
			$submenuhead='<li id-info="'.$parent_id1.'" onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span '.$sdvig_vverh.'>'.$parent_cat_name1.'</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li class="inside_menu_head"><a href="/shop/CID_'.$row[id].'.html">'.$row[name].'</a></li>';
			$submenuhead.='<li class="inside_menu_head"><a href="/shop/CID_'.$parent_id1.'.html">'.$parent_cat_name1.'</a></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 142 ***********************************************************************************					
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();
				

			$submenuhead.='<p id="catpage1_'.$parent_id1.'" class="menublock" >По типу:</p><ul id="ul_cat_page_1_'.$parent_id1.'" class="catalogPodcatalog1">';

			$submenuhead1_1='';
			$submenuhead2_1='';
			$submenuhead3_1='';
			$submenuhead4_1='';
			$submenuhead5_1='';
			$submenuhead6_1='';
			$submenuhead7_1='';
			$submenuhead8_1='';
			$submenuhead9_1='';
                        //для изменения значения массива после хука с подстановкой измененного названия в меню
                        $cnt=0;
                        //меню с сортировкой сначала русские названия потом английские
                        $array_submenuhead1_1=array();
                        $array_submenuhead2_1=array();
                        $array_submenuhead3_1=array();
                        $array_submenuhead4_1=array();
                        $array_submenuhead5_1=array();
                        $array_submenuhead6_1=array();
                        $array_submenuhead7_1=array();
                        $array_submenuhead8_1=array();
                        $array_submenuhead9_1=array();
                        //цикл заносим в массивы меню по типу данные плюс применяем хук с подстановкой измененного названия в меню

			foreach ($res1 as $prod_row1) {
                            // Перехват модуля
                            $hook=$this->setHook(__CLASS__, __FUNCTION__, $prod_row1, 'MIDDLE');
                            if (!empty($hook)){
                            $res1[$cnt][name]=trim($hook);
                            $prod_row1[name]=trim($hook);
                            }
                           //нужно сформировать массив для вывода и в нем осуществить сортировку по полю name
                            if ($prod_row1[category_id]==1) {
                                $array_submenuhead1_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==2) {
                                $array_submenuhead2_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==3) {
                                $array_submenuhead3_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==4) {
                                $array_submenuhead4_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==5) {
                                $array_submenuhead5_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==6) {
                                $array_submenuhead6_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==7) {
                                $array_submenuhead7_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==8) {
                                $array_submenuhead8_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            if ($prod_row1[category_id]==9) {
                                $array_submenuhead9_1[]=array('cnt'=>$cnt,'category_id'=>$prod_row1[category_id],'id'=>$prod_row1[id],'name'=>$prod_row1[name]);
                            }
                            //print_r($res2);
                            $cnt++;
                        }
                        if (count($array_submenuhead1_1)>1)
                            usort($array_submenuhead1_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead2_1)>1)
                            usort($array_submenuhead2_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead3_1)>1)
                            usort($array_submenuhead3_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead4_1)>1)
                            usort($array_submenuhead4_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead5_1)>1)
                            usort($array_submenuhead5_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead6_1)>1)
                            usort($array_submenuhead6_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead7_1)>1)
                            usort($array_submenuhead7_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead8_1)>1)
                            usort($array_submenuhead8_1, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead9_1)>1)
                            usort($array_submenuhead9_1, PHPShopText::array_submenuhead1_cmp('name'));
                        reset($array_submenuhead1_1);
                        reset($array_submenuhead2_1);
                        reset($array_submenuhead3_1);
                        reset($array_submenuhead4_1);
                        reset($array_submenuhead5_1);
                        reset($array_submenuhead6_1);
                        reset($array_submenuhead7_1);
                        reset($array_submenuhead8_1);
                        reset($array_submenuhead9_1);
                        
                        //циклы формируем вывод меню по типу с учетом сортировки
                        foreach ($array_submenuhead1_1 as $prod_row1){
                             if ($prod_row1[id]==145) {
                                    $submenuhead1_1.='<li class="inside_menu_head" style="font-size:12px;width: 99%;"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a>&nbsp;';
                                    $submenuhead1_1.='(<a href="/shop/CID_426.html" style="font-size:12px;">Бензиновые ямобуры</a>,&nbsp;';
                                    $submenuhead1_1.='<a href="/shop/CID_427.html" style="font-size:12px;">Бензобуры для земляных работ</a>)</li>';
                            } else if ($prod_row1[id]==146) {
                                    $submenuhead1_1.='<li class="inside_menu_head" style="font-size:12px;width: 99%;"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a>&nbsp;';
                                    $submenuhead1_1.='(<a href="/shop/CID_428.html" style="font-size:12px;">Бензобуры для рыбалки</a>,&nbsp;';
                                    $submenuhead1_1.='<a href="/shop/CID_429.html" style="font-size:12px;">Мотобуры для рыбалки</a>)</li>';
                            } else if (($prod_row1[id]!=426 && $prod_row1[id]!=427 && $prod_row1[id]!=145) && ($prod_row1[id]!=428 && $prod_row1[id]!=429 && $prod_row1[id]!=146)) {
                                    $submenuhead1_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                            }
                        }
                        foreach ($array_submenuhead2_1 as $prod_row1) {
                                $submenuhead2_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead3_1 as $prod_row1){
                                $submenuhead3_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead4_1 as $prod_row1){
                                $submenuhead4_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead5_1 as $prod_row1){
                                $submenuhead5_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead6_1 as $prod_row1){
                                $submenuhead6_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead7_1 as $prod_row1){
                                $submenuhead7_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead8_1 as $prod_row1){
                                $submenuhead8_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        foreach ($array_submenuhead9_1 as $prod_row1){
                                $submenuhead9_1.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html">'.$prod_row1[name].'</a></li>';
                        }
                        $array_submenuhead1_1=array();
                        $array_submenuhead2_1=array();
                        $array_submenuhead3_1=array();
                        $array_submenuhead4_1=array();
                        $array_submenuhead5_1=array();
                        $array_submenuhead6_1=array();
                        $array_submenuhead7_1=array();
                        $array_submenuhead8_1=array();
                        $array_submenuhead9_1=array();

			$submenuhead.=$submenuhead1_1;
			$submenuhead.='</ul>';
			//генерация второго раздела меню "по производителю"	
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();			
			//$res2=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row2=array();

			//$db_rows2=mysql_num_rows($res2);

			$submenuhead.='<p id="catpage2_'.$parent_id1.'" class="menublock" >По производителю:</p><ul id="ul_cat_page2_'.$parent_id1.'"  class="catalogPodcatalog2">';

			$submenuhead1_2='';
			$submenuhead2_2='';
			$submenuhead3_2='';
			$submenuhead4_2='';
			$submenuhead5_2='';
			$submenuhead6_2='';
			$submenuhead7_2='';
			$submenuhead8_2='';
			$submenuhead9_2='';
                        $cnt=0;
                        $array_submenuhead1_2=array();
                        $array_submenuhead2_2=array();
                        $array_submenuhead3_2=array();
                        $array_submenuhead4_2=array();
                        $array_submenuhead5_2=array();
                        $array_submenuhead6_2=array();
                        $array_submenuhead7_2=array();
                        $array_submenuhead8_2=array();
                        $array_submenuhead9_2=array();
			foreach ($res2 as $prod_row2) {
			    //от конца строки ищем (
			    $start_curl_brace_pos=strrpos($prod_row2[name],"(");
			    //от конца строки ищем )
			    $stop_curl_brace_pos=strrpos($prod_row2[name],")");
					
        		    $prod_row2[name]=trim(substr_replace($prod_row2[name],'',$start_curl_brace_pos,($stop_curl_brace_pos-$start_curl_brace_pos)+1));

                            // Перехват модуля
                            $hook=$this->setHook(__CLASS__, __FUNCTION__, $prod_row2, 'MIDDLE');
                            if (!empty($hook)){
                            $res2[$cnt][name]=trim($hook); 
                            $prod_row2[name]=trim($hook);
                            }
                            //нужно сформировать массив для вывода категории 1 и в нем осуществить сортировку по полю name
                            if ($prod_row2[category_id]==1) {
                                $array_submenuhead1_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==2) {
                                $array_submenuhead2_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==3) {
                                $array_submenuhead3_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==4) {
                                $array_submenuhead4_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==5) {
                                $array_submenuhead5_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==6) {
                                $array_submenuhead6_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==7) {
                                $array_submenuhead7_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==8) {
                                $array_submenuhead8_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            if ($prod_row2[category_id]==9) {
                                $array_submenuhead9_2[]=array('cnt'=>$cnt,'category_id'=>$prod_row2[category_id],'id'=>$prod_row2[id],'name'=>$prod_row2[name]);
                            }
                            //print_r($res2);
                            $cnt++;
                        }
                        //$t1=$this->microtime_float();
                        //echo microtime().'<br>';
                        if (count($array_submenuhead1_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead1_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead1_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead2_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead2_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead2_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead2_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead3_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead3_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead3_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead3_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead4_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead4_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead4_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead4_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead5_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead5_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead5_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead5_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead6_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead6_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead6_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead6_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead7_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead7_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead7_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead7_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead8_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead8_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead8_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead8_2, PHPShopText::array_submenuhead1_cmp('name'));
                        if (count($array_submenuhead9_2)>1 && CustomSortOrder==1)
                            usort($array_submenuhead9_2, PHPShopText::array_submenuhead2_cmp('name'));
                        elseif (count($array_submenuhead9_2)>1 && CustomSortOrder==0)
                            usort($array_submenuhead9_2, PHPShopText::array_submenuhead1_cmp('name'));
                        //$t2=$this->microtime_float();
                        //echo microtime().'<br>';
                        //$t3=$t2-$t1;
                        //echo $t3.'<br>';
                        //print_r($array_submenuhead1_2);
                        //print_r($array_submenuhead1_2);
                        reset($array_submenuhead1_2);
                        reset($array_submenuhead2_2);
                        reset($array_submenuhead3_2);
                        reset($array_submenuhead4_2);
                        reset($array_submenuhead5_2);
                        reset($array_submenuhead6_2);
                        reset($array_submenuhead7_2);
                        reset($array_submenuhead8_2);
                        reset($array_submenuhead9_2);
                        
                        foreach ($array_submenuhead1_2 as $prod_row2){
       				$submenuhead1_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead2_2 as $prod_row2){
                                $submenuhead2_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead3_2 as $prod_row2){
                                $submenuhead3_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead4_2 as $prod_row2){
                                $submenuhead4_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead5_2 as $prod_row2){
                                $submenuhead5_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead6_2 as $prod_row2){
                                $submenuhead6_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead7_2 as $prod_row2){
                                $submenuhead7_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead8_2 as $prod_row2){
                                $submenuhead8_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        foreach ($array_submenuhead9_2 as $prod_row2){
                                $submenuhead9_2.='<li class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html">'.$prod_row2[name].'</a></li>';
                        }
                        $array_submenuhead1_2=array();
                        $array_submenuhead2_2=array();
                        $array_submenuhead3_2=array();
                        $array_submenuhead4_2=array();
                        $array_submenuhead5_2=array();
                        $array_submenuhead6_2=array();
                        $array_submenuhead7_2=array();                        
                        $array_submenuhead8_2=array();
                        $array_submenuhead9_2=array();

                        $submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id2,$parent_cat_name2,$row[id],$row[name],$submenuhead2_1,$submenuhead2_2);
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id3,$parent_cat_name3,$row[id],$row[name],$submenuhead3_1,$submenuhead3_2);
				
			$submenuhead.=$this->custommenuoutput_submenu($parent_id4,$parent_cat_name4,$row[id],$row[name],$submenuhead4_1,$submenuhead4_2);
			
			$submenuhead.=$this->custommenuoutput_submenu($parent_id5,$parent_cat_name5,$row[id],$row[name],$submenuhead5_1,$submenuhead5_2);
				
			$submenuhead.=$this->custommenuoutput_submenu($parent_id6,$parent_cat_name6,$row[id],$row[name],$submenuhead6_1,$submenuhead6_2);
				
			$submenuhead.=$this->custommenuoutput_submenu($parent_id7,$parent_cat_name7,$row[id],$row[name],$submenuhead7_1,$submenuhead7_2);
				
			$submenuhead.=$this->custommenuoutput_submenu($parent_id8,$parent_cat_name8,$row[id],$row[name],$submenuhead8_1,$submenuhead8_2);
				
			$submenuhead.=$this->custommenuoutput_submenu($parent_id9,$parent_cat_name9,$row[id],$row[name],$submenuhead9_1,$submenuhead9_2);
				
			$this->set('sub_li',$submenuhead,true);
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);
			$this->set('catalogid_4',$row['id']);
			// Перехват модуля
			$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));
		} else {
			// Определяем переменные
			$this->set('catalogId',$row['id']);
			$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			//делим меню каталога на 2 типа
			
			$this->set('catalogPodcatalog',$this->subcatalog($row['id']));
			
			$this->set('catalogPodcatalog2',$this->subcatalog2($row['id']));
			
			$this->set('catalogTitle',$row['name']);

			if ($row['id']=='228') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/stihl_small.png', 0, 'absmiddle','style="float: right; margin-right: 3px; margin-top: -4px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			} else if ($row['id']=='134') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/karcher_small.png', 0, 'absmiddle','style="float: right; margin-right: 1px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			} else if ($row['id']=='295') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/viking_small.png', 0, 'absmiddle','style="float: right; margin-right: 3px; margin-top: -5px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			} else if ($row['id']=='300') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/sale_small.png', 0, 'absmiddle','style="float: right; margin-right: 3px; margin-top: -4px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			} else {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:12px !important;">'.$row['name'].'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','0px');			
			}
			
			// Перехват модуля
			$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
			//echo $this->isAction('leftCatal_hook');
			
			// Если есть подкаталоги
			if ($this->chek($row['id'])) {
				$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_3'));
			}
			// Если нет подкаталогов
			else {
				if ($row['vid'] == 1) {
					$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_2'));
				} else {
					if ($row['id']=='472') {
						$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_5'));						
					} else {
						$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma'));						
					}
				}
			}
		}
                $t2=$this->microtime_float();
                //echo $t2-$t1.'<br>';
		return $dis;
	}

	function custommenuoutput_submenu($parent_id,$parent_cat_name,$row_id,$row_name,$submenuhead1,$submenuhead2) {
		if (strlen($parent_cat_name)>24) {
			$sdvig_vverh='style="position:relative;top:-14px;"';
		} else {
			$sdvig_vverh='';
		}
		$submenuhead.='<li id-info="'.$parent_id.'" onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span '.$sdvig_vverh.'>'.$parent_cat_name.'</span>';
		$submenuhead.='<div class="submenuhead">';
		$submenuhead.='<div class="menutype">';
		$submenuhead.='<ul>';
		$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$row_id.'.html">'.$row_name.'</a></span></li>';
		$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$parent_id.'.html">'.$parent_cat_name.'</a></span></li>';
		$submenuhead.='</ul>';
		//генерация первого раздела меню "по типу"
	
		$submenuhead.='<p id="catpage1_'.$parent_id.'" class="menublock" >По типу:</p><ul id="ul_cat_page_1_'.$parent_id.'" class="catalogPodcatalog1">';

		$submenuhead.=$submenuhead1;
		$submenuhead.='</ul>';
		//генерация второго раздела меню "по производителю"
		
		$submenuhead.='<p id="catpage2_'.$parent_id.'" class="menublock" >По производителю:</p><ul id="ul_cat_page2_'.$parent_id.'"  class="catalogPodcatalog2">';

		$submenuhead.=$submenuhead2;
		$submenuhead.='</ul>';
		$submenuhead.='</div>';
		$submenuhead.='</div>';
		$submenuhead.='</li>';
		return $submenuhead;
	}

    function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}

/**
 * Элемент корзина покупок
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCartElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCartElement extends PHPShopElements {

    /**
     * Конструктор
     * @param bool $order режим корзины в заказе
     */
    function PHPShopCartElement($order = false) {

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();
        $this->order = $order;

        parent::PHPShopElements();
    }

    /**
     *  Мини корзина
     */
    function miniCart() {

        // Если вывод не в разделах офомления заказа
        if ($this->PHPShopNav->notPath(array('order', 'done')) or !empty($this->order)) {

            if (!empty($_SESSION['compare']))
                $compare = $_SESSION['compare'];
            else
                $compare = array();
            $numcompare = 0;

            // Если есть товары в корзине
            if ($this->PHPShopCart->getNum() > 0)
                $this->set('orderEnabled', 'block');
            else
                $this->set('orderEnabled', 'none');

            // Если есть сравнение
            if (count($compare) > 0) {
                if (is_array($compare)) {
                    foreach ($compare as $j => $v) {
                        $numcompare = count($compare);
                    }
                }
                $this->set('compareEnabled', 'block');
            } else {
                $numcompare = "--";
                $this->set('compareEnabled', 'none');
            }

            // Локализация
            $this->set('tovarNow', $this->getValue('lang.cart_tovar_now'));
            $this->set('summaNow', $this->getValue('cart_summa_now'));
            $this->set('orderNow', $this->getValue('cart_order_now'));

            // Сравнение
            $this->set('numcompare', $numcompare);

            // Товаров
            $this->set('num', $this->PHPShopCart->getNum());

            // Сумма
            $this->set('sum', $this->PHPShopCart->getSum());
        }
        else
            $this->set('productValutaName', $this->PHPShopSystem->getDefaultValutaCode(true));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__);
    }

}

/**
 * Элемент смены валюты
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCurrencyElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCurrencyElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopCurrencyElement() {
        parent::PHPShopElements();
        $this->setAction(array('post' => 'valuta'));
    }

    /**
     * Перенаправление формы смены валюты
     */
    function valuta() {
        $_SESSION['valuta'] = intval($_POST['valuta']);
        header("Location: " . $_SERVER['REQUEST_URI']);
    }

    /**
     * Форма выбора валюты
     * @return string
     */
    function valutaDisp() {
        global $PHPShopValutaArray;

        if ($this->PHPShopNav->notPath('order')) {

            if (isset($_SESSION['valuta']))
                $valuta = $_SESSION['valuta'];
            else
                $valuta = $this->PHPShopSystem->getParam('dengi');

            $PHPShopValuta = $PHPShopValutaArray->getArray();

            if (is_array($PHPShopValuta))
                foreach ($PHPShopValuta as $v) {
                    if ($valuta == $v['id'])
                        $sel = "selected";
                    else
                        $sel = false;
                    $value[] = array($v['name'], $v['id'], $sel);
                }

            // Определяем переменные
            $this->set('leftMenuName', 'Валюта');
            $select = PHPShopText::select('valuta', $value, 100, "none", false, "ChangeValuta()");
            $this->set('leftMenuContent', PHPShopText::form($select, 'ValutaForm'));

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $PHPShopValuta);

            // Подключаем шаблон
            $dis = $this->parseTemplate($this->getValue('templates.valuta_forma'));
            return $dis;
        }
    }

}

/**
 * Элемент Облако тегов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCloudElement
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopCloudElement extends PHPShopElements {

    var $debug = false;

    /**
     * Лимит записей для анализа
     * @var int
     */
    var $page_limit = 100;

    /**
     * Лимит слов для вывода
     * @var int
     */
    var $word_limit = 30;

    /**
     * Цвет ссылок облака тегов
     * @var string
     */
    var $color = "0x518EAD";

    /**
     * Конструктор
     */
    function PHPShopCloudElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopElements();
    }

    /**
     * Облако тегов
     * @param array $row массив данных
     * @return string
     */
    function index($row = null) {
        $disp = $dis = $CloudCount = $ArrayWords = $CloudCountLimit = null;
        $ArrayLinks = array();

        // Перехват модуля в начале функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
            return $hook;

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.cloud_enabled')) {
            switch ($GLOBALS['SysValue']['nav']['nav']) {

                case(""):
                    $tip = "search";
                    $str = array('enabled' => "='1'", 'keywords' => " !=''");
                    break;

                case("CID"):
                    $tip = "words";
                    if (empty($row))
                        return false;
                    else
                        $data = $row;
                    break;

                case("UID"):
                    $tip = "words";
                    if (empty($row))
                        return false;
                    else
                        $data[] = $row;
                    break;

                default:
                    $tip = "search";
                    $str = array('enabled' => "='1'", 'keywords' => " !=''");
                    break;
            }

            if (empty($row))
                $data = $this->PHPShopOrm->select(array('keywords', 'id'), $str, false, array("limit" => $this->page_limit), __CLASS__, __FUNCTION__);

            if (is_array($data))
                foreach ($data as $row) {
                    $explode = explode(", ", $row['keywords']);
                    foreach ($explode as $ev)
                        if (!empty($ev)) {
                            $ArrayWords[] = $ev;
                            $ArrayLinks[$ev] = $row['id'];
                        }
                }
            if (is_array($ArrayWords))
                foreach ($ArrayWords as $k => $v) {
                    $count = array_keys($ArrayWords, $v);
                    $CloudCount[$v]['size'] = count($count);
                }

            // Урезаем лишние элементы
            $i = 0;
            if (is_array($CloudCount))
                foreach ($CloudCount as $k => $v) {
                    if ($i < $this->word_limit)
                        $CloudCountLimit[$k] = $v;
                    $i++;
                }

            if (is_array($CloudCountLimit))
                foreach ($CloudCountLimit as $key => $val) {

                    // Чистим теги
                    $key = str_replace('"', '', $key);
                    $key = str_replace("'", '', $key);
                    if ($tip == "words")
                        $disp.='<h1>' . $key . '</h1> ';
                    else
                        $disp.="<a href='/search/?words=" . $key . "' style='font-size:12pt;'>$key</a>";
                }

            // Чистим теги
            $disp = str_replace('\n', '', $disp);

            if ($tip == "search" and !empty($disp))
                $disp = '
<div id="wpcumuluscontent">Загрузка флеш...</div><script type="text/javascript">
var dd=new Date();
 var so = new SWFObject("/stockgallery/tagcloud.swf?rnd="+dd.getTime(), "tagcloudflash", "180", "180", "9", "' . $this->color . '");
so.addParam("wmode", "transparent");
so.addParam("allowScriptAccess", "always");
so.addVariable("tcolor", "' . $this->color . '");
so.addVariable("tspeed", "150");
so.addVariable("distr", "true");
so.addVariable("mode", "tags");
so.addVariable("tagcloud", "<tags>' . $disp . '</tags>");
so.write("wpcumuluscontent");</script>
';

            // Чистим содержание
            $disp = str_replace('\n', '', $disp);
            $disp = str_replace(chr(13), '', $disp);
            $disp = str_replace(chr(10), '', $disp);

            // Определяем переменные
            if (!empty($disp)) {
                $this->set('leftMenuName', __("Облако тегов"));
                $this->set('leftMenuContent', $disp);

                // Перехват модуля в конце функции
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
            }
            return $dis;
        }
    }

}

?>