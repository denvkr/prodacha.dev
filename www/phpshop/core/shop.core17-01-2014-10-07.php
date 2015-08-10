<?php

/**
 * Обработчик товаров
 * @author PHPShop Software
 * @version 1.6
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShop
 * @package PHPShopShopCore
 */
class PHPShopShop extends PHPShopShopCore {

    /**
     * Режим отладки
     * @var bool
     */
    var $debug = false;

    /**
     * Режим кэширования записей БД, рекомендуется для этого файла true
     * @var bool
     */
    var $cache = true;

    /**
     * Имена полей БД, удаляемых из кэша для оптимизации памяти, рекомендуется  array('content','yml_bid_array')
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');

    /**
     * Максимальный лимит вывода товаров/каталогов на странице для оптимизации памяти, рекомендуется не более 100
     * @var int
     */
    var $max_item = 200;

    /**
     * Имя функции шаблона вывода фильтров характеристик товара
     * @var string 
     */
    var $sort_template = null;
    var $ed_izm = null;

    /**
     * Конструктор
     */
    function PHPShopShop() {

        // Размещение
        $this->path = '/' . $GLOBALS['SysValue']['nav']['path'];

        // Список экшенов
        $this->action = array("nav" => array("CID", "UID"));
        parent::PHPShopShopCore();

        $this->PHPShopOrm->cache_format = $this->cache_format;

        $this->page = $this->PHPShopNav->getPage();
        if (strlen($this->page) == 0)
            $this->page = 1;
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

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    }

    /**
     * Выделение текущего каталога в меню
     */
    function setActiveMenu() {

        $this->set('thisCat', $this->PHPShopCategory->getParam('parent_to'));

        // Верхний уловень каталога
        $cat = $this->get('thisCat');
        if (empty($cat))
            $this->set('thisCat', intval($this->PHPShopNav->getId()));

        // Если 3х вложенность каталога
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache_format = array('content', 'description');
        $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($this->get('thisCat'))), false, array('limit' => 1));
        $ParentTest = $data['parent_to'];

        if (!empty($ParentTest)) {
            $this->set('thisCat', $ParentTest);
            $this->set('thisPodCat', $this->PHPShopCategory->getParam('parent_to'));
        }

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $data);
    }

    /**
     * Прикрепленные файлы товара
     * @param array $files
     */
    function file($row) {

        $files = unserialize($row['files']);
        if ($this->PHPShopSystem->getSerilizeParam('admoption.digital_product_enabled') != 1) {
            if (is_array($files)) {
                $this->set('productFiles', '');
                foreach ($files as $cfile) {
                    $this->set('productFiles', PHPShopText::img('images/shop/action_save.gif', 3, 'absmiddle'), true);
                    $this->set('productFiles', PHPShopText::a($cfile, basename($cfile), basename($cfile), false, false, '_blank'), true);
                    $this->set('productFiles', PHPShopText::br(), true);
                }
            } else {
                $this->set('productFiles', __("Нет файлов"));
            }
        } else
            $this->set('productFiles', __("Файлы будут доступны только после оплаты"));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Облако тегов
     * @param array $row массив данных
     */
    function cloud($row) {
        global $PHPShopCloudElement;

        $disp = $PHPShopCloudElement->index($row);
        $this->set('cloud', $disp);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Прикрепленные статьи товара
     * @param string $pages
     */
    function article($row) {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $row, 'START'))
            return true;

        $dis = null;
        if (strstr($row['page'], ','))
            $pages = explode(",", $row['page']);

        if (!empty($pages) and is_array($pages)) {
            foreach ($pages as $val) {

                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name11']);
                $row = $PHPShopOrm->select(array('name'), array('link' => "='" . $val . "'"));

                $this->set('pageLink', $val);
                $this->set('pageName', $row['name']);

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Подключаем шаблон
                $dis.=ParseTemplateReturn($this->getValue('templates.product_pagetema_forma'));
            }

            if (!empty($dis)) {
                $this->set('temaContent', $dis);
                $this->set('temaTitle', __('Статьи по теме'));

                // Вставляем результат в шаблон
                $this->set('pagetemaDisp', ParseTemplateReturn($this->getValue('templates.product_pagetema_list')));
            }
        }

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    }

    /**
     * Вывод рейтинга товаров
     * Функция вынесена в отдельный файл rating.php
     * @return mixed
     */
    function rating($row) {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Вывод галлереи изображений
     * Функция вынесена в отдельный файл image_gallery.php
     * @return mixed
     */
    function image_gallery($row) {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Вывод опций товаров
     * Функция вынесена в отдельный файл option_select.php
     * @return mixed
     */
    function option_select($row) {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации UID
     */
    function UID() {

        // Перехват модуля в начале функции
        if ($this->setHook(__CLASS__, __FUNCTION__, null, 'START'))
            return true;

        // Безопасность
        if (!PHPShopSecurity::true_num($this->PHPShopNav->getId()))
            return $this->setError404();

        // Выборка данных
        $row = parent::getFullInfoItem(array('*'), array('id' => "=" . $this->PHPShopNav->getId(), 'enabled' => "='1'", 'parent_enabled' => "='0'"), __CLASS__, __FUNCTION__);

        // 404 ошибка
        if (empty($row['id']))
            return $this->setError404();

        // Категория
        $this->category = $row['category'];
        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        // 404 ошибка мультибазы
        if ($this->errorMultibase($this->category))
            return $this->setError404();

        // Единица измерения
        if (empty($row['ed_izm']))
            $ed_izm = $this->ed_izm;
        else
            $ed_izm = $row['ed_izm'];

        // Прикрепленные файлы
        $this->file($row);

        // Облако тегов
        $this->cloud($row);

        // Фотогалерея
        $this->image_gallery($row);

        // Таблица характеристик
        $this->sort_table($row);

        // Опции товара
        $this->option_select($row);

        // Рейтинг
        $this->rating($row);

        // Проверка режима Multibase
        $this->checkMultibase($row['pic_small']);


		//nah
		
		
		if($row['sklad']!=1)
		{	
			$fp=round($row['price']/10);
			
			$firstcreditpunch='
			<span class="addtochart buyincredit2" id="credit_'.$row['id'].'">
									<input class="creditinputcart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="В кредит">
									<span class="firstcreditpunch"> '.$fp.' р. первый взнос</span>
								</span>';
								
			$this->set('firstcreditpunch',$firstcreditpunch);					
		}		
		else
			$this->set('firstcreditpunch','');

        $this->set('productName', $row['name']);
        $this->set('productArt', $row['uid']);
        $this->set('productDes', $row['content']);
        $this->set('productPriceMoney', $this->dengi);
        $this->set('productBack', $this->lang('product_back'));
        $this->set('productSale', $this->lang('product_sale'));
        $this->set('productValutaName', $this->currency());
        $this->set('productUid', $row['id']);
        $this->set('productId', $row['id']);

        // Опции склада
        $this->checkStore($row);

        // Статьи по теме
        $this->article($row);

        // Подтипы
        $this->parent($row);

        // Перехват модуля в середине функции
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

        // Подключаем шаблон
        $this->add(ParseTemplateReturn($this->getValue('templates.main_product_forma_full')), true);

        // Однотипные товары
        $this->odnotip($row);

        // Данные родительской категории
        $cat = $this->PHPShopCategory->getValue('parent_to');
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array(
                'name' => 'Каталог',
                'id' => 0
            );
        }

        $this->set('catalogCat', $parent_category_row['name']);
        $this->set('catalogId', $parent_category_row['id']);
        $this->set('catalogUId', $cat);
        $this->set('pcatalogId', $this->category);
        $this->set('productName', $row['name']);
        $this->set('catalogCategory', $this->PHPShopCategory->getName());
        $this->set('pcatalogId', $this->category);

        // Выделение текущего каталога в меню
        $this->setActiveMenu();

        // Навигация хлебных крошек для новых шаблонов
        $this->navigation($this->category, $row['name']);

        // Мета заголовки
        $this->set_meta(array($row, $this->PHPShopCategory->getArray(), $parent_category_row));
        $this->lastmodified = $row['datas'];

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_page_full'));
    }

    /**
     * Мета-теги
     * @param array $row данные
     */
    function set_meta($row) {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Однотипные товары
     * @param array $row массив данных
     */
    function odnotip($row) {
        global $PHPShopProductIconElements;

        $this->odnotip_setka_num = 1;
        $this->line = false;
        $this->template_odnotip = 'main_spec_forma_icon';

        // Перехват модуля в начале функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
            return true;

        $disp = null;
        $odnotipList = null;
        if (!empty($row['odnotip'])) {
            if (strpos($row['odnotip'], ','))
                $odnotip = explode(",", $row['odnotip']);
            elseif (is_numeric(trim($row['odnotip'])))
                $odnotip[] = trim($row['odnotip']);
        }

        // Список для выборки
        if (is_array($odnotip))
            foreach ($odnotip as $value) {
                if (!empty($value))
                    $odnotipList.=' id=' . trim($value) . ' OR';
            }

        $odnotipList = substr($odnotipList, 0, strlen($odnotipList) - 2);

        // Режим проверки остатков на складе
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 2)
            $chek_items = ' and items>0';
        else
            $chek_items = null;

        if (!empty($odnotipList)) {

            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $result = $PHPShopOrm->query("select * from " . $this->objBase . " where (" . $odnotipList . ") " . $chek_items . " and  enabled='1' and parent_enabled='0' and sklad!='1' order by num");
            while ($row = mysql_fetch_assoc($result))
                $data[] = $row;

            // Сетка товаров
            if (!empty($data) and is_array($data))
                $disp = $PHPShopProductIconElements->seamply_forma($data, $this->odnotip_setka_num, $this->template_odnotip, $this->line);
        }


        if (!empty($disp)) {
            // Вставка в центральную часть
            if (PHPShopParser::check($this->getValue('templates.main_product_odnotip_list'), 'productOdnotipList')) {
                $this->set('productOdnotipList', $disp);
                $this->set('productOdnotip', __('Рекомендуемые товары'));
            } else {
                // Вставка в правый столбец
                $this->set('specMainTitle', __('Рекомендуемые товары'));
                $this->set('specMainIcon', $disp);
            }

            // Перехват модуля в середине функции
            $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

            $odnotipDisp = ParseTemplateReturn($this->getValue('templates.main_product_odnotip_list'));
            $this->set('odnotipDisp', $odnotipDisp);
        }
        // Выводим последние новинки
        else {
            $this->set('specMainIcon', $PHPShopProductIconElements->specMainIcon(true, $this->category));
        }

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    }

    /**
     * Вывод подтипов товаров
     * @param array $row массив значений
     */
    function parent($row) {

        // Перехват модуля в начале функции
        if ($this->setHook(__CLASS__, __FUNCTION__, $row, 'START'))
            return true;

        $select_value = array();
        $row['parent'] = PHPShopSecurity::CleanOut($row['parent']);

        if (!empty($row['parent'])) {
            $parent = explode(",", $row['parent']);

            // Убираем добавление в корзину главного товара
            $this->set('ComStartCart', '<!--');
            $this->set('ComEndCart', '-->');

            // Собираем массив товаров
            if (is_array($parent))
                foreach ($parent as $value) {
                    if (PHPShopProductFunction::true_parent($value))
                        $Product[$value] = $this->select(array('*'), array('uid' => '="' . $value . '"', 'enabled' => "='1'", 'sklad' => "!='1'"), false, false, __FUNCTION__);
                    else
                        $Product[intval($value)] = $this->select(array('*'), array('id' => '=' . intval($value), 'enabled' => "='1'"), false, false, __FUNCTION__);
                }

            // Цена главного товара
            if (!empty($row['price'])) {
                $select_value[] = array($row['name'] . " -  (" . $this->price($row) . "
                    " . $this->get('productValutaName') . ')', $row['id'], false);
            }


            // Выпадающий список товаров
            if (is_array($Product))
                foreach ($Product as $p) {
                    if (!empty($p)) {

                        // Если товар на складе
                        if (empty($p['priceSklad'])) {
                            $price = $this->price($p);
                            $select_value[] = array($p['name'] . ' -  (' . $price . ' ' . $this->get('productValutaName') . ')', $p['id'], false);
                        }
                    }
                }

            $this->set('parentList', PHPShopText::select('parentId', $select_value, false));
            $this->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
            $this->set('productPrice', '');
            $this->set('productPriceRub', '');
            $this->set('productValutaName', '');

            // Перехват модуля в конце функции
            $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
        }
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации CID
     */
    function CID() {

        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // ID категории
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopCategory(intval($this->category));
        $this->category_name = $this->PHPShopCategory->getName();

        // Запрос на подкаталоги
        $parent_category_row = $this->select(array('*'), array('parent_to' => '=' . $this->category), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories')));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'MIDDLE');

        // Если товары
        if (empty($parent_category_row['id'])) {

            $this->CID_Product();
        }
        // Если каталоги
        else {

            $this->CID_Category();
        }
    }

    /**
     * Генерация SQL запроса со сложными фильтрами и условиями
     * Функция вынесена в отдельный файл query_filter.php
     * @return mixed
     */
    function query_filter() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if (!empty($hook))
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Вывод таблицы характеристик товара
     * Функция вынесена в отдельный файл sort_table.php
     * @param array $row массив значений
     * @return mixed
     */
    function sort_table($row) {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * Вывод списка товаров
     * @param integer $category ИД категории
     */
    function CID_Product($category = null) {

        if (!empty($category))
            $this->category = intval($category);

        // Перехват модуля в начале
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;
        
        // 404 если каталога не существует
        if(empty($this->category_name))
            return $this->setError404();

        // Путь для навигации
        $this->objPath = './CID_' . $this->category . '_';

        // Валюта
        $this->set('productValutaName', $this->currency());

        // Количество ячеек для вывода товара
        $cell = $this->PHPShopCategory->getParam('num_row');

        // Фильтр сортировки
        $order = $this->query_filter();

        // Кол-во товаров на странице
        $num_cow = $this->PHPShopCategory->getParam('num_cow');
        if (!empty($num_cow))
            $this->num_row = $num_cow;

        // Простой запрос
        if (is_array($order)) {

            $this->dataArray = parent::getListInfoItem(false, false, false, __CLASS__, __FUNCTION__, $order['sql']);

            // Пагинатор
            $this->setPaginator(count($this->dataArray), $order['sql']);
        } else {
            // Сложный запрос
            $this->PHPShopOrm->sql = 'select * from ' . $this->SysValue['base']['products'] . ' where ' . $order;
            $this->PHPShopOrm->debug = $this->debug;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();

            // Пагинатор
            $this->setPaginator(count($this->dataArray), $order);
        }

        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // Родительская категория
        $cat = $this->PHPShopCategory->getParam('parent_to');

        // Данные родительской категории
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array();
        }

        $this->set('catalogCat', $parent_category_row['name']);
        $this->set('catalogCategory', $this->PHPShopCategory->getName());
        $this->set('productId', $this->category);
        $this->set('catalogId', $cat);
        $this->set('pcatalogId', $this->category);

        // Фильтр товаров
        PHPShopObj::loadClass('sort');
        $PHPShopSort = new PHPShopSort($this->category, $this->PHPShopCategory->getParam('sort'), true, $this->sort_template);
        $this->set('vendorDisp', $PHPShopSort->display());

        // Выделение текущего каталог в меню
        $this->setActiveMenu();

        // Мета заголовки
        $this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));

        // Дублирующая навигация
        $this->other_cat_navigation($cat);

        // Навигация хлебных крошек для новых шаблонов
        $this->navigation($cat, $this->PHPShopCategory->getName());

        // Описание каталога
        $this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
	// Описание каталога
        $this->set('catalogContent_h', $this->PHPShopCategory->getContent_h());

        // Облако тегов
        $this->cloud($this->dataArray);

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_page_list'));
    }

    /**
     * Альтернативная навигация категорий с списке товаров
     * @param Int $parent ИД родителя категории
     */
    function other_cat_navigation($parent) {

        // Перехват модуля в начале функции
        $this->setHook(__CLASS__, __FUNCTION__, $parent, 'START');

        // Имя родителя
        $dis = PHPShopText::h1($this->get('catalogCat'));

        $dataArray = array();
        $dis = null;

        // Использование глобального кэша
        foreach ($GLOBALS['Cache'][$GLOBALS['SysValue']['base']['categories']] as $val) {
            if ($val['parent_to'] == $parent)
                $dataArray[] = $val;
        }

        if (count($dataArray) > 1) {
            foreach ($dataArray as $row) {

                if ($row['id'] == $this->category)
                    $class = 'activ_catalog';
                else
                    $class = null;

                $dis.=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'], false, false, false, false, $class);
                $dis.=' | ';
            }
        }
        // Выборка данных из БД при отсутствии данных в кэше
        else {
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.categories'));
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->cache = false;
            $dataArray = $PHPShopOrm->select(array('*'), array('parent_to' => '=' . $parent), array('order' => 'num'), array('limit' => 100));
            if (is_array($dataArray))
                foreach ($dataArray as $row) {

                    if ($row['id'] == $this->category)
                        $class = 'activ_catalog';
                    else
                        $class = null;

                    $dis.=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'], false, false, false, false, $class);
                    $dis.=' | ';
                }
        }

        // Перехват модуля в конце функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $parent, 'END');
        if ($hook)
            return true;

        $this->set('DispCatNav', $dis);
    }

    /**
     * Вывод списка категорий
     */
    function CID_Category() {

        // Перехват модуля в начале функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook)
            return true;

        // ID категории
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopCategory($this->category);

        // Скрытый каталог
        if ($this->PHPShopCategory->getParam('skin_enabled') == 1)
            return $this->setError404();

        // Название категории
        $this->category_name = $this->PHPShopCategory->getName();

        // Условия выборки
        $where = array('parent_to' => '=' . $this->category, 'skin_enabled' => "!='1'");

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }


        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.categories'));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;
        $dis = null;
        $dataArray = $PHPShopOrm->select(array('*'), $where, array('order' => 'num'), array('limit' => $this->max_item));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {
                $dis.=PHPShopText::li($row['name'], $this->path . '/CID_' . $row['id'] . '.html');
            }

        $this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
        $disp = PHPShopText::ul($dis);
        $this->set('catalogName', $this->category_name);
        $this->set('catalogList', $disp);
        $this->set('thisCat', $this->PHPShopNav->getId());

        // Данные родительской категории для meta
        $cat = $this->PHPShopCategory->getValue('parent_to');
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array(
                'name' => 'Каталог',
                'id' => 0
            );
        }

        // Выделение текущего каталог в меню
        $this->setActiveMenu();

        // Мета заголовки
        $this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));

        // Навигация хлебных крошек для новых шаблонов
        $this->navigation($this->PHPShopCategory->getParam('parent_to'), $this->category_name);

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.catalog_info_forma'));
    }

    /**
     * Экшен 404 ошибки по ссылке /shop/
     */
    function index() {
        $this->setError404();
    }

}

?>