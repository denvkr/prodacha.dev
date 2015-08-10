<?php
include_once('filtr_config.php');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
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
	 * Загружает массив из конфигурационного файла custom_menu_1.txt
	 * @var array
	 */
	
	private $custom_menu_3=array();
		
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

        // Верхний уровень каталога
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
    	//echo 'UID';
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
			//$fp=round($row['price']/10);
			//выводим кредит
			$firstcreditpunch='
			<span class="addtochart buyincredit2" id="credit_'.$row['id'].'" style="left:-50px;">
									<input class="creditinputcart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="В кредит">';
			$this->set('firstcreditpunch',$firstcreditpunch);
			//выводим быстрый заказ
			/*$FastOrder='<span class="addtochart fast_order1" id="fast_order_'.$row['id'].'">
			 <input class="fast_ordercart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="'.$this->lang('fast_order').'">
			</span>';
			*/
			$sdvig_vlevo='left:-50px;';
			$FastOrder='<span class="price_comlain fast_order1" style="position:relative;'.$sdvig_vlevo.'" onclick="fast_order_window2(window.location,document.getElementsByClassName(\'netref\'));">'.$this->lang('fast_order').'</span>';
			$this->set('FastOrder', $FastOrder);
		}		
		else {
			$this->set('firstcreditpunch','');
		}

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
        
        $this->product_icons($row);
      
    	//echo '2';
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
        if ( intval($this->category)==473 || intval($this->category)==474 || intval($this->category)==475 ||
        	 intval($this->category)==476 || intval($this->category)==477 || intval($this->category)==478 ||
        	 intval($this->category)==479 || intval($this->category)==480 || intval($this->category)==481 ||
        	 intval($this->category)==482 || intval($this->category)==483 || intval($this->category)==484 ||
        	 intval($this->category)==485 || intval($this->category)==486 || intval($this->category)==486 ||
        	 intval($this->category)==487 || intval($this->category)==488 || intval($this->category)==489 ||
        	 intval($this->category)==490 || intval($this->category)==491 || intval($this->category)==492 ||
        	 intval($this->category)==493 || intval($this->category)==494 || intval($this->category)==495 ||
        	 intval($this->category)==496 || intval($this->category)==497 || intval($this->category)==498 ||
        	 intval($this->category)==499 || intval($this->category)==500 || intval($this->category)==501 ||
        	 intval($this->category)==502 || intval($this->category)==536 || intval($this->category)==538 || 
			 intval($this->category)==543 || intval($this->category)==545 || intval($this->category)==549) {
        	switch (intval($this->category)) {
        		case 473 : $where=' where id in (38,120,62,314,45,74,19,15,17,20,21,22,30,27,28,23,24,25,26,29,229,126,173)';
        					break;
        		case 474 : $where=' where id in (42,46,316)';
        					break;
        		case 475 : $where=' where id in (41,319,40,318,330)';
        					break;
        		case 476: $where = " where id in (70,71,131,102,112,219,409,414,144,198,92,268,269)";
        					break;
        		case 477: $where = " where id in (84,127,101,138,216,410,255,309,420,226,305,212)";
        					break;
        		case 478: $where = " where id in (83,105,113,415)";
        					break;
        		case 479: $where = " where id in (245,103,116,220,411,424,200)";
        					break;
        		case 480: $where = " where id in (128,100)";
        					break;
        		case 481: $where = " where id in (251,193,203,218,408,310,422,271,225,307,509,517)";
        					break;
        		case 482: $where = " where id in (179,178,416,150,325,326)";
        					break;
        		case 483: $where = " where id in (129,109,217,421,213)";
        					break;
        		case 484: $where = " where id in (171,321)";
        					break;
        		case 485: $where = " where id in (240,239,227,241,238,418,204,235)";
        					break;
        		case 486: $where = " where id in (174,210,221)";
        					break;
        		case 487: $where = " where id in (175,207,223)";
        					break;
        		case 488: $where = " where id in (181,205,180,222)";
        					break;
        		case 489: $where = " where id in (195,246,194,185,412,199)";
        					break;
        		case 490: $where = " where id in (140,111,152)";
        					break;
        		case 491: $where = " where id in (139,104)";
        					break;
        		case 492: $where = " where id in (52,190)";
        					break;
        		case 493: $where = " where id in (303,156,115,413,425,148,243,197,530)";
        					break;
        		case 494: $where = " where id in (423,132,106,114,313)";
        					break;
        		case 495: $where = " where id in (419,151)";
        					break;
        		case 496: $where = " where id in (130,108,306,537)";
        					break;
        		case 497: $where = " where id in (168,170)";
        					break;
        		case 498: $where = " where id in (209)";
        					break;
        		case 499: $where = " where id in (149)";
        					break;
        		case 500: $where = " where id in (304,186)";
        					break;
        		case 501: $where = " where id in (183)";
        					break;
        		case 502: $where = " where id in (244,522,523,524)";
        					break;
        		case 536: $where = " where id in (537)";
        					break;
        		case 538: $where = " where id in (539,540)";
        					break;
        		case 543: $where = " where id in (544)";
        					break;
        		case 545: $where = " where id in (538,541,542,543)";
        					break;
        		case 549: $where = " where id in (550,552)";
        					break;
        	}
        	$this->PHPShopOrm->sql = 'select '.intval($this->category).' as id,name,num,'.intval($this->category).' as parent_to,yml,num_row,num_cow,sort,content,vid,name_rambler,servers,title,title_enabled,title_shablon,descrip,descrip_enabled,descrip_shablon,keywords,keywords_enabled,keywords_shablon,skin,skin_enabled,order_by,order_to,secure_groups,content_h,filtr,icon_description from ' . $this->getValue('base.categories').$where.' LIMIT 1';
        	//echo $this->PHPShopOrm->sql;
        	$this->PHPShopOrm->debug = $this->debug;
        	$parent_category_row = $this->PHPShopOrm->select();

        	// Перехват модуля
        	$this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'MIDDLE');
        	
        	// Если каталоги
        	$this->CID_Category();

       		// Перехват модуля
        	$this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'END');
        	      	
        } else {
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
     * @param $category
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
		//if ($this->category==15) echo $this->category;
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
        	//print_r($order);
        	 
        	$this->dataArray = parent::getListInfoItem(false, false, false, __CLASS__, __FUNCTION__, $order['sql']);
            
            // Пагинатор
            $this->setPaginator(count($this->dataArray), $order['sql']);
        } else {
            // Сложный запрос
            //echo $this->PHPShopOrm->sql;
            $this->PHPShopOrm->sql = "select *, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from " . $this->SysValue['base']['products'] . " where " . $order;
            $this->PHPShopOrm->debug = $this->debug;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
            // Пагинатор
            $this->setPaginator(count($this->dataArray), $order);
        }
		//echo $this->PHPShopOrm->sql;
        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // Родительская категория
        $cat = $this->PHPShopCategory->getParam('parent_to');
		//echo $cat;

		// Данные родительской категории
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array();
        }
		//echo $this->PHPShopCategory->getName();
        //$this->set('catalogCat', $parent_category_row['name']);
		//$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)stihl(.*)$/i','$1Штиль$3',$this->PHPShopCategory->getName());
		//$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)viking(.*)$/i','$1Викинг$3',$this->PHPShopCategory->getName());
		//if ($this->PHPShopNav->getId()!=135 && $this->PHPShopNav->getId()!=458) {
		//echo $this->PHPShopNav->getId();
		//$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)karcher(.*)$/i','$1Керхер$3',$this->PHPShopCategory->getName());		
		//} else {
		$this->PHPShopCategory->objRow['name']=$this->PHPShopCategory->getName();
		//}
		//print_r($this->PHPShopCategory->objRow);	
        $this->set('catalogCategory', $this->PHPShopCategory->getName());
        $this->set('productId', $this->category);
        $this->set('catalogId', $cat);
        $this->set('pcatalogId', $this->category);

        // Фильтр товаров
        //PHPShopObj::loadClass('sort');
        //$PHPShopSort = new PHPShopSort($this->category, $this->PHPShopCategory->getParam('sort'), true, $this->sort_template);
        //$this->set('vendorDisp', $PHPShopSort->display());
		//echo $PHPShopSort->display();
		//print_r($this->dataArray);
		//echo '-'.intval($this->dataArray['category']).'-';
		// Выделение текущего каталога в меню
		$this->setActiveMenu();
			
		// Мета заголовки
		$this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));
			
		// Дублирующая навигация
		$this->other_cat_navigation($cat);
			
		// Навигация хлебных крошек для новых шаблонов
		$this->navigation($cat, $this->PHPShopCategory->getName());
		//echo '10';
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
    	
    	$id_excluded=array();
    	    	
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
        //echo $this->PHPShopCategory->getName();

        //читаем данные из файла конфигурации по кастомизированным меню
        $this->custom_menu_3=array();
        $this->custom_menu_3=custom_menu_1('custom_menu_3.txt');
        
        switch (intval($this->PHPShopNav->getId())) {
        	case 473: $where = " where skin_enabled!='1' and id in (38,120,62,314,45,74,19,15,17,20,21,22,30,27,28,23,24,25,26,29,229,126,173)";
        			  break;
        	case 474: $where = " where skin_enabled!='1' and id in (42,46,316)";
        			  break;
        	case 475: $where = " where skin_enabled!='1' and id in (41,319,40,318,330)";
        			  break;
        	case 476: $where = " where skin_enabled!='1' and id in (70,71,131,102,112,219,409,414,144,198,92,268,269)";
        			  break;
        	case 477: $where = " where skin_enabled!='1' and id in (84,127,101,138,216,410,255,309,420,226,305,212)";
        			  break;
        	case 478: $where = " where skin_enabled!='1' and id in (83,105,113,415)";
        			  break;
        	case 479: $where = " where skin_enabled!='1' and id in (245,103,116,220,411,424,200)";
        			  break;
        	case 480: $where = " where skin_enabled!='1' and id in (128,100)";
        			  break;
        	case 481: $where = " where skin_enabled!='1' and id in (251,193,203,218,408,310,422,271,225,307,509,517)";
        			  break;
        	case 482: $where = " where skin_enabled!='1' and id in (179,178,416,150,325,326)";
        			  break;
        	case 483: $where = " where skin_enabled!='1' and id in (129,109,217,421,213)";
        			  break;
        	case 484: $where = " where skin_enabled!='1' and id in (171,321)";
        			  break;
        	case 485: $where = " where skin_enabled!='1' and id in (240,239,227,241,238,418,204,235)";
        			  break;
        	case 486: $where = " where skin_enabled!='1' and id in (174,210,221)";
        			  break;
        	case 487: $where = " where skin_enabled!='1' and id in (175,207,223)";
        			  break;
        	case 488: $where = " where skin_enabled!='1' and id in (181,205,180,222)";
        			  break;
        	case 489: $where = " where skin_enabled!='1' and id in (195,246,194,185,412,199)";
        			  break;
        	case 490: $where = " where skin_enabled!='1' and id in (140,111,152)";
        			  break;
        	case 491: $where = " where skin_enabled!='1' and id in (139,104)";
        			  break;
        	case 492: $where = " where skin_enabled!='1' and id in (52,190)";
        			  break;
        	case 493: $where = " where skin_enabled!='1' and id in (303,156,115,413,425,148,243,197,530)";
        			  break;
        	case 494: $where = " where skin_enabled!='1' and id in (423,132,106,114,313)";
        			  break;
        	case 495: $where = " where skin_enabled!='1' and id in (419,151)";
        			  break;
        	case 496: $where = " where skin_enabled!='1' and id in (130,108,306,537)";
        			  break;
        	case 497: $where = " where skin_enabled!='1' and id in (168,170)";
        			  break;
        	case 498: $where = " where skin_enabled!='1' and id in (209)";
        			  break;
        	case 499: $where = " where skin_enabled!='1' and id in (149)";
        			  break;
        	case 500: $where = " where skin_enabled!='1' and id in (304,186)";
        			  break;
        	case 501: $where = " where skin_enabled!='1' and id in (183)";
        			  break;
        	case 502: $where = " where skin_enabled!='1' and id in (244,522,523,524)";
        			  break;
        	//case 503: $where = " where skin_enabled!='1' and id in (504,505,506,507,508)";
        	//		  break;
        	case 509: $where = " where skin_enabled!='1' and id in (510,511,512,513,514)";
        			  break;
        	case 536: $where = " where skin_enabled!='1' and id in (537)";
        			  break;
        	case 538: $where = " where skin_enabled!='1' and id in (539,540)";
        			  break;
        	case 543: $where = " where id in (544)";
        			  break;
        	case 545: $where = " where id in (538,541,542,543)";
        			  break;
        	case 549: $where = " where id in (550,552)";
        			  break;
        	default: // Условия выборки
					 $where = array('parent_to' => '=' . $this->category, 'skin_enabled' => "!='1'");
        }

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.categories'));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;
        $dis = null;
		//изменение для каталога техника по производителю
		if (intval($this->PHPShopNav->getId())==473 || intval($this->PHPShopNav->getId())==474 || intval($this->PHPShopNav->getId())==475 ||
			intval($this->PHPShopNav->getId())==476 || intval($this->PHPShopNav->getId())==477 || intval($this->PHPShopNav->getId())==478 ||
			intval($this->PHPShopNav->getId())==479 || intval($this->PHPShopNav->getId())==480 || intval($this->PHPShopNav->getId())==481 ||
			intval($this->PHPShopNav->getId())==482 || intval($this->PHPShopNav->getId())==483 || intval($this->PHPShopNav->getId())==484 ||
			intval($this->PHPShopNav->getId())==485 || intval($this->PHPShopNav->getId())==486 || intval($this->PHPShopNav->getId())==487 ||
			intval($this->PHPShopNav->getId())==488 || intval($this->PHPShopNav->getId())==489 || intval($this->PHPShopNav->getId())==490 ||
			intval($this->PHPShopNav->getId())==491 || intval($this->PHPShopNav->getId())==492 || intval($this->PHPShopNav->getId())==493 ||
			intval($this->PHPShopNav->getId())==494 || intval($this->PHPShopNav->getId())==495 || intval($this->PHPShopNav->getId())==496 ||
			intval($this->PHPShopNav->getId())==497 || intval($this->PHPShopNav->getId())==498 || intval($this->PHPShopNav->getId())==499 ||
			intval($this->PHPShopNav->getId())==500 || intval($this->PHPShopNav->getId())==501 || intval($this->PHPShopNav->getId())==502 ||
			intval($this->PHPShopNav->getId())==509	|| intval($this->PHPShopNav->getId())==536 || intval($this->PHPShopNav->getId())==538 ||
			intval($this->PHPShopNav->getId())==543 || intval($this->PHPShopNav->getId())==545 || intval($this->PHPShopNav->getId())==549) 
		{
			$PHPShopOrm->sql="select id,case name_rambler when '' then name else name_rambler end as name,num,".$this->PHPShopNav->getId().' as parent_to,yml,num_row,num_cow,sort,content,vid,name_rambler,servers,title,title_enabled,title_shablon,descrip,descrip_enabled,descrip_shablon,keywords,keywords_enabled,keywords_shablon,skin,skin_enabled,order_by,order_to,secure_groups,content_h,filtr,icon_description from '. $this->getValue('base.categories') . $where;
			$dataArray = $PHPShopOrm->select();
		} else {
			$dataArray = $PHPShopOrm->select(array('id',"case name_rambler when '' then name else name_rambler end as name",'num','parent_to','yml','num_row','num_cow','sort','content','vid','name_rambler','servers','title','title_enabled','title_shablon','descrip','descrip_enabled','descrip_shablon','keywords','keywords_enabled','keywords_shablon','skin','skin_enabled','order_by','order_to','secure_groups','content_h','filtr','icon_description'), $where, array('order' => 'num'), array('limit' => $this->max_item));
		}
        //print_r($dataArray);
        if (is_array($dataArray))		
			$cnt1=0;
			$cnt_cur_row_by_type=0;
			$cnt2=0;
			$cnt_cur_row_by_maker=0;
			$cnt_by_type=0;
			$cnt_by_maker=0;
			$catalog_items_by_type_table_td=array();
			$catalog_items_by_maker_table_td=array();
			//echo $this->PHPShopNav->getId();
			
			foreach ($dataArray as $row) {
				//считаем сколько у нас элементов без скобок, по типу и со скобками, по производителю
				if ( 
					($this->PHPShopNav->getId()==5 || $this->PHPShopNav->getId()==9 || $this->PHPShopNav->getId()==16 ||
					$this->PHPShopNav->getId()==18 || $this->PHPShopNav->getId()==30 ||	$this->PHPShopNav->getId()==31 ||
					$this->PHPShopNav->getId()==32 || $this->PHPShopNav->getId()==33 ||	$this->PHPShopNav->getId()==34 ||
					$this->PHPShopNav->getId()==35 || $this->PHPShopNav->getId()==36 || $this->PHPShopNav->getId()==37 ||
					$this->PHPShopNav->getId()==38 || $this->PHPShopNav->getId()==44 ||	$this->PHPShopNav->getId()==60 ||
					$this->PHPShopNav->getId()==62 || $this->PHPShopNav->getId()==77 ||	$this->PHPShopNav->getId()==81 ||							
					$this->PHPShopNav->getId()==85 || $this->PHPShopNav->getId()==88 ||	$this->PHPShopNav->getId()==96 ||
					$this->PHPShopNav->getId()==97 || $this->PHPShopNav->getId()==98 ||	$this->PHPShopNav->getId()==99 ||
					$this->PHPShopNav->getId()==120	|| $this->PHPShopNav->getId()==121 || $this->PHPShopNav->getId()==122 || 
					$this->PHPShopNav->getId()==123 ||							
					$this->PHPShopNav->getId()==134 || $this->PHPShopNav->getId()==141 || $this->PHPShopNav->getId()==142 ||
					$this->PHPShopNav->getId()==143 || $this->PHPShopNav->getId()==154 || $this->PHPShopNav->getId()==172 ||
					$this->PHPShopNav->getId()==186 || $this->PHPShopNav->getId()==190 || $this->PHPShopNav->getId()==191 ||
					$this->PHPShopNav->getId()==201 || $this->PHPShopNav->getId()==211 || $this->PHPShopNav->getId()==215 ||
					$this->PHPShopNav->getId()==224	|| $this->PHPShopNav->getId()==227 || $this->PHPShopNav->getId()==228 ||
					$this->PHPShopNav->getId()==234 || $this->PHPShopNav->getId()==247 || $this->PHPShopNav->getId()==248 ||
					$this->PHPShopNav->getId()==249 || $this->PHPShopNav->getId()==254 || $this->PHPShopNav->getId()==252 ||
					$this->PHPShopNav->getId()==256 || $this->PHPShopNav->getId()==257 || $this->PHPShopNav->getId()==258 ||
					$this->PHPShopNav->getId()==270 || $this->PHPShopNav->getId()==272 || $this->PHPShopNav->getId()==288 ||
					$this->PHPShopNav->getId()==290 || $this->PHPShopNav->getId()==292 || $this->PHPShopNav->getId()==293 ||
					$this->PHPShopNav->getId()==295 || $this->PHPShopNav->getId()==297 || $this->PHPShopNav->getId()==298 ||
					$this->PHPShopNav->getId()==299 || $this->PHPShopNav->getId()==300 || $this->PHPShopNav->getId()==332 ||
					$this->PHPShopNav->getId()==333	|| $this->PHPShopNav->getId()==334 || $this->PHPShopNav->getId()==335 ||
					$this->PHPShopNav->getId()==336 || $this->PHPShopNav->getId()==337 || $this->PHPShopNav->getId()==350 ||
					$this->PHPShopNav->getId()==351 || $this->PHPShopNav->getId()==352 || $this->PHPShopNav->getId()==353 ||
					$this->PHPShopNav->getId()==354 || $this->PHPShopNav->getId()==355 || $this->PHPShopNav->getId()==356 ||
					$this->PHPShopNav->getId()==382	|| $this->PHPShopNav->getId()==414 || $this->PHPShopNav->getId()==415 ||
					$this->PHPShopNav->getId()==416	|| $this->PHPShopNav->getId()==418 || $this->PHPShopNav->getId()==419 ||
					$this->PHPShopNav->getId()==420	|| $this->PHPShopNav->getId()==421 || $this->PHPShopNav->getId()==422 ||
					$this->PHPShopNav->getId()==423	|| $this->PHPShopNav->getId()==424 || $this->PHPShopNav->getId()==425 ||
					$this->PHPShopNav->getId()==436	|| $this->PHPShopNav->getId()==437 || $this->PHPShopNav->getId()==440 ||
					$this->PHPShopNav->getId()==442	|| $this->PHPShopNav->getId()==459 || $this->PHPShopNav->getId()==461 ||
					$this->PHPShopNav->getId()==464 || $this->PHPShopNav->getId()==465 || $this->PHPShopNav->getId()==467 ||
					$this->PHPShopNav->getId()==503 || $this->PHPShopNav->getId()==509 || $this->PHPShopNav->getId()==536 || 
					$this->PHPShopNav->getId()==538 || $this->PHPShopNav->getId()==509 || $this->PHPShopNav->getId()==536 || 
					$this->PHPShopNav->getId()==543 || $this->PHPShopNav->getId()==545 || $this->PHPShopNav->getId()==549 || 
					$this->PHPShopNav->getId()==555
					)) {
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0) {
						$cnt_by_type++;
					}
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])) {
						$cnt_by_maker++;
					}
					//исключение для каталога техника по производителю
				} else if ($this->PHPShopNav->getId()==472 ||
						   $this->PHPShopNav->getId()==473 || $this->PHPShopNav->getId()==474 ||
						   $this->PHPShopNav->getId()==475 || $this->PHPShopNav->getId()==476 ||
						   $this->PHPShopNav->getId()==477 || $this->PHPShopNav->getId()==478 ||
						   $this->PHPShopNav->getId()==479 || $this->PHPShopNav->getId()==480 ||
						   $this->PHPShopNav->getId()==481 || $this->PHPShopNav->getId()==482 ||
						   $this->PHPShopNav->getId()==483 || $this->PHPShopNav->getId()==484 ||
						   $this->PHPShopNav->getId()==485 || $this->PHPShopNav->getId()==486 ||
						   $this->PHPShopNav->getId()==487 || $this->PHPShopNav->getId()==488 ||
						   $this->PHPShopNav->getId()==489 || $this->PHPShopNav->getId()==490 ||
						   $this->PHPShopNav->getId()==491 || $this->PHPShopNav->getId()==492 ||
						   $this->PHPShopNav->getId()==493 || $this->PHPShopNav->getId()==494 ||
						   $this->PHPShopNav->getId()==495 || $this->PHPShopNav->getId()==496 ||
						   $this->PHPShopNav->getId()==497 || $this->PHPShopNav->getId()==498 ||
						   $this->PHPShopNav->getId()==499 || $this->PHPShopNav->getId()==500 ||
						   $this->PHPShopNav->getId()==501 || $this->PHPShopNav->getId()==502 ||
						   $this->PHPShopNav->getId()==503 || $this->PHPShopNav->getId()==509 || 
						   $this->PHPShopNav->getId()==536 || $this->PHPShopNav->getId()==538 || 
						   $this->PHPShopNav->getId()==543 || $this->PHPShopNav->getId()==545 ||
						   $this->PHPShopNav->getId()==549
						) {
						$cnt_by_type++;
				}
			
			}
								
			//собираем массивы по типу и по производителю
			foreach ($dataArray as $row) {
				//переменные для построения кастомизированных ссылок типа: товар (аналог)
				$id_custom_menu_3_true=false;
				$id_custom_menu_3_false=false;
				//print_r($row['name']);
                $dis.=PHPShopText::li($row['name'], $this->path . '/CID_' . $row['id'] . '.html');
				//($cnt1==0 || $cnt1==1 || $cnt1==2) &&
				if (
					($this->PHPShopNav->getId()==5 || $this->PHPShopNav->getId()==9 || $this->PHPShopNav->getId()==16 ||
					$this->PHPShopNav->getId()==18 || $this->PHPShopNav->getId()==30 ||	$this->PHPShopNav->getId()==31 ||
					$this->PHPShopNav->getId()==32 || $this->PHPShopNav->getId()==33 ||	$this->PHPShopNav->getId()==34 ||
					$this->PHPShopNav->getId()==35 || $this->PHPShopNav->getId()==36 || $this->PHPShopNav->getId()==37 ||
					$this->PHPShopNav->getId()==38 || $this->PHPShopNav->getId()==44 ||	$this->PHPShopNav->getId()==60 ||
					$this->PHPShopNav->getId()==62 || $this->PHPShopNav->getId()==77 ||	$this->PHPShopNav->getId()==81 ||							
					$this->PHPShopNav->getId()==85 || $this->PHPShopNav->getId()==88 ||	$this->PHPShopNav->getId()==96 ||
					$this->PHPShopNav->getId()==97 || $this->PHPShopNav->getId()==98 ||	$this->PHPShopNav->getId()==99 ||
					$this->PHPShopNav->getId()==120	|| $this->PHPShopNav->getId()==121 || $this->PHPShopNav->getId()==122 || $this->PHPShopNav->getId()==123 ||							
					$this->PHPShopNav->getId()==134 || $this->PHPShopNav->getId()==141 || $this->PHPShopNav->getId()==142 ||
					$this->PHPShopNav->getId()==143 || $this->PHPShopNav->getId()==154 || $this->PHPShopNav->getId()==172 ||
					$this->PHPShopNav->getId()==186 || $this->PHPShopNav->getId()==190 || $this->PHPShopNav->getId()==191 ||
					$this->PHPShopNav->getId()==201 || $this->PHPShopNav->getId()==211 || $this->PHPShopNav->getId()==215 ||
					$this->PHPShopNav->getId()==224	|| $this->PHPShopNav->getId()==227 || $this->PHPShopNav->getId()==228 ||
					$this->PHPShopNav->getId()==234 || $this->PHPShopNav->getId()==247 || $this->PHPShopNav->getId()==248 ||
					$this->PHPShopNav->getId()==249 || $this->PHPShopNav->getId()==254 || $this->PHPShopNav->getId()==252 ||
					$this->PHPShopNav->getId()==256 || $this->PHPShopNav->getId()==257 || $this->PHPShopNav->getId()==258 ||
					$this->PHPShopNav->getId()==270 || $this->PHPShopNav->getId()==272 || $this->PHPShopNav->getId()==288 ||
					$this->PHPShopNav->getId()==290 || $this->PHPShopNav->getId()==292 || $this->PHPShopNav->getId()==293 ||
					$this->PHPShopNav->getId()==295 || $this->PHPShopNav->getId()==297 || $this->PHPShopNav->getId()==298 ||
					$this->PHPShopNav->getId()==299 || $this->PHPShopNav->getId()==300 || $this->PHPShopNav->getId()==332 ||
					$this->PHPShopNav->getId()==333	|| $this->PHPShopNav->getId()==334 || $this->PHPShopNav->getId()==335 ||
					$this->PHPShopNav->getId()==336 || $this->PHPShopNav->getId()==337 || $this->PHPShopNav->getId()==350 ||
					$this->PHPShopNav->getId()==351 || $this->PHPShopNav->getId()==352 || $this->PHPShopNav->getId()==353 ||
					$this->PHPShopNav->getId()==354 || $this->PHPShopNav->getId()==355 || $this->PHPShopNav->getId()==356 ||
					$this->PHPShopNav->getId()==382	|| $this->PHPShopNav->getId()==414 || $this->PHPShopNav->getId()==415 ||
					$this->PHPShopNav->getId()==416	|| $this->PHPShopNav->getId()==418 || $this->PHPShopNav->getId()==419 ||
					$this->PHPShopNav->getId()==420	|| $this->PHPShopNav->getId()==421 || $this->PHPShopNav->getId()==422 ||
					$this->PHPShopNav->getId()==423	|| $this->PHPShopNav->getId()==424 || $this->PHPShopNav->getId()==425 ||
					$this->PHPShopNav->getId()==436	|| $this->PHPShopNav->getId()==437 || $this->PHPShopNav->getId()==440 ||
					$this->PHPShopNav->getId()==442	|| $this->PHPShopNav->getId()==459 || $this->PHPShopNav->getId()==461 ||
					$this->PHPShopNav->getId()==464 || $this->PHPShopNav->getId()==465 || $this->PHPShopNav->getId()==467 || 
					$this->PHPShopNav->getId()==503 || $this->PHPShopNav->getId()==509 || $this->PHPShopNav->getId()==536 || 
					$this->PHPShopNav->getId()==538 || $this->PHPShopNav->getId()==543 || $this->PHPShopNav->getId()==545 ||
					$this->PHPShopNav->getId()==549 || $this->PHPShopNav->getId()==555
				   )) {
					//для некоторых категорий переделываем все на table
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0) {
						foreach ($this->custom_menu_3 as $custom_menu_3_item) {
							//print_r($custom_menu_3_item);
							//делаем исключения
							if (in_array($row['id'],$custom_menu_3_item)===true) {
								if ($custom_menu_3_item['id']==$row['id']) {
									//print_r($custom_menu_3_item);
									//echo $row['id'];									
									$custom_href=$custom_menu_3_item['href'];
									$custom_css_option_width=$custom_menu_3_item['css_option_width'];
									$id_custom_menu_3_true=true;
									$catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false).$custom_href;
									array_push($catalog_items_by_type_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
									array_push($id_excluded,$custom_menu_3_item['sub_id']);
									//print_r($catalog_items_by_type_table_td);
									$cnt1++;
									$cnt_cur_row_by_type++;
									break;
								}
							} else if (in_array($row['id'],$custom_menu_3_item)===false) {
								$id_custom_menu_3_false=true;
							}
						}
						//echo $id_custom_menu_3_true.$id_custom_menu_3_false.'<br />';
						if ($id_custom_menu_3_true===false && $id_custom_menu_3_false===true && in_array($row['id'],$id_excluded)===false){

							$catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
							array_push($catalog_items_by_type_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
							$cnt1++;
							$cnt_cur_row_by_type++;
						}
					}
					
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])) {
						$catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
						array_push($catalog_items_by_maker_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
						$cnt2++;
						$cnt_cur_row_by_maker++;
					}
				//исключение для 473 каталога по производителю
				} else if ($this->PHPShopNav->getId()==472 || $this->PHPShopNav->getId()==473 ||
						   $this->PHPShopNav->getId()==474 ||
						   $this->PHPShopNav->getId()==475 || $this->PHPShopNav->getId()==476 ||
						   $this->PHPShopNav->getId()==477 || $this->PHPShopNav->getId()==478 ||
						   $this->PHPShopNav->getId()==479 || $this->PHPShopNav->getId()==480 ||
						   $this->PHPShopNav->getId()==481 || $this->PHPShopNav->getId()==482 ||
						   $this->PHPShopNav->getId()==483 || $this->PHPShopNav->getId()==484 ||
						   $this->PHPShopNav->getId()==485 || $this->PHPShopNav->getId()==486 ||
						   $this->PHPShopNav->getId()==487 || $this->PHPShopNav->getId()==488 ||
						   $this->PHPShopNav->getId()==489 || $this->PHPShopNav->getId()==490 ||
						   $this->PHPShopNav->getId()==491 || $this->PHPShopNav->getId()==492 ||
						   $this->PHPShopNav->getId()==493 || $this->PHPShopNav->getId()==494 ||
						   $this->PHPShopNav->getId()==495 || $this->PHPShopNav->getId()==496 ||
						   $this->PHPShopNav->getId()==497 || $this->PHPShopNav->getId()==498 ||
						   $this->PHPShopNav->getId()==499 || $this->PHPShopNav->getId()==500 ||
						   $this->PHPShopNav->getId()==501 || $this->PHPShopNav->getId()==502 || 
						   $this->PHPShopNav->getId()==509 || $this->PHPShopNav->getId()==536 || 
						   $this->PHPShopNav->getId()==538 || $this->PHPShopNav->getId()==543 || 
						   $this->PHPShopNav->getId()==545 || $this->PHPShopNav->getId()==549) {
					$catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
					array_push($catalog_items_by_type_table_td,$catalog_items_content);
					$cnt1++;
					$cnt_cur_row_by_type++;
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
		if 	(
			$this->PHPShopNav->getId()==5 || $this->PHPShopNav->getId()==9 || $this->PHPShopNav->getId()==16 ||
			$this->PHPShopNav->getId()==18 || $this->PHPShopNav->getId()==30 ||	$this->PHPShopNav->getId()==31 ||
			$this->PHPShopNav->getId()==32 || $this->PHPShopNav->getId()==33 ||	$this->PHPShopNav->getId()==34 ||
			$this->PHPShopNav->getId()==35 || $this->PHPShopNav->getId()==36 || $this->PHPShopNav->getId()==37 ||
			$this->PHPShopNav->getId()==38 || $this->PHPShopNav->getId()==44 ||	$this->PHPShopNav->getId()==60 ||
			$this->PHPShopNav->getId()==62 || $this->PHPShopNav->getId()==77 ||	$this->PHPShopNav->getId()==81 ||							
			$this->PHPShopNav->getId()==85 || $this->PHPShopNav->getId()==88 ||	$this->PHPShopNav->getId()==96 ||
			$this->PHPShopNav->getId()==97 || $this->PHPShopNav->getId()==98 ||	$this->PHPShopNav->getId()==99 ||
			$this->PHPShopNav->getId()==120	|| $this->PHPShopNav->getId()==121 || $this->PHPShopNav->getId()==122 || $this->PHPShopNav->getId()==123 ||							
			$this->PHPShopNav->getId()==134 || $this->PHPShopNav->getId()==141 || $this->PHPShopNav->getId()==142 ||
			$this->PHPShopNav->getId()==143 || $this->PHPShopNav->getId()==154 || $this->PHPShopNav->getId()==172 ||
			$this->PHPShopNav->getId()==186 || $this->PHPShopNav->getId()==190 || $this->PHPShopNav->getId()==191 ||
			$this->PHPShopNav->getId()==201 || $this->PHPShopNav->getId()==211 || $this->PHPShopNav->getId()==215 ||
			$this->PHPShopNav->getId()==224	|| $this->PHPShopNav->getId()==227 || $this->PHPShopNav->getId()==228 ||
			$this->PHPShopNav->getId()==234 || $this->PHPShopNav->getId()==247 || $this->PHPShopNav->getId()==248 ||
			$this->PHPShopNav->getId()==249 || $this->PHPShopNav->getId()==254 || $this->PHPShopNav->getId()==252 ||
			$this->PHPShopNav->getId()==256 || $this->PHPShopNav->getId()==257 || $this->PHPShopNav->getId()==258 ||
			$this->PHPShopNav->getId()==270 || $this->PHPShopNav->getId()==272 || $this->PHPShopNav->getId()==288 ||
			$this->PHPShopNav->getId()==290 || $this->PHPShopNav->getId()==292 || $this->PHPShopNav->getId()==293 ||
			$this->PHPShopNav->getId()==295 || $this->PHPShopNav->getId()==297 || $this->PHPShopNav->getId()==298 ||
			$this->PHPShopNav->getId()==299 || $this->PHPShopNav->getId()==300 || $this->PHPShopNav->getId()==332 ||
			$this->PHPShopNav->getId()==333	|| $this->PHPShopNav->getId()==334 || $this->PHPShopNav->getId()==335 ||
			$this->PHPShopNav->getId()==336 || $this->PHPShopNav->getId()==337 || $this->PHPShopNav->getId()==350 ||
			$this->PHPShopNav->getId()==351 || $this->PHPShopNav->getId()==352 || $this->PHPShopNav->getId()==353 ||
			$this->PHPShopNav->getId()==354 || $this->PHPShopNav->getId()==355 || $this->PHPShopNav->getId()==356 ||
			$this->PHPShopNav->getId()==382	|| $this->PHPShopNav->getId()==414 || $this->PHPShopNav->getId()==415 ||
			$this->PHPShopNav->getId()==416	|| $this->PHPShopNav->getId()==418 || $this->PHPShopNav->getId()==419 ||
			$this->PHPShopNav->getId()==420	|| $this->PHPShopNav->getId()==421 || $this->PHPShopNav->getId()==422 ||
			$this->PHPShopNav->getId()==423	|| $this->PHPShopNav->getId()==424 || $this->PHPShopNav->getId()==425 ||
			$this->PHPShopNav->getId()==436	|| $this->PHPShopNav->getId()==437 || $this->PHPShopNav->getId()==440 ||
			$this->PHPShopNav->getId()==442	|| $this->PHPShopNav->getId()==459 || $this->PHPShopNav->getId()==461 ||
			$this->PHPShopNav->getId()==464 || $this->PHPShopNav->getId()==465 || $this->PHPShopNav->getId()==467 ||
			$this->PHPShopNav->getId()==472 || $this->PHPShopNav->getId()==473 || $this->PHPShopNav->getId()==474 ||
			$this->PHPShopNav->getId()==475 || $this->PHPShopNav->getId()==476 || $this->PHPShopNav->getId()==477 ||
			$this->PHPShopNav->getId()==478 || $this->PHPShopNav->getId()==479 || $this->PHPShopNav->getId()==480 ||
			$this->PHPShopNav->getId()==481 || $this->PHPShopNav->getId()==482 || $this->PHPShopNav->getId()==483 || 
			$this->PHPShopNav->getId()==484 || $this->PHPShopNav->getId()==485 || $this->PHPShopNav->getId()==486 ||
			$this->PHPShopNav->getId()==487 || $this->PHPShopNav->getId()==488 || $this->PHPShopNav->getId()==489 || 
			$this->PHPShopNav->getId()==490 || $this->PHPShopNav->getId()==491 || $this->PHPShopNav->getId()==492 ||
			$this->PHPShopNav->getId()==493 || $this->PHPShopNav->getId()==494 || $this->PHPShopNav->getId()==495 || 
			$this->PHPShopNav->getId()==496 || $this->PHPShopNav->getId()==497 || $this->PHPShopNav->getId()==498 ||
			$this->PHPShopNav->getId()==499 || $this->PHPShopNav->getId()==500 || $this->PHPShopNav->getId()==501 ||
			$this->PHPShopNav->getId()==502 || $this->PHPShopNav->getId()==503 || $this->PHPShopNav->getId()==509 || 
			$this->PHPShopNav->getId()==536 || $this->PHPShopNav->getId()==538 || $this->PHPShopNav->getId()==543 || 
			$this->PHPShopNav->getId()==545 || $this->PHPShopNav->getId()==549 || $this->PHPShopNav->getId()==555
		    ) {
			$disp1=PHPShopText::table($catalog_items_table1,3,1,'center','98%',false,0,'catalog_items_table1');
			$disp2=PHPShopText::table($catalog_items_table2,3,1,'center','98%',false,0,'catalog_items_table2');			
		} else {	
			$disp = PHPShopText::ul($dis);
		}
		
        //$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
        //$disp = PHPShopText::ul($dis);
        //echo $this->category_name;
        /*
		$this->category_name=preg_replace('/^(.*)stihl(.*)$/i','$1Штиль$3',$this->category_name);
		$this->category_name=preg_replace('/^(.*)viking(.*)$/i','$1Викинг$3',$this->category_name);
		$this->category_name=preg_replace('/^(.*)karcher(.*)$/i','$1Керхер$3',$this->category_name);
		*/
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
        
        $this->set('productValutaName', $this->currency());

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
		
		if 	(
			$this->PHPShopNav->getId()==5 || $this->PHPShopNav->getId()==9 || $this->PHPShopNav->getId()==16 ||
			$this->PHPShopNav->getId()==18 || $this->PHPShopNav->getId()==30 ||	$this->PHPShopNav->getId()==31 ||
			$this->PHPShopNav->getId()==32 || $this->PHPShopNav->getId()==33 ||	$this->PHPShopNav->getId()==34 ||
			$this->PHPShopNav->getId()==35 || $this->PHPShopNav->getId()==36 || $this->PHPShopNav->getId()==37 ||
			$this->PHPShopNav->getId()==38 || $this->PHPShopNav->getId()==44 ||	$this->PHPShopNav->getId()==60 ||
			$this->PHPShopNav->getId()==62 || $this->PHPShopNav->getId()==77 ||	$this->PHPShopNav->getId()==81 ||							
			$this->PHPShopNav->getId()==85 || $this->PHPShopNav->getId()==88 ||	$this->PHPShopNav->getId()==96 ||
			$this->PHPShopNav->getId()==97 || $this->PHPShopNav->getId()==98 ||	$this->PHPShopNav->getId()==99 ||
			$this->PHPShopNav->getId()==120	|| $this->PHPShopNav->getId()==121 || $this->PHPShopNav->getId()==122 ||
			$this->PHPShopNav->getId()==123 ||							
			$this->PHPShopNav->getId()==134 || $this->PHPShopNav->getId()==141 || $this->PHPShopNav->getId()==142 ||
			$this->PHPShopNav->getId()==143 || $this->PHPShopNav->getId()==154 || $this->PHPShopNav->getId()==172 ||
			$this->PHPShopNav->getId()==186 || $this->PHPShopNav->getId()==190 || $this->PHPShopNav->getId()==191 ||
			$this->PHPShopNav->getId()==201 || $this->PHPShopNav->getId()==211 || $this->PHPShopNav->getId()==215 ||
			$this->PHPShopNav->getId()==224	|| $this->PHPShopNav->getId()==227 || $this->PHPShopNav->getId()==228 ||
			$this->PHPShopNav->getId()==234 || $this->PHPShopNav->getId()==247 || $this->PHPShopNav->getId()==248 ||
			$this->PHPShopNav->getId()==249 || $this->PHPShopNav->getId()==254 || $this->PHPShopNav->getId()==252 ||
			$this->PHPShopNav->getId()==256 || $this->PHPShopNav->getId()==257 || $this->PHPShopNav->getId()==258 ||
			$this->PHPShopNav->getId()==270 || $this->PHPShopNav->getId()==272 || $this->PHPShopNav->getId()==288 ||
			$this->PHPShopNav->getId()==290 || $this->PHPShopNav->getId()==292 || $this->PHPShopNav->getId()==293 ||
			$this->PHPShopNav->getId()==295 || $this->PHPShopNav->getId()==297 || $this->PHPShopNav->getId()==298 ||
			$this->PHPShopNav->getId()==299 || $this->PHPShopNav->getId()==300 || $this->PHPShopNav->getId()==332 ||
			$this->PHPShopNav->getId()==333	|| $this->PHPShopNav->getId()==334 || $this->PHPShopNav->getId()==335 ||
			$this->PHPShopNav->getId()==336 || $this->PHPShopNav->getId()==337 || $this->PHPShopNav->getId()==350 ||
			$this->PHPShopNav->getId()==351 || $this->PHPShopNav->getId()==352 || $this->PHPShopNav->getId()==353 ||
			$this->PHPShopNav->getId()==354 || $this->PHPShopNav->getId()==355 || $this->PHPShopNav->getId()==356 ||
			$this->PHPShopNav->getId()==382	|| $this->PHPShopNav->getId()==414 || $this->PHPShopNav->getId()==415 ||
			$this->PHPShopNav->getId()==416	|| $this->PHPShopNav->getId()==418 || $this->PHPShopNav->getId()==419 ||
			$this->PHPShopNav->getId()==420	|| $this->PHPShopNav->getId()==421 || $this->PHPShopNav->getId()==422 ||
			$this->PHPShopNav->getId()==423	|| $this->PHPShopNav->getId()==424 || $this->PHPShopNav->getId()==425 ||
			$this->PHPShopNav->getId()==436	|| $this->PHPShopNav->getId()==437 || $this->PHPShopNav->getId()==440 ||
			$this->PHPShopNav->getId()==442	|| $this->PHPShopNav->getId()==459 || $this->PHPShopNav->getId()==461 ||
			$this->PHPShopNav->getId()==464 || $this->PHPShopNav->getId()==465 || $this->PHPShopNav->getId()==467 ||
			$this->PHPShopNav->getId()==472 || $this->PHPShopNav->getId()==473 || $this->PHPShopNav->getId()==474 ||
			$this->PHPShopNav->getId()==475 || $this->PHPShopNav->getId()==476 || $this->PHPShopNav->getId()==477 ||
			$this->PHPShopNav->getId()==478 || $this->PHPShopNav->getId()==479 || $this->PHPShopNav->getId()==480 ||
			$this->PHPShopNav->getId()==481 || $this->PHPShopNav->getId()==482 || $this->PHPShopNav->getId()==483 || 
			$this->PHPShopNav->getId()==484 || $this->PHPShopNav->getId()==485 || $this->PHPShopNav->getId()==486 ||
			$this->PHPShopNav->getId()==487 || $this->PHPShopNav->getId()==488 || $this->PHPShopNav->getId()==489 || 
			$this->PHPShopNav->getId()==490 || $this->PHPShopNav->getId()==491 || $this->PHPShopNav->getId()==492 ||
			$this->PHPShopNav->getId()==493 || $this->PHPShopNav->getId()==494 || $this->PHPShopNav->getId()==495 || 
			$this->PHPShopNav->getId()==496 || $this->PHPShopNav->getId()==497 || $this->PHPShopNav->getId()==498 ||
			$this->PHPShopNav->getId()==499 || $this->PHPShopNav->getId()==500 || $this->PHPShopNav->getId()==501 ||
			$this->PHPShopNav->getId()==502 || $this->PHPShopNav->getId()==503 || $this->PHPShopNav->getId()==509 || 
			$this->PHPShopNav->getId()==536 || $this->PHPShopNav->getId()==538 || $this->PHPShopNav->getId()==543 || 
			$this->PHPShopNav->getId()==545 || $this->PHPShopNav->getId()==549 || $this->PHPShopNav->getId()==555
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
		//echo 'catalog_end';
		// Подключаем шаблон
		if ($this->PHPShopNav->getId()==472 || $this->PHPShopNav->getId()==473 ||$this->PHPShopNav->getId()==474 ||
			$this->PHPShopNav->getId()==475 || $this->PHPShopNav->getId()==476 || $this->PHPShopNav->getId()==477 ||
			$this->PHPShopNav->getId()==478 || $this->PHPShopNav->getId()==479 || $this->PHPShopNav->getId()==480 ||
			$this->PHPShopNav->getId()==481 || $this->PHPShopNav->getId()==482 || $this->PHPShopNav->getId()==483 || 
			$this->PHPShopNav->getId()==484 || $this->PHPShopNav->getId()==485 || $this->PHPShopNav->getId()==486 ||
			$this->PHPShopNav->getId()==487 || $this->PHPShopNav->getId()==488 || $this->PHPShopNav->getId()==489 || 
			$this->PHPShopNav->getId()==490 || $this->PHPShopNav->getId()==491 || $this->PHPShopNav->getId()==492 ||
			$this->PHPShopNav->getId()==493 || $this->PHPShopNav->getId()==494 || $this->PHPShopNav->getId()==495 || 
			$this->PHPShopNav->getId()==496 || $this->PHPShopNav->getId()==497 || $this->PHPShopNav->getId()==498 ||
			$this->PHPShopNav->getId()==499 || $this->PHPShopNav->getId()==500 || $this->PHPShopNav->getId()==501 ||
			$this->PHPShopNav->getId()==502 || $this->PHPShopNav->getId()==509 || $this->PHPShopNav->getId()==536 || 
			$this->PHPShopNav->getId()==538 || $this->PHPShopNav->getId()==543 || $this->PHPShopNav->getId()==545 ||
			$this->PHPShopNav->getId()==549) {
			$this->parseTemplate($this->getValue('templates.catalog_info_forma_1'));
		} else
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
		$cid_id=$this->PHPShopNav->getId();
		if 	(	$cid_id==5 || $cid_id==9 ||	$cid_id==16 || $cid_id==18 ||	
				$cid_id==30 || $cid_id==31 || $cid_id==32 || $cid_id==33 ||
				$cid_id==34 || $cid_id==35 || $cid_id==36 || $cid_id==37 ||
				$cid_id==44 || $cid_id==60 || $cid_id==77 || $cid_id==81 ||				
				$cid_id==85 || $cid_id==88 || $cid_id==96 || $cid_id==97 ||
				$cid_id==121 ||	$cid_id==134 ||	$cid_id==141 ||	$cid_id==142 ||	
				$cid_id==154 ||
				$cid_id==172 ||	$cid_id==186 ||	$cid_id==190 ||	$cid_id==191 ||
				$cid_id==201 ||	$cid_id==211 ||	$cid_id==215 ||	$cid_id==224 ||
				$cid_id==227 ||	$cid_id==228 ||	$cid_id==247 ||	$cid_id==248 ||
				$cid_id==254 ||	$cid_id==256 ||	$cid_id==272 ||	$cid_id==288 || 
				$cid_id==290 || $cid_id==292 || $cid_id==293 ||	$cid_id==295 ||				
				$cid_id==297 ||	$cid_id==298 ||	$cid_id==299 ||	$cid_id==300 ||
				$cid_id==332 ||	$cid_id==333 ||	$cid_id==334 ||	$cid_id==335 ||	
				$cid_id==336 ||	$cid_id==337 ||	$cid_id==350 ||	$cid_id==351 ||
				$cid_id==352 ||	$cid_id==353 ||	$cid_id==354 ||	$cid_id==355 ||
				$cid_id==356 ||	$cid_id==382 ||	$cid_id==414 ||	$cid_id==415 ||
				$cid_id==416 ||	$cid_id==418 ||	$cid_id==419 ||	$cid_id==420 ||
				$cid_id==421 ||	$cid_id==422 ||	$cid_id==423 ||	$cid_id==424 ||
				$cid_id==425 ||	$cid_id==431 ||	$cid_id==432 ||	$cid_id==433 ||
				$cid_id==436 ||	$cid_id==437 ||	$cid_id==440 ||	$cid_id==442 ||
				$cid_id==459 ||	$cid_id==461 ||	$cid_id==464 || $cid_id==465 ||
				$cid_id==467 || $cid_id==472 || $cid_id==473 || $cid_id==474 ||
				$cid_id==475 || $cid_id==476 || $cid_id==477 || $cid_id==478 ||
				$cid_id==479 || $cid_id==480 || $cid_id==481 || $cid_id==482 ||
				$cid_id==483 || $cid_id==484 || $cid_id==485 || $cid_id==486 || 
				$cid_id==487 || $cid_id==488 || $cid_id==489 || $cid_id==490 || 
				$cid_id==491 || $cid_id==492 || $cid_id==493 || $cid_id==494 || 
				$cid_id==495 || $cid_id==496 || $cid_id==497 || $cid_id==498 || 
				$cid_id==499 || $cid_id==500 || $cid_id==501 || $cid_id==502 ||
				$cid_id==503 || $cid_id==509 || $cid_id==536 || $cid_id==538 || 
				$cid_id==543 || $cid_id==545 || $cid_id==549 || $cid_id==555
		) {
			// Путь для навигации
			$this->objPath = './CID_' . $this->category . '_';	
		
			// Количество ячеек для вывода товара
			$cell = $this->PHPShopCategory->getParam('num_row');

			// Кол-во товаров на странице
			$num_cow = $this->PHPShopCategory->getParam('num_cow');
			if (!empty($num_cow))
				$this->num_row = $num_cow;

			$orderby=' sklad asc,price desc,outdated asc ';
			$orderby_1=' sortorder asc,price desc ';
	        if (!empty($_GET['s']) and is_numeric($_GET['s']) and !empty($_GET['f']) and is_numeric($_GET['f'])) {
				if ($_GET['f']==1 and $_GET['s']==2) {
					$orderby=' price asc,sklad asc,outdated asc ';
					$orderby_1=' price asc,sklad asc,outdated asc ';
				}
				if ($_GET['f']==2 and $_GET['s']==2) {
					$orderby=' price desc,sklad asc,outdated asc ';
					$orderby_1=' price desc,sklad asc,outdated asc ';
				}	
				if ($_GET['f']==1 and $_GET['s']==1) {
					$orderby=' name asc,sklad asc,outdated asc ';
					$orderby_1=' name asc,sklad asc,outdated asc ';
				}
				if ($_GET['f']==2 and $_GET['s']==1) {
					$orderby=' name desc,sklad asc,outdated asc ';
					$orderby_1=' name desc,sklad asc,outdated asc ';
				}
				if ($_GET['f']==2 and $_GET['s']==3) {
					$orderby=' num asc,sklad asc,outdated asc ';
					$orderby_1=' num asc,sklad asc,outdated asc ';
				}
				if ($_GET['f']==1 and $_GET['s']==3) {
					$orderby=' num desc,sklad asc,outdated asc ';
					$orderby_1=' num desc,sklad asc,outdated asc ';
				}
				
	        }
			if ($cid_id==31 ||	$cid_id==32 || $cid_id==33 || $cid_id==34 || $cid_id==35 ||
	         	$cid_id==81 || $cid_id==96 || $cid_id==121 || $cid_id==141 || $cid_id==154 || $cid_id==186 || $cid_id==190 ||
	         	$cid_id==191 || $cid_id==201 ||	$cid_id==227 ||	$cid_id==333 || $cid_id==334 ||
	         	$cid_id==382 ||	$cid_id==414 ||	$cid_id==415 ||	$cid_id==416 || $cid_id==418 ||
	         	$cid_id==419 || $cid_id==420 ||	$cid_id==421 ||	$cid_id==422 ||	$cid_id==423 ||
	         	$cid_id==424 ||	$cid_id==425 || $cid_id==431 || $cid_id==432 ||	$cid_id==436 ||
         		$cid_id==433 ||	$cid_id==437 || $cid_id==440 || $cid_id==442 ||	$cid_id==459 ||
	         	$cid_id==461 || $cid_id==464 || $cid_id==465 || $cid_id==467 || $cid_id==472 ||
	         	$cid_id==473 || $cid_id==474 ||	$cid_id==475 || $cid_id==476 || $cid_id==477 ||
	         	$cid_id==478 ||	$cid_id==479 || $cid_id==480 || $cid_id==481 || $cid_id==482 ||
				$cid_id==483 || $cid_id==484 || $cid_id==485 || $cid_id==486 || $cid_id==487 || 
	         	$cid_id==488 || $cid_id==489 || $cid_id==490 || $cid_id==491 || $cid_id==492 ||
	         	$cid_id==493 || $cid_id==494 || $cid_id==495 || $cid_id==496 || $cid_id==497 || 
	         	$cid_id==498 || $cid_id==499 || $cid_id==500 || $cid_id==501 || $cid_id==502 ||
				$cid_id==536 ||	$cid_id==538 || $cid_id==543 || $cid_id==545 || $cid_id==549 ||
	         	$cid_id==555 ) {
				if ($this->PHPShopNav->getPage()=='') {
	        		/*
	        		$sql="select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and enabled='1' and parent_enabled='0' order by ".$orderby." LIMIT 1,".$this->num_row;
	        		*/
	        		$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,sortorder from (select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,1 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='0' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,2 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,3 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='1') t1 order by ".$orderby_1." LIMIT 1,".$this->num_row;
	        	} else if ($this->PHPShopNav->isPageAll()) {
	        		/*
	        		$sql="select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and enabled='1' and parent_enabled='0' order by ".$orderby;
	        		*/
	        		$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,sortorder from (select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,1 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='0' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,2 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,3 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='1') t1 order by ".$orderby_1;
	        	} else {
	        		$num_row=$this->PHPShopNav->getPage()*$this->num_row;
	        		//echo $num_row.' '.$this->num_row;
	        		$sql_2=" LIMIT ".($num_row-$this->num_row).",".$this->num_row;
	        		/*
	        		$sql="select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and enabled='1' and parent_enabled='0' order by ".$orderby.$sql_2;
	        		*/
	        		$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,sortorder from (select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,1 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='0' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,2 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,3 as sortorder from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='1') t1 order by ".$orderby_1.$sql_2;
	         	}
					        	
			} else {
				if ($this->PHPShopNav->getPage()=='') {
					//select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat from u301639_test.phpshop_products
					$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from ".$GLOBALS['SysValue']['base']['products']." where category in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
					." union all"
					." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby_1." LIMIT 1,".$this->num_row;
					//$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');
				} else if ($this->PHPShopNav->isPageAll()) {
					$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from ".$GLOBALS['SysValue']['base']['products']." where category in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
					." union all"
					." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby_1;
					//$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');
				} else {
					$num_row=$this->PHPShopNav->getPage()*$this->num_row;
					//echo $num_row.' '.$this->num_row;
					$sql_2=" LIMIT ".($num_row-$this->num_row).",".$this->num_row;
					$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from ".$GLOBALS['SysValue']['base']['products']." where category in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
					." union all"
					." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby_1.$sql_2;
					//$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');
				}
			}
			//echo $sql;
			//$sql="select distinct * from u301639_test.phpshop_products";
			//$sql2="(category in (38,39,70,65) or dop_cat LIKE '%#288#%') and enabled='1' and parent_enabled='0' order by COALESCE(sklad,0) = 1 asc, ".$orderby;
			
			//$res=$this->PHPShopOrm->query($sql);
			$PHPShopOrm = new PHPShopOrm($this->getValue('base.categories'));
			$PHPShopOrm->debug = $this->debug;
			$PHPShopOrm->cache = $this->cache;
			$PHPShopOrm->sql=$sql;
			$res=$PHPShopOrm->select();

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
			//echo $db_rows;
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
			//в зависимости от типа каталога строим контент
			if (($cid_id==30 || $cid_id==31 || $cid_id==32 || $cid_id==33 ||
					$cid_id==34 || $cid_id==35 || $cid_id==81 || $cid_id==96 || 
					$cid_id==121 || $cid_id==141 ||
					$cid_id==154 || $cid_id==186 || $cid_id==190 || $cid_id==191 ||
					$cid_id==201 || $cid_id==227 || $cid_id==333 || $cid_id==334 ||
					$cid_id==382 || $cid_id==414 || $cid_id==415 || $cid_id==416 ||
					$cid_id==418 || $cid_id==419 || $cid_id==420 || $cid_id==421 ||
					$cid_id==422 || $cid_id==423 || $cid_id==424 || $cid_id==425 ||
					$cid_id==431 || $cid_id==432 || $cid_id==433 || $cid_id==436 ||
					$cid_id==437 || $cid_id==440 || $cid_id==442 || $cid_id==459 ||
					$cid_id==461 || $cid_id==464 || $cid_id==465 || $cid_id==467 ||
					$cid_id==472 || $cid_id==473 || $cid_id==474 ||	$cid_id==475 ||
					$cid_id==476 || $cid_id==477 ||	$cid_id==478 ||	$cid_id==479 || 
					$cid_id==480 || $cid_id==481 || $cid_id==482 ||	$cid_id==483 || 
					$cid_id==484 || $cid_id==485 || $cid_id==486 || $cid_id==487 || 
					$cid_id==488 || $cid_id==489 || $cid_id==490 || $cid_id==491 || 
					$cid_id==492 ||	$cid_id==493 || $cid_id==494 || $cid_id==495 || 
					$cid_id==496 || $cid_id==497 || $cid_id==498 || $cid_id==499 || 
					$cid_id==500 || $cid_id==501 || $cid_id==502 || $cid_id==536 || 
					$cid_id==538 || $cid_id==543 || $cid_id==545 || $cid_id==549 || 
					$cid_id==555
					)  && $cell==1) {
				//echo 1;
				$disp_cat.='<div class="content"><table cellpadding="0" cellspacing="0" border="0" width="100%" id="manufacturers_products" style="display: table;">';
			} else {
				$disp_cat.='<div class="wrapper"><table cellspacing="0" cellpadding="0" border="0">';
			}
			//сообщение о старой цене
			$comnotice_price_n='';
			//основной цикл построения вывода товаров в категории
			foreach ($res as $prod_row) {			
				//формируем заголовок ссылок 
				if (empty($prod_row['pic_small'])) {
					$prod_row['pic_small']='images/shop/no_photo.gif';
				}
				//в зависимости от статуса товара $prod_row['sklad']=1 (под заказ)
				//комментируем некоторые разделы вывода
				if ($prod_row['sklad']==0) {
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
				
				// Если товар на складе
				if (empty($prod_row['sklad'])) {
				
					// Если нет новой цены
					if (empty($prod_row['price_n'])) {
						if ( ($_COOKIE['sincity']=="sp") AND ($prod_row['price2']!=0) ) {
							$price=$prod_row['price2'];
						} else if( ($_COOKIE['sincity']=="chb") AND ($prod_row['price3']!=0) ) {
							$price=$prod_row['price3'];
						}
						else {
							$price=$prod_row['price'];
						}
				
						$mod_price=strval($price);
				
						//модифицируем вывод цены с пробелом
						$mod_price=$this->add_space_to_price($mod_price);
						$comnotice_price_n='';
						
					}
					// Если есть новая цена
					else {
						
						if ( ($_COOKIE['sincity']=="sp") AND ($prod_row['price2']!=0) ) {
							$price=$prod_row['price2'];
						} else if( ($_COOKIE['sincity']=="chb") AND ($prod_row['price3']!=0) ) {
							$price=$prod_row['price3'];
						}
						else {
							$price=$prod_row['price'];
						}
						$productPriceNew = $this->price($prod_row, true);
						$mod_price=strval($price);
					
						//модифицируем вывод цены с пробелом
						$mod_price=$this->add_space_to_price($mod_price);

						$comnotice_price_n='<div class="prev_price" style="position:relative;left:150px;"><strike>'.$prod_row['price_n'].'</strike></div>';
					}
				}
				// Товар под заказ | снят с производства
				else {
					$this->set('collaboration','lostandfound');
					if ( ($_COOKIE['sincity']=="sp") AND ($prod_row['price2']!=0) ) {
						$price=$prod_row['price2'];
					} else if( ($_COOKIE['sincity']=="chb") AND ($prod_row['price3']!=0) ) {
						$price=$prod_row['price3'];
					}
					else {
						$price=$prod_row['price'];
					}
				
					$mod_price=strval($price);
				
					//модифицируем вывод цены с пробелом
					$mod_price=$this->add_space_to_price($mod_price);
					$comnotice_price_n='';
				}				
				//формируем карточку для stihl
				if ( $cid_id==121 || $cid_id==228 || $cid_id==459 || $cid_id==461 || $cid_id==464 ) {
					// снят с производства+должен быть под заказ
					if ( !(empty($prod_row['sklad'])) && !(empty($prod_row['outdated'])) ) {
						//вывод сообщения об устаревшем товаре
						$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
						$productnotice='';
						//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
						if ($cell>1) {
							$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
						}
						//только если есть аналог выводим ссылку на него					
						if ( !(empty($prod_row['analog'])) ) {
							//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
							if ($cell>1) {
								$sdvig_vlevo='';
								$font_size='13px';
							} else {
								$sdvig_vlevo='left:-20px;';
								$font_size='14px';
							}
							//формирует ссылку на аналогичный товар
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:'.$font_size.' !important;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></div>';
							$productnotice.='<a id="analog_href'.$prod_row['id'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$prod_row['analog'].'.html"></a>';
							$productnotice.='<script type="text/javascript">';
							$productnotice.='$(document).ready(function() {';
							$productnotice.='$("#price_comlain'.$prod_row['id'].'").click( function( event ) {';
							$productnotice.='	window.location =$("#analog_href'.$prod_row['id'].'").attr("href");';
							$productnotice.='	return false;';
							$productnotice.='});});';
							$productnotice.='</script>';
						} else {
							//если нет аналога то выводим пустышку
							$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
							$productnotice='';
							//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
							if ($cell>1) {
								$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
							}
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;	border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
						}
						$comnotice='';
						$outdated_style='style="display:relative;margin:-32px 15px 0px 0px;"';
					} else {
						//товар со статусом в наличие/под заказ
						$comnotice_30='';
						$addtochart='<input type="button" onclick="stihl_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						$productnotice='<input type="button" onclick="stihl_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						//если товар под заказ
						if (!(empty($prod_row['sklad']))) {
							//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
							if ($cell>1) {
								$sdvig_vpravo='left:20px;';
								$comnotice='<div class="prev_price" style="position:relative;font-size:11px !important;'.$sdvig_vpravo.'">'.$this->lang('sklad_mesage').'</div>';
							} else {
								$sdvig_vpravo='';
								$comnotice=$this->lang('sklad_mesage');
							}
						} else {
							$comnotice='';
						}
						$outdated_style='';
					}
				//формируем карточку для viking
				} else if ( $cid_id==295 || $cid_id==467 ) {
					// снят с производства+должен быть под заказ
					if (!(empty($prod_row['sklad'])) && !(empty($prod_row['outdated']))) {
						//вывод сообщения об устаревшем товаре
						$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
						$productnotice='';
						//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
						if ($cell>1) {
							$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
						}
						//только если есть аналог выводим ссылку на него
						if ( !(empty($prod_row['analog'])) ) {
							//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
							if ($cell>1) {
								$sdvig_vlevo='';
								$font_size='13px';
							} else {
								$sdvig_vlevo='left:-20px;';
								$font_size='14px';
							}							
							//формирует ссылку на аналогичный товар
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:'.$font_size.' !important;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></div>';
							$productnotice.='<a id="analog_href'.$prod_row['id'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$prod_row['analog'].'.html"></a>';
							$productnotice.='<script type="text/javascript">';
							$productnotice.='$(document).ready(function() {';
							$productnotice.='$("#price_comlain'.$prod_row['id'].'").click( function( event ) {';
							$productnotice.='	window.location =$("#analog_href'.$prod_row['id'].'").attr("href");';
							$productnotice.='	return false;';
							$productnotice.='});});';
							$productnotice.='</script>';
						} else {
							//если нет аналога то выводим пустышку
							$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
							$productnotice='';
							//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
							if ($cell>1) {
								$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
							}
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;	border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
						}						
						$comnotice='';
						$outdated_style='style="display:relative;margin:-32px 15px 0px 0px;"';
					} else {
						//товар со статусом в наличие/под заказ
						$comnotice_30='';
						$addtochart='<input type="button" onclick="viking_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						$productnotice='<input type="button" onclick="viking_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						//если товар под заказ
						if (!(empty($prod_row['sklad']))) {
							//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
							if ($cell>1) {
								$sdvig_vpravo='left:20px;';
								$comnotice='<div class="prev_price" style="position:relative;font-size:11px !important;'.$sdvig_vpravo.'">'.$this->lang('sklad_mesage').'</div>';
							} else {
								$sdvig_vpravo='';
								$comnotice=$this->lang('sklad_mesage');
							}
						} else {
							$comnotice='';
						}
						$outdated_style='';
					}
				} else {
					// снят с производства+должен быть под заказ, для модифицированных категорий
					if (!(empty($prod_row['sklad'])) && !(empty($prod_row['outdated']))) {
						if ($cid_id==30 || $cid_id==31 || $cid_id==32 || $cid_id==33 ||
							$cid_id==34 || $cid_id==35 || $cid_id==81 || $cid_id==96 || 
							$cid_id==121 || $cid_id==141 ||
							$cid_id==154 || $cid_id==186 || $cid_id==190 || $cid_id==191 ||
							$cid_id==201 || $cid_id==227 || $cid_id==333 || $cid_id==334 ||
							$cid_id==382 || $cid_id==414 || $cid_id==415 || $cid_id==416 ||
							$cid_id==418 || $cid_id==419 || $cid_id==420 || $cid_id==421 ||
							$cid_id==422 || $cid_id==423 || $cid_id==424 || $cid_id==425 ||
							$cid_id==431 || $cid_id==432 || $cid_id==433 || $cid_id==436 ||
							$cid_id==437 || $cid_id==440 || $cid_id==442 || $cid_id==465 ||
							$cid_id==472 || $cid_id==473 || $cid_id==474 ||	$cid_id==475 || 
							$cid_id==476 || $cid_id==477 ||	$cid_id==478 ||	$cid_id==479 ||
							$cid_id==480 || $cid_id==481 || $cid_id==482 ||	$cid_id==483 ||
							$cid_id==484 || $cid_id==485 || $cid_id==486 || $cid_id==487 || 
	         				$cid_id==488 || $cid_id==489 || $cid_id==490 || $cid_id==491 || 
							$cid_id==492 ||	$cid_id==493 || $cid_id==494 || $cid_id==495 || 
							$cid_id==496 || $cid_id==497 || $cid_id==498 || $cid_id==499 || 
							$cid_id==500 || $cid_id==501 || $cid_id==502 || $cid_id==536 || 
							$cid_id==538 || $cid_id==543 || $cid_id==545 || $cid_id==549 || 
							$cid_id==555
						) {
							$comnotice='';
							$productnotice='';
							$comnotice_30=$this->lang('outdated_message');//$this->lang('sklad_mesage');
							if ($cell>1) {
								$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
							}
							//снят с производства+должен быть под заказ, для всех остальных категорий
						} else {
							$comnotice='';
							$productnotice_0='<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$this->lang('outdated_message').'</noindex></div>';
							$productnotice=$productnotice_0;
						}
						//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
						if ($cell>1) {
							$sdvig_vlevo='';
						} else {
							$sdvig_vlevo='left:-20px;';
						}
						//вывод ссылки на аналогичный товар
						if ( !(empty($prod_row['analog'])) ) {
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></div>';
							$productnotice.='<a id="analog_href'.$prod_row['id'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$prod_row['analog'].'.html"></a>';
							$productnotice.='<script type="text/javascript">';
							$productnotice.='$(document).ready(function() {';
							$productnotice.='$("#price_comlain'.$prod_row['id'].'").click( function( event ) {';
							$productnotice.='	window.location =$("#analog_href'.$prod_row['id'].'").attr("href");';
							$productnotice.='	return false;';
							$productnotice.='});});';
							$productnotice.='</script>';
						} else {
							//вывод пустышки если нет ссылки на аналог
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;	border-bottom:1px;'.$sdvig_vlevo.'"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
						}
						$comnotice='';
						$outdated_style='style="display:relative;margin:-32px 15px 0px 0px;"';
						$comnotice_price_n='';
						//вывод статуса под заказ
					} else if (!(empty($prod_row['sklad'])) && (empty($prod_row['outdated']))) {
						$comnotice_30='';//$this->lang('sklad_mesage');
						//$productnotice='<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'" />';
						$productnotice='<input type="button" onclick="ask_product_availability(\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'));" value="'.$this->lang('product_notice').'">';
						//используем отдельное форматирование для вывода в каталогах с кол-м ячеек больше 1
						if ($cell>1) {
							$sdvig_vpravo='left:20px;';
							$comnotice='<div class="prev_price" style="position:relative;font-size:11px !important;'.$sdvig_vpravo.'">'.$this->lang('sklad_mesage').'</div>';
						} else {
							$sdvig_vpravo='';
							$comnotice=$this->lang('sklad_mesage');
						}
						$outdated_style='';
						$comnotice_price_n='';
					} else {
						//вывод товара со статусом доступен для покупки
						$addtochart='<input type="button" onclick="javascript:AddToCart('.$prod_row['id'].')"  value="'.$this->lang('product_sale').'" />';
						//$productnotice='<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'" />';
						$productnotice='<input type="button" onclick="ask_product_availability(\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'));" value="'.$this->lang('product_notice').'">';
						$comnotice='<div class="prev_price" style="font-size:11px !important;">'.$this->lang('sklad_mesage').'</div>';
						$outdated_style='';
						$comnotice_30='';
						if (!empty($prod_row['price_n'])){
							$comnotice_price_n='<div class="prev_price" style="position:relative;left:150px;"><strike>'.$prod_row['price_n'].'</strike></div>';
						}
						$comnotice='';
					}
				}
				//вариант вывода карточки товара с одной ячейкой для модифицированных категорий
				if (($cid_id==30 || $cid_id==31 || $cid_id==32 || $cid_id==33 ||
					$cid_id==34 || $cid_id==35 || $cid_id==81 || $cid_id==96 || 
					$cid_id==121 || $cid_id==141 ||
					$cid_id==154 || $cid_id==186 || $cid_id==190 || $cid_id==191 ||
					$cid_id==201 || $cid_id==227 || $cid_id==333 || $cid_id==334 ||
					$cid_id==382 || $cid_id==414 || $cid_id==415 || $cid_id==416 ||
					$cid_id==418 || $cid_id==419 || $cid_id==420 || $cid_id==421 ||
					$cid_id==422 || $cid_id==423 || $cid_id==424 || $cid_id==425 ||
					$cid_id==431 || $cid_id==432 || $cid_id==433 || $cid_id==436 ||
					$cid_id==437 || $cid_id==440 || $cid_id==442 || $cid_id==459 ||
					$cid_id==461 || $cid_id==464 || $cid_id==465 || $cid_id==467 ||
					$cid_id==472 || $cid_id==473 || $cid_id==474 ||	$cid_id==475 || 
					$cid_id==476 || $cid_id==477 ||	$cid_id==478 ||	$cid_id==479 ||
					$cid_id==480 || $cid_id==481 || $cid_id==482 ||	$cid_id==483 ||
					$cid_id==484 || $cid_id==485 || $cid_id==486 || $cid_id==487 || 
	         		$cid_id==488 || $cid_id==489 || $cid_id==490 || $cid_id==491 || 
					$cid_id==492 ||	$cid_id==493 || $cid_id==494 || $cid_id==495 || 
					$cid_id==496 || $cid_id==497 || $cid_id==498 || $cid_id==499 || 
					$cid_id==500 || $cid_id==501 || $cid_id==502 || $cid_id==536 || 
					$cid_id==538 || $cid_id==543 || $cid_id==545 || $cid_id==549 ||
					$cid_id==555
					) && $cell==1) {
					//echo 2;
					$this->catalog_product_icons($prod_row);
					$this->sort_table($prod_row);
					//echo $this->get('Producticons');
					$disp_cat.='<tr>'
					.'<td class="panel_l panel_1_1" valign="top"><div id="_tool_" class="tool_'.$prod_row['id'].'">'
					.'<div class="tovar1"> <div class="item1">'
					.'<span class="popular-"></span>'
					.'<div class="thumb">'
					.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
					.'<tbody><tr>'
					.'<td height="160" align="top"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="/phpshop/templates/prodacha/images/shop/no_photo.gif" onerror="NoFoto(this,\'/phpshop/templates/prodacha\')" onload="EditFoto(this,)" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'
					.'</tr>'
					.'</tbody></table>'
					.'</div>'
					.'<div class="descr_wrapper"><a href="/shop/UID_'.$prod_row['id'].'.html"><span class="description">'.$prod_row['name'].'</span></a>'
					.$this->get('Producticons')
					.'<span class="prev_price"><noindex>'.$comnotice.$comnotice_30.'</noindex></span> <span class="price">'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>'					
					.'<div class="buybuttons">'
					.$ComStartCart.'<span class="addtochart">'
					.$addtochart
					.'</span>'.$ComEndCart	
					.$ComStartNotice.'<span class="addtochart">'
					.$productnotice
					.'</span>'.$ComEndNotice									
					.'<!--@firstcreditpunch@-->'
					.'</div>'
					.'</div>'
					.'<div class="parameters">'
					.'<table  width="100%" border="0" cellspacing="0" cellpadding="0">'
					.'<tr>'
					.'<td height="160" valign=top ><div style="overflow:hidden; height:150px; padding-right:20px;">'.$this->get('vendorDisp').'</div></td>'
					.'</tr>'
					.'</table>'
					//.$this->get('vendorDisp')
					.'</div> </div>'
					.'</div>'
					.'</td>'
					.'</tr>'
					.'<tr><td class="setka" colspan="2" height="1"><img height="1" src="images/spacer.gif"></td>'
					.'</tr>';
					// Добавляем в дизайн ячейки с товарами
					//$grid = $this->product_grid($prod_row, 1);
					//if (empty($grid))
					//	$grid = PHPShopText::h2($this->lang('empty_product_list'));
					//$this->add($grid, true);
				} else {
					if ($cell==1 || $cell==2 || $cell==3 || $cell==4) {
						//echo $this->lang('outdated_message');
						if ($cnt == 1) {
							$disp_cat.='<tr><td class="panel_l panel_3_1"><div class="tovar" style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';">';
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
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<span class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice.$comnotice_price_n
							.'</div>'
							.'</div>'
							.'</div>'
							.'</td><td class="setka"> <img width="1" src="images/spacer.gif"></td>';
							if ($cell==1) {
								$cnt=0;
								$disp_cat.='</tr>';
							}
						}
					}
					if ($cell==2 || $cell==3 || $cell==4) {
						if  ($cnt == 2) {
							$disp_cat.='<td class="panel_r panel_3_2"><div class="tovar" style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"><div class="item">'
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
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<span class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice.$comnotice_price_n
							.'</div>'
							.'</div>'
							.'</div>'
							.'</td><td class="setka"> <img width="1" src="images/spacer.gif"></td>';
							if ($cell==2) {
								$cnt=0;
								$disp_cat.='</tr>';
							}
						}
					}
					if ($cell==3 || $cell==4) {
						if  ($cnt == 3) {
							$disp_cat.='<td class="panel_l panel_3_2"><div class="tovar" style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"><div class="item">'
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
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<span class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice.$comnotice_price_n
							.'</div>'
							.'</div>'
							.'</div>'
							.'</td><td class="setka"> <img width="1" src="images/spacer.gif"></td>';
							if ($cell==3) {
								$cnt=0;
								$disp_cat.='</tr>';
							}
						}
					}
					if ($cell==4) {
						if  ($cnt == 4) {
							$disp_cat.='<td class="panel_l panel_3_3"><div class="tovar"  style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;" onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"><div class="item">'
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
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<span class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice.$comnotice_price_n
							.'</div>'
							.'</div>'
							.'</div>'
							.'</td><td class="setka"> <img width="1" src="images/spacer.gif"></td>';
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
			}
			$disp_cat.='</table></div>';
			//echo $disp_cat;
			$this->set('catalogoutput',$disp_cat);
		
		}
	}

}

?>