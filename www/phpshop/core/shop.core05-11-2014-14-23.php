<?php

/**
 * Обработчик товаров
 * @author PHPShop Software
 * @version 1.6
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShop
 * @package PHPShopShopCore
 */

//иконки для списка товаров
//include $_SERVER['DOCUMENT_ROOT']."/catalog_product_icons.php";

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
    var $max_item = 500;

    /**
     * Имя функции шаблона вывода фильтров характеристик товара
     * @var string 
     */
    var $sort_template = null;
    var $ed_izm = null;

	var $first_time_catalog=null;
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
			//выводим кредит
			/*
			$firstcreditpunch='
			<span class="addtochart buyincredit2" id="credit_'.$row['id'].'">
									<input class="creditinputcart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="В кредит">
									<span class="firstcreditpunch"> '.$fp.' р. первый взнос</span>
								</span>';
			*/
			//$this->set('firstcreditpunch',$firstcreditpunch);
			//выводим быстрый заказ
			/*$FastOrder='<span class="addtochart fast_order1" id="fast_order_'.$row['id'].'">
						<input class="fast_ordercart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="'.$this->lang('fast_order').'">
						</span>';*/
			$FastOrder='<span class="price_comlain" onclick="fast_order_window2(window.location,document.getElementsByClassName(\'netref\'));">'.$this->lang('fast_order').'</span>';
			$this->set('FastOrder', $FastOrder);
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
/*
        $mod_price=strval($row['price']);
        
        switch (strlen($mod_price)) {
        	case 4:
        		$mod_price=substr($mod_price,0,1).' '.substr($mod_price,1,strlen($mod_price)-1);
        		break;
        	case 5:
        		$mod_price=substr($mod_price,0,2).' '.substr($mod_price,2,strlen($mod_price)-2);
        		break;
        	case 6:
        		$mod_price=substr($mod_price,0,3).' '.substr($mod_price,3,strlen($mod_price)-3);
        		break;
        }
        $row['price']=$mod_price;
*/                
        // Опции склада
        $this->checkStore($row);

		//Для каталога stihl модифицируем кнопку уточнить
		/*
        $stihl_catalog_search=false;
		$sql="select id from ".$GLOBALS['SysValue']['base']['categories']." where name like '%stihl%'";
		$PHPShopOrm = new PHPShopOrm();
		$PHPShopOrm->cache = true;
		$PHPShopOrm->debug = $this->debug;
		$PHPShopOrm->sql=$sql;
		$res=$PHPShopOrm->select();
        //$catalog_id_result = $this->PHPShopOrm->query("select id from ".$GLOBALS['SysValue']['base']['categories']." where name like '%stihl%'");
       // while ($catalog_id_rows=mysql_fetch_row($catalog_id_result)) {
	   //echo $row['category'];
	   foreach ($res as $catalog_id_rows) {		
        	if ($row['category']==$catalog_id_rows[id]) {
        		$stihl_catalog_search=true;
				//echo $catalog_id_rows[id];
        	}
        }
        //print_r($catalog_id_rows);
        //echo ' '.$catalog_id_rows[0][2]. ' '.$row['category'];
        if ($stihl_catalog_search==true) {
        	$this->set('productNotice', $this->lang('stihl_string'));
        } else {
        	$this->set('productNotice', $this->lang('product_notice'));
        }
        */
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

        //if ($this->get('productName')=='Мотоблок МКМ-3-DK7 Lander (Пахарь)') {
        //$this->set('test',$mod_price);
        //}
        //$this->add('test');
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

        $UIDProductName=$row['name'];
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
                $this->set('UIDProductName', $UIDProductName);
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
		/*	
		if (substr($this->PHPShopNav->getUrl(),-7,2)=='_1' || substr($this->PHPShopNav->getUrl(),-7,2)=='_0') {
			$corrected_url=str_replace( '_1' , '' , $this->PHPShopNav->getUrl());
			$corrected_url=str_replace( '_0' , '' , $corrected_url);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:http://test.prodacha.ru".$corrected_url);
		}
		*/
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

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'END');
				
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
			//print_r( $this->query_filter() );
        // Простой запрос
        if (is_array($order)) {

            $this->dataArray = parent::getListInfoItem(false, false, false, __CLASS__, __FUNCTION__, $order['sql']);
			//echo $order['sql'];
            // Пагинатор
            $this->setPaginator(count($this->dataArray), $order['sql']);
        } else {
            // Сложный запрос
            $this->PHPShopOrm->sql = 'select * from ' . $this->SysValue['base']['products'] . ' where ' . $order;
            $this->PHPShopOrm->debug = $this->debug;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
			//echo $order;
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
		$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)stihl(.*)$/i','$1Штиль$3',$this->PHPShopCategory->getName());
		$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)viking(.*)$/i','$1Викинг$3',$this->PHPShopCategory->getName());
		$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)karcher(.*)$/i','$1Керхер$3',$this->PHPShopCategory->getName());		
		//print_r($this->PHPShopCategory->objRow['name']);	
        $this->set('catalogCategory', $this->PHPShopCategory->getName());
        $this->set('productId', $this->category);
        $this->set('catalogId', $cat);
        $this->set('pcatalogId', $this->category);

        // Фильтр товаров
        //PHPShopObj::loadClass('sort');
        //$PHPShopSort = new PHPShopSort($this->category, $this->PHPShopCategory->getParam('sort'), true, $this->sort_template);
        //$this->set('vendorDisp', $PHPShopSort->display());
		//echo $PHPShopSort->display();

        // Выделение текущего каталога в меню
        $this->setActiveMenu();

        // Мета заголовки
        $this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));

        // Дублирующая навигация
        $this->other_cat_navigation($cat);

        // Навигация хлебных крошек для новых шаблонов
        $this->navigation($cat, $this->PHPShopCategory->getName());

		//echo $this->num_of_pages;
		//echo $this->PHPShopNav->getPage();
		//print_r($this->PHPShopNav);

		if ($this->PHPShopNav->getPage()==1) {
			// Описание каталога
			$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));

			// Описание каталога
			$this->set('catalogContent_h', $this->PHPShopCategory->getContent_h());	
		}

		//echo $this->dataArray[2][category];
		//$this->stihl_catalog_settings($parent_category_row);
		//echo '$this->get(ComStartCart) '.$this->get('ComStartCart');
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
		
			$cnt1=0;
			$cnt_cur_row_by_type=0;
			$cnt2=0;
			$cnt_cur_row_by_maker=0;
			$cnt_by_type=0;
			$cnt_by_maker=0;
			$catalog_items_by_type_table_td=array();
			$catalog_items_by_maker_table_td=array();
			
			//считаем сколько у нас элементов без скобок
			foreach ($dataArray as $row) {
				if ( 
					($this->PHPShopNav->getId()==288 || 
					$this->PHPShopNav->getId()==290 || 
					$this->PHPShopNav->getId()==292 || 
					$this->PHPShopNav->getId()==37 || 
					$this->PHPShopNav->getId()==44 || 
					$this->PHPShopNav->getId()==5 || 
					$this->PHPShopNav->getId()==36 || 
					$this->PHPShopNav->getId()==18 || 
					$this->PHPShopNav->getId()==85 || 
					$this->PHPShopNav->getId()==215 ||
					$this->PHPShopNav->getId()==293 ||
					$this->PHPShopNav->getId()==142 ||
					$this->PHPShopNav->getId()==297 ||
					$this->PHPShopNav->getId()==298	||
					$this->PHPShopNav->getId()==88 ||
					$this->PHPShopNav->getId()==224	||
					$this->PHPShopNav->getId()==172	||
					$this->PHPShopNav->getId()==16 ||
					$this->PHPShopNav->getId()==60 ||
					$this->PHPShopNav->getId()==9 ||
					$this->PHPShopNav->getId()==77 ||
					$this->PHPShopNav->getId()==211 ||
					$this->PHPShopNav->getId()==134 ||
					$this->PHPShopNav->getId()==228 ||
					$this->PHPShopNav->getId()==295 ||
					$this->PHPShopNav->getId()==299 ||
					$this->PHPShopNav->getId()==254 ||
					$this->PHPShopNav->getId()==272 ||
					$this->PHPShopNav->getId()==300			
					)) {
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0) {
						$cnt_by_type++;
					}
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])) {
						$cnt_by_maker++;
					}					
				}
			
			}

			foreach ($dataArray as $row) {
				//print_r($row['name']);
                $dis.=PHPShopText::li($row['name'], $this->path . '/CID_' . $row['id'] . '.html');
				//($cnt1==0 || $cnt1==1 || $cnt1==2) &&
				if (
					($this->PHPShopNav->getId()==288 || 
					$this->PHPShopNav->getId()==290 || 
					$this->PHPShopNav->getId()==292 || 
					$this->PHPShopNav->getId()==37 || 
					$this->PHPShopNav->getId()==44 || 
					$this->PHPShopNav->getId()==5 || 
					$this->PHPShopNav->getId()==36 || 
					$this->PHPShopNav->getId()==18 || 
					$this->PHPShopNav->getId()==85 || 
					$this->PHPShopNav->getId()==215 ||
					$this->PHPShopNav->getId()==293 ||
					$this->PHPShopNav->getId()==142 ||
					$this->PHPShopNav->getId()==297 ||
					$this->PHPShopNav->getId()==298	||
					$this->PHPShopNav->getId()==88 ||
					$this->PHPShopNav->getId()==224	||
					$this->PHPShopNav->getId()==172	||
					$this->PHPShopNav->getId()==16 ||
					$this->PHPShopNav->getId()==60 ||
					$this->PHPShopNav->getId()==9 ||
					$this->PHPShopNav->getId()==77 ||
					$this->PHPShopNav->getId()==211 ||
					$this->PHPShopNav->getId()==134 ||
					$this->PHPShopNav->getId()==228 ||
					$this->PHPShopNav->getId()==295 ||
					$this->PHPShopNav->getId()==299 ||
					$this->PHPShopNav->getId()==254 ||
					$this->PHPShopNav->getId()==272 ||
					$this->PHPShopNav->getId()==300			
					)) {
					//для некоторых категорий переделываем все на table
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0) {
						$catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
						array_push($catalog_items_by_type_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
						$cnt1++;
						$cnt_cur_row_by_type++;
					}
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])) {
						$catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
						array_push($catalog_items_by_maker_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
						$cnt2++;
						$cnt_cur_row_by_maker++;
					}					
				}

					if ($cnt1%2==0 || $cnt_cur_row_by_type==$cnt_by_type) {
						$catalog_items_table1.=PHPShopText::tr2($catalog_items_by_type_table_td);
						$catalog_items_by_type_table_td=array();
						$cnt1=0;						
					}

					if ($cnt2%2==0 || $cnt_cur_row_by_maker==$cnt_by_maker) {
						$catalog_items_table2.=PHPShopText::tr2($catalog_items_by_maker_table_td);
						$catalog_items_by_maker_table_td=array();
						$cnt2=0;						
					}

						
			}
		if 	($this->PHPShopNav->getId()==288 || 
		$this->PHPShopNav->getId()==290 || 
		$this->PHPShopNav->getId()==292 || 
		$this->PHPShopNav->getId()==37 || 
		$this->PHPShopNav->getId()==44 || 
		$this->PHPShopNav->getId()==5 || 
		$this->PHPShopNav->getId()==36 || 
		$this->PHPShopNav->getId()==18 || 
		$this->PHPShopNav->getId()==85 ||
		$this->PHPShopNav->getId()==215 ||
		$this->PHPShopNav->getId()==293 ||
		$this->PHPShopNav->getId()==142 ||
		$this->PHPShopNav->getId()==297 ||
		$this->PHPShopNav->getId()==298	||
		$this->PHPShopNav->getId()==88 ||
		$this->PHPShopNav->getId()==224	||
		$this->PHPShopNav->getId()==172	||
		$this->PHPShopNav->getId()==16 ||
		$this->PHPShopNav->getId()==60 ||
		$this->PHPShopNav->getId()==9 ||
		$this->PHPShopNav->getId()==77 ||
		$this->PHPShopNav->getId()==211 ||
		$this->PHPShopNav->getId()==134 ||
		$this->PHPShopNav->getId()==228 ||
		$this->PHPShopNav->getId()==295 ||
		$this->PHPShopNav->getId()==299 ||
		$this->PHPShopNav->getId()==254 ||
		$this->PHPShopNav->getId()==272 ||
		$this->PHPShopNav->getId()==300	
		) {
			$disp1=PHPShopText::table($catalog_items_table1,3,1,'center','98%',false,0,'catalog_items_table1');
			$disp2=PHPShopText::table($catalog_items_table2,3,1,'center','98%',false,0,'catalog_items_table2');			
		} else {	
			$disp = PHPShopText::ul($dis);
		}
		
        //$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
        //$disp = PHPShopText::ul($dis);
		$this->category_name=preg_replace('/^(.*)stihl(.*)$/i','$1Штиль$3',$this->category_name);
		$this->category_name=preg_replace('/^(.*)viking(.*)$/i','$1Викинг$3',$this->category_name);
		$this->category_name=preg_replace('/^(.*)karcher(.*)$/i','$1Керхер$3',$this->category_name);		
        $this->set('catalogName', $this->category_name);

		if ($cnt_by_type==0) {
			$this->set('display_status1','none');
		} else {
			$this->set('display_status1','block');		
		}		
        $this->set('catalogList', $disp1);
		
		if ($cnt_by_maker==0) {
			$this->set('display_status2','none');
		} else {
			$this->set('display_status2','block');		
		}
        $this->set('catalogList1', $disp2);
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

        // Выделение текущего каталога в меню
        $this->setActiveMenu();

        // Мета заголовки
        $this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));

        // Навигация хлебных крошек для новых шаблонов
        $this->navigation($this->PHPShopCategory->getParam('parent_to'), $this->category_name);

        // Перехват модуля в конце функции
        $hook =$this->setHook(__CLASS__, __FUNCTION__, false, 'END');
        if ($hook)
            return true;
		$this->catalog_improvements();
		if 	($this->PHPShopNav->getId()==288 || 
		$this->PHPShopNav->getId()==290 || 
		$this->PHPShopNav->getId()==292 || 
		$this->PHPShopNav->getId()==37 || 
		$this->PHPShopNav->getId()==44 || 
		$this->PHPShopNav->getId()==5 || 
		$this->PHPShopNav->getId()==36 || 
		$this->PHPShopNav->getId()==18 || 
		$this->PHPShopNav->getId()==85 ||
		$this->PHPShopNav->getId()==215 ||
		$this->PHPShopNav->getId()==293 ||
		$this->PHPShopNav->getId()==142 ||
		$this->PHPShopNav->getId()==297 ||
		$this->PHPShopNav->getId()==298	||
		$this->PHPShopNav->getId()==88 ||
		$this->PHPShopNav->getId()==224	||
		$this->PHPShopNav->getId()==172	||
		$this->PHPShopNav->getId()==16 ||
		$this->PHPShopNav->getId()==60 ||
		$this->PHPShopNav->getId()==9 ||
		$this->PHPShopNav->getId()==77 ||
		$this->PHPShopNav->getId()==211 ||
		$this->PHPShopNav->getId()==134 ||
		$this->PHPShopNav->getId()==228 ||
		$this->PHPShopNav->getId()==295 ||
		$this->PHPShopNav->getId()==299 ||
		$this->PHPShopNav->getId()==254 ||
		$this->PHPShopNav->getId()==272 ||
		$this->PHPShopNav->getId()==300		
		) {
			$this->set('productId', $this->PHPShopNav->getId());
			$this->set('productPageThis', $this->PHPShopNav->getPage());
		}
		if ($this->PHPShopNav->getPage()==1) {
			// Описание каталога
			$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));

			// Описание каталога
			$this->set('catalogContent_h', $this->PHPShopCategory->getContent_h());	
		}

		// Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.catalog_info_forma'));
    }

    /**
     * Экшен 404 ошибки по ссылке /shop/
     */
    function index() {
        return $this->setError404();
    }  

	function catalog_improvements() {
		//реализация нового вида каталога
		
		//получаем текущий каталог
		$cid_id=$this->PHPShopNav->getId();//$GLOBALS['SysValue']['nav']['id'];
		//print_r($this->PHPShopNav);
		//echo $cid_id;
		if 	($cid_id==288 || 
		$cid_id==290 || 
		$cid_id==292 || 
		$cid_id==37 || 
		$cid_id==44 || 
		$cid_id==5 || 
		$cid_id==36 || 
		$cid_id==18 || 
		$cid_id==85 ||
		$cid_id==215 ||
		$cid_id==293 ||
		$cid_id==142 ||
		$cid_id==297 ||
		$cid_id==298 ||
		$cid_id==88 ||
		$cid_id==224 ||
		$cid_id==172 ||
		$cid_id==16 ||
		$cid_id==60 ||
		$cid_id==9 ||
		$cid_id==77 ||
		$cid_id==211 ||
		$cid_id==134 ||
		$cid_id==228 ||
		$cid_id==295 ||
		$cid_id==299 ||
		$cid_id==254 ||
		$cid_id==272 ||
		$cid_id==300	
		) {
			// Путь для навигации
			$this->objPath = './CID_' . $this->category . '_';	
			//echo $this->objPath;
			//print_r($this->PHPShopNav->getPage());			
			// Количество ячеек для вывода товара
			$cell = $this->PHPShopCategory->getParam('num_row');
			//echo $cell;
			// Кол-во товаров на странице
			$num_cow = $this->PHPShopCategory->getParam('num_cow');
			if (!empty($num_cow))
				$this->num_row = $num_cow;

			//echo $this->where;
		$orderby=' price desc ';
        if (!empty($_GET['s']) and is_numeric($_GET['s']) and !empty($_GET['f']) and is_numeric($_GET['f'])) {
			if ($_GET['f']==1 and $_GET['s']==2) {
				$orderby=' price asc ';
			}
			if ($_GET['f']==2 and $_GET['s']==2) {
				$orderby=' price desc ';
			}	
			if ($_GET['f']==1 and $_GET['s']==1) {
				$orderby=' name asc ';
			}
			if ($_GET['f']==2 and $_GET['s']==1) {
				$orderby=' name desc ';
			}
			if ($_GET['f']==2 and $_GET['s']==3) {
				$orderby=' num asc ';
			}
			if ($_GET['f']==1 and $_GET['s']==3) {
				$orderby=' num desc ';
			}
			
        }
			
			if ($this->PHPShopNav->getPage()=='') {
				//select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat from u301639_test.phpshop_products
				$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat from ".$GLOBALS['SysValue']['base']['products']." where category in"
				." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
				." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
				." union all"
				." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby." LIMIT 1,".$this->num_row;
				$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');
			
			} else if ($this->PHPShopNav->isPageAll()) {
				$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5 from ".$GLOBALS['SysValue']['base']['products']." where category in"
				." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
				." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
				." union all"
				." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby;
				$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');

			} else {
				$num_row=$this->PHPShopNav->getPage()*$this->num_row;
				//echo $num_row.' '.$this->num_row;
				$sql_2=" LIMIT ".($num_row-$this->num_row).",".$this->num_row;
				$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5 from ".$GLOBALS['SysValue']['base']['products']." where category in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
					." union all"
					." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby.$sql_2;
				$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');

			}
			//echo $sql;
			//$sql="select distinct * from u301639_test.phpshop_products";
			//$sql2="(category in (38,39,70,65) or dop_cat LIKE '%#288#%') and enabled='1' and parent_enabled='0' order by COALESCE(sklad,0) = 1 asc, ".$orderby;
			
			//$res=$this->PHPShopOrm->query($sql);
			$PHPShopOrm = new PHPShopOrm($this->getValue('base.categories'));
			$PHPShopOrm->debug = $this->debug;
			$PHPShopOrm->cache = $this->cache;
			$PHPShopOrm->cache = true;
			$PHPShopOrm->sql=$sql;
			$res=$PHPShopOrm->select();
			//echo $this->objPath;
			// Фильтр сортировки
			$order = $this->query_filter();
			//print_r($order);
			$cnt=1;
			$last_cnt=0;

			$prod_row=array();

			//$db_rows=mysql_num_rows($res);
			$db_rows=count($res);
			// вычисляем колво страниц пагинации
			//$this->num_page = $db_rows;

			// Кол-во страниц в навигации
			//$num = ceil($this->num_page / $this->num_row);	

			// Пагинатор
			//echo $order['sql'];
			//echo $order;
			if ($this->PHPShopNav->isPageAll()) {
				$this->setPaginator($num_cow, $order);
			} else {
				$this->setPaginator($num_cow, $order['sql']);			
			}

			
			//необходимая переменная для завершения вывода товаров
			$db_rows_odd=false;
			
			//понимаем если четное или нечетное кол-во
			if ( $db_rows % 2 ) {
				$db_rows_odd=false;
			} else if ( $db_rows % 1 ) {
				$db_rows_odd=true;
			}

			//echo '$db_rows_odd='.intval($db_rows_odd);
			//echo '$db_rows='.$db_rows;
			//echo '$num_cow='.$num_cow;
			
			$disp_cat.='<div class="wrapper"><table cellspacing="0" cellpadding="0" border="0">';

			foreach ($res as $prod_row) {			
			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
				//формируем заголовок ссылок 
				//$this->set('catalogContent','=================',true);
				if (empty($prod_row['pic_small'])) {
					$prod_row['pic_small']='images/shop/no_photo.gif';
				}

				if (empty($prod_row['sklad'])) {
					$ComStartNotice='<!--';
					$ComEndNotice='-->';
					$ComStartCart='';
					$ComEndCart='';
				} else {
					$ComStartNotice='';
					$ComEndNotice='';
					$ComStartCart='<!--';
					$ComEndCart='-->';					
				}
				$region_info='m';
				if (isset($_COOKIE['sincity'])) {
					$region_info=$_COOKIE['sincity'];
				}
				
				if ($cid_id==228) {
					$addtochart='<input type="button" onclick="stihl_window(\''.$region_info.'\')"  value="'.$this->lang('stihl_string').'" />';
				} else if ($cid_id==295) {
					$addtochart='<input type="button" onclick="viking_window(\''.$region_info.'\')"  value="'.$this->lang('stihl_string').'" />';				
				} else {
					$addtochart='<input type="button" onclick="javascript:AddToCart('.$prod_row['id'].')"  value="'.$this->lang('product_sale').'" />';
				}
				
				if ($cell==1 || $cell==2 || $cell==3 || $cell==4) { 
					if ($cnt == 1) {
					$disp_cat.='<tr><td class="panel_l panel_3_1"><div class="tovar" style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(../images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';">';
					$disp_cat.='<div class="item">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
								 .'<tr>'
									.'<td height="160" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'
								 .'</tr>'
							.'</table>'
						.'</div>'
						.'<div style="clear:both"></div>'
						.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
								.'<td valign="top" height="45"><a href="/shop/UID_'.$prod_row['id'].'.html"><span class="description">'.$prod_row['name'].'</span></a></td>'
							.'</tr>'
						.'</table>'
						.$ComStartCart.'<span class="addtochart">'
						.$addtochart
						.'</span>'.$ComEndCart
						.$ComStartNotice.'<span class="addtochart">'
						.'<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'">'
                        .'</span>'.$ComEndNotice
						.'<span class="price">'.$prod_row['price'].'<span class="smallfont">'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>' 
						.'<div style="clear:both"></div>'
						.$ComStartNotice.'<div class="prev_price" style="font-size:11px !important;">'.$this->lang('sklad_mesage').'</div>'.$ComEndNotice						
						.'</div>'
						.'</div>'
						.'</div>'						
						.'</td><td class="setka"> <img width="1" src="/phpshop/templates/prodacha/images/spacer.gif"></td>';
					if ($cell==1) {
						$cnt=0;
						$disp_cat.='</tr>';
					}					
					}
				}
				if ($cell==2 || $cell==3 || $cell==4) {
					if  ($cnt == 2) {
					$disp_cat.='<td class="panel_r panel_3_2"><div class="tovar" style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(../images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"><div class="item">'
						.'<span class="new"></span>'
						.'<div class="thumb">'
						.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
								.'<td height="160" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'
							.'</tr>'
							.'</table>'
						.'</div>'
						.'<div style="clear:both"></div>'
						.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
								.'<td valign="top" height="45"><a href="/shop/UID_'.$prod_row['id'].'.html"><span class="description">'.$prod_row['name'].'</span></a></td>'
							.'</tr>'
						.'</table>'
						.$ComStartCart.'<span class="addtochart">'
						.$addtochart
						.'</span>'.$ComEndCart
						.$ComStartNotice.'<span class="addtochart">'
						.'<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'">'
                        .'</span>'.$ComEndNotice						
						.'<span class="price">'.$prod_row['price'].'<span class="smallfont">'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>' 
						.'<div style="clear:both"></div>'
						.$ComStartNotice.'<div class="prev_price" style="font-size:11px !important;">'.$this->lang('sklad_mesage').'</div>'.$ComEndNotice						
						.'</div>'
						.'</div>'
						.'</div>'						
						.'</td><td class="setka"> <img width="1" src="/phpshop/templates/prodacha/images/spacer.gif"></td>';
						if ($cell==2) {
							$cnt=0;
							$disp_cat.='</tr>';					
						}					
					}
				}
				if ($cell==3 || $cell==4) {
					if  ($cnt == 3) {
						$disp_cat.='<td class="panel_l panel_3_2"><div style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(../images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"><div class="item">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
								 .'<tr>'
									.'<td height="160" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'
								 .'</tr>'
							.'</table>'
						.'</div>'
						.'<div style="clear:both"></div>'
						.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
								.'<td valign="top" height="45"><a href="/shop/UID_'.$prod_row['id'].'.html"><span class="description">'.$prod_row['name'].'</span></a></td>'
							.'</tr>'
						.'</table>'
						.$ComStartCart.'<span class="addtochart">'
						.$addtochart
						.'</span>'.$ComEndCart
						.$ComStartNotice.'<span class="addtochart">'
						.'<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'">'
                        .'</span>'.$ComEndNotice					
						.'<span class="price">'.$prod_row['price'].'<span class="smallfont">'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>' 
						.'<div style="clear:both"></div>'
						.$ComStartNotice.'<div class="prev_price" style="font-size:11px !important;">'.$this->lang('sklad_mesage').'</div>'.$ComEndNotice					
						.'</div>'
						.'</div>'
						.'</div>'
						.'</td><td class="setka"> <img width="1" src="/phpshop/templates/prodacha/images/spacer.gif"></td>';
						if ($cell==3) {
							$cnt=0;
							$disp_cat.='</tr>';					
						}
					}		
				}
				if ($cell==4) {
					if  ($cnt == 4) {
						$disp_cat.='<td class="panel_l panel_3_3"><div class="tovar"  style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(../images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"><div class="item">'
						.'<div class="item">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
								 .'<tr>'
									.'<td height="160" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'
								 .'</tr>'
							.'</table>'
						.'</div>'
						.'<div style="clear:both"></div>'
						.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
								.'<td valign="top" height="45"><a href="/shop/UID_'.$prod_row['id'].'.html"><span class="description">'.$prod_row['name'].'</span></a></td>'
							.'</tr>'
						.'</table>'
						.$ComStartCart.'<span class="addtochart">'
						.$addtochart
						.'</span>'.$ComEndCart
						.$ComStartNotice.'<span class="addtochart">'
						.'<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'">'
                        .'</span>'.$ComEndNotice						
						.'<span class="price">'.$prod_row['price'].'<span class="smallfont">'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>' 
						.'<div style="clear:both"></div>'
						.$ComStartNotice.'<div class="prev_price" style="font-size:11px !important;">'.$this->lang('sklad_mesage').'</div>'.$ComEndNotice
						.'</div>'
						.'</div>'
						.'</div>'						
						.'</td><td class="setka"> <img width="1" src="/phpshop/templates/prodacha/images/spacer.gif"></td>'; 
						if ($cell==4) {
							$cnt=0;
							$disp_cat.='</tr>';											
						}
					}			
				}
				//echo 'last_cnt='.$last_cnt.'$db_rows='.$db_rows;			
				if (( ($db_rows_odd==false && ($cell==2 || $cell==3 || $cell==4)) || ($db_rows_odd==true && ($cell==1 || $cell==4)) ) && $last_cnt==($db_rows-1)) {
					//echo 'last_cnt='.$last_cnt;
					$disp_cat.='</tr>';
				}
				$cnt++;
				$last_cnt++;
							
			}
			$disp_cat.='</table></div>';
			$this->set('catalogoutput',$disp_cat);
		
		}
	}
	}

?>