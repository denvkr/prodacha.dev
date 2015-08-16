<?php

/**
 * Обработчик поиска товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSearch 
 * @version 1.3
 * @package PHPShopShopCore
 */
class PHPShopSearch extends PHPShopShopCore {

    /**
     * сетка товаров
     * @var int 
     */
    var $cell = 1;
    var $line = false;
    var $debug = false;
    var $cache = false;
    var $grid = false;
    var $empty_index_action = false;
    
    function PHPShopSearch() {

        // Список экшенов
        $this->action = array("post" => "words", "get" => "words", "nav" => "index");
    	parent::PHPShopShopCore();
    }

    /**
     * Экшен по умолчанию, вывод формы поиска
     */
    function index() {

        $this->category_select();

        $this->set('searchSetA', 'checked');
        $this->set('searchSetC', 'checked');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__);

        if (isset($_REQUEST['ajax']))
        	exit();
        
        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    /**
     * Генерация SQL запроса со сложными фильтрами и условиями
     * Функция вынесена в отдельный файл query_filter.php
     * @return mixed
     */
    function query_filter($where = false) {

        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Составление реверсивного списка подкаталогов
     * @param int $cat ИД каталога
     * @param string $parent_name цепочка имен родителей
     * @return bool
     */
    function subcategory($cat, $parent_name = false) {
        if (!empty($this->ParentArray[$cat]) and is_array($this->ParentArray[$cat])) {
            foreach ($this->ParentArray[$cat] as $val) {

                $name = $this->PHPShopCategoryArray->getParam($val . '.name');
                $sup = $this->subcategory($val, $parent_name . ' / ' . $name);
                if (empty($sup)) {

                    // Запоминаем параметр каталога
                    if ($_REQUEST['cat'] == $val)
                        $sel = 'selected';
                    else
                        $sel = false;

                    $this->value[] = array($parent_name . ' / ' . $name, $val, $sel);
                }
            }
            return true;
        }
        else {
            //Запоминаем параметр каталога
            if (!empty($_REQUEST['cat']) and $_REQUEST['cat'] == $cat)
                $sel = 'selected';
            else
                $sel = false;

            if(!$this->errorMultibase($cat))
            $this->value[] = array($parent_name, $cat, $sel);
            
            return true;
        }
    }

    /**
     * Вывод категорий для поиска
     */
    function category_select() {

            $this->value[] = array(__('Все разделы'), 0, false);
            $this->PHPShopCategoryArray = new PHPShopCategoryArray();
            $this->ParentArray = $this->PHPShopCategoryArray->getKey('parent_to.id', true);
            if (is_array($this->ParentArray[0])) {
                foreach ($this->ParentArray[0] as $val) {
                    if ($this->PHPShopCategoryArray->getParam($val . '.vid') != 1 and !$this->errorMultibase($val)) {
                        $name = $this->PHPShopCategoryArray->getParam($val . '.name');
                        $this->subcategory($val, $name);
                    }
                }
            }

            $disp = PHPShopText::select('cat', $this->value, '400', $float = "none", false, "proSearch(this.value)", false, 1, 'cat');
            $this->set('searchPageCategory', $disp);
        

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->ParentArray);
    }

    /**
     *  Вывод фильтров
     */
    function sort_select() {
        if (PHPShopSecurity::true_param(@$_REQUEST['v'], @$_REQUEST['cat']))
            if (is_array($_REQUEST['v'])) {
                PHPShopObj::loadClass('sort');
                if (PHPShopSecurity::true_num($_REQUEST['cat'])) {
                    $PHPShopSort = new PHPShopSort($_REQUEST['cat']);
                    $this->set('searchPageSort', $PHPShopSort->display());

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $PHPShopSort);
                }
            }
    }

    /**
     * Экшен поиска по запросу
     */
    function words() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, $_REQUEST, 'START'))
            return true;

        // Валюта
        $this->set('productValutaName', $this->currency());

        // Категория поиска
        $this->category_select();

        // Фильтры поиска
        $this->sort_select();

        // Русские буквы в Ajax запросе
        if (isset($_REQUEST['ajax']))
        	$_REQUEST['words'] =  iconv('utf-8//IGNORE//TRANSLIT','cp1251', urldecode($_REQUEST['words']));
        //echo $_REQUEST['words'];
        // Фильтр поиска
        $_REQUEST['words'] = PHPShopSecurity::true_search($_REQUEST['words']);

        if (!empty($_REQUEST['words'])) {

        	// Ajax Search
        	if (isset($_REQUEST['ajax'])) {
        		$this->cell = 1;
        		$this->num_row = 3;
        		$template = 'search/search_ajax_product_forma.tpl';
        	
        		if (!empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'])) {
        			$seourlpro = true;
        		}
        	}
        	else
        		$template = false;

            $order = $this->query_filter();
            //$file="base_".date("d_m_y_His")."query_filtr.txt";
            //$fp = fopen($file, "w");
            //if ($fp) {
            //fwrite($fp,('1'.$order));
            //}
            //fclose($fp);            
			//echo $order;
            $order=str_replace('LIMIT 0,9','LIMIT 0,5',$order);

            //$order=preg_replace('~\) and \((?!.*\) and \()~', ') or (', $order);
            //echo $order;
            // Сложный запрос
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->debug=false;
            $this->PHPShopOrm->mysql_error = false;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
			//print_r($this->dataArray);
            if (!empty($this->dataArray)) {

                // Пагинатор
                $this->setPaginator(count($this->dataArray), $order);

                // Ajax Search

                if (isset($_REQUEST['ajax'])) {
	               	foreach ($this->dataArray as $row) {
	               		// Название
	            		$this->set('productName', $row['name']);
	                
	               		// Артикул
	               		$this->set('productArt', $row['uid']);
	                
	               		// Краткое описание
	               		$this->set('productDes', $row['description']);
	                
	               		// Вес
	               		$this->set('productWeight', $row['weight']);
	             
	               		// Максимальная дата изменения
	               		if ($row['datas'] > $lastmodified)
	                			$lastmodified = $row['datas'];
	                
	                		// Маленькая картинка
	                		$this->set('productImg', $this->checkMultibase($row['pic_small']));
	                
	                		// Пустая картинка, заглушка
	                		if (empty($row['pic_small']))
	                			$this->set('productImg', $this->no_photo);
	                
	                		// Большая картинка
	                		$this->set('productImgBigFoto', $this->checkMultibase($row['pic_big']));
	                
	                		// Ид товара
	                		$this->set('productUid', $row['id']);

	                		if (empty($row['pic_small'])) $row['pic_small']='/'.$GLOBALS['SysValue']['other']['catalogTemplates'].$this->no_photo;
	                		 
	                		$disp.='<div class="news-list">'.
	                		'<a href="/shop/UID_'.$row['id'].'.html">'.
	                		'<div class="template-pad media">'.
	                		'<div class="media-left">'.
	                		'<img src="'.$this->checkMultibase($row['pic_small']).'" alt="'.$row['name'].'" width="64" class="media-object img-rounded" />'.
	                		'</div>'.
	                		'<div class="media-body">'.
	                		'<h4 class="media-heading" style="font: normal 12px/1.4 Arial, Helvetica, sans-serif;">'.$row['name'].'</h4>'.
	                		'<span class="btn btn-default">'.$row['price'].' '.$this->PHPShopSystem->getDefaultValutaCode(true).'</span>'.
	                		'</div>'.
	                		'</div>'.
	                		'</a>'.
	                		'</div>'; //class="img-responsive"
	                }
	                echo $disp;
                } else {
                	// Добавляем в дизайн ячейки с товарами
                	$grid=$this->product_grid($this->dataArray, $this->cell, $template=false, $this->line);
                	$this->add($grid, true);
                }
	                
            }
            else {
            	if (isset($_REQUEST['ajax'])) {
            		//echo 'false';
            		exit();
            	}
            	 
                $this->add(PHPShopText::h3(__('Ничего не найдено')), true);
            }
            // Запись в журнал
            $this->write($this->get('searchString'), @$this->num_page, @$_REQUEST['cat'], @$_REQUEST['set']);

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');
        }
        if (isset($_REQUEST['ajax'])){
        	//echo 'false';
        	exit();
        }
        
        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    /**
     * Запись в журнал поиска
     */
    function write($name, $num, $cat, $set) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name18'));
        $PHPShopOrm->debug = $this->debug;

        // Перехват модуля
        $arg = func_get_args();
        $this->PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $arg);

        $PHPShopOrm->insert(array('name_new' => $name, 'num_new' => $num, 'datas_new' => time(), 'cat_new' => $cat, 'dir_new' => $_SERVER['HTTP_REFERER']));
    }

    /**
     * Генерация пагинатора
     */
    function setPaginator($count) {

        // Кол-во данных
        $this->count = $count;

        if (is_array($this->search_order)) {
            $SQL = " where enabled='1' and parent_enabled='0' and " . $this->search_order['string'] . " " . $this->search_order['sort'] . "
                 " . $this->search_order['prewords'] . " " . $this->search_order['sortV'];
        }else
            $SQL = null;


        // Всего страниц
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . $SQL);
        $row = mysql_fetch_array($result);
        $this->num_page = $row['count'];

        $i = 1;
        $navigat = null;
        $num = round(($this->num_page / $this->num_row) + 0.4);
        if (empty($_GET['p']))
            $_GET['p'] = 1;
        $this->page = $_GET['p'];

        if ($num > 1) {
            if ($this->page >= $num) {
                $p_to = $i - 1;
                $p_do = $this->page - 1;
            } else {
                $p_to = $this->page + 1;
                $p_do = 1;
            }

            while ($i <= $num) {

                if ($i > 1) {
                    $p_start = $this->num_row * ($i - 1);
                    $p_end = $p_start + $this->num_row;
                } else {
                    $p_start = $i;
                    $p_end = $this->num_row;
                }
                if ($i != $this->page) {
                    $navigat.=PHPShopText::a("./?words=" . $this->search_order['words'] . "&pole=" .
                                    $this->search_order['pole'] . "&set=" . $this->search_order['set'] . "&p=" . $i . "&cat=" . $this->search_order['cat'], $p_start . '-' . $p_end) . ' / ';
                }
                else
                    $navigat.=PHPShopText::b($p_start . '-' . $p_end . ' / ');
                $i++;
            }


            $nav = $this->getValue('lang.page_now') . ': ';
            $nav.=PHPShopText::a("./?words=" . $this->search_order['words'] . "&pole=" .
                            $this->search_order['pole'] . "&set=" . $this->search_order['set'] . "&p=" . $p_do . "&cat=" . $this->search_order['cat'], PHPShopText::img('images/shop/3.gif', 1, 'absmiddle'), $this->lang('nav_back'));
            $nav.=$navigat;
            $nav.=PHPShopText::a("./?words=" . $this->search_order['words'] . "&pole=" .
                            $this->search_order['pole'] . "&set=" . $this->search_order['set'] . "&p=" . $p_to . "&cat=" . $this->search_order['cat'], PHPShopText::img('images/shop/4.gif', 1, 'absmiddle'), $this->lang('nav_forw'));

            $this->set('searchPageNav', $nav);

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $nav);
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

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    }

    /**
     * Генератор сетки товаров
     * @param array $dataArray массив данных
     * @param int $cell разряд сетки [1-5]
     * @return string
     */
    function product_grid_search($dataArray, $cell = 2, $template=false) {
    
    	if (empty($cell))
    		$cell = 2;
    	$this->cell = $cell;
    	$this->setka_footer = true;
    
    	$table = null;
    	$j = 1;
    	$item = 1;
    	$lastmodified = 0;
    
    	// Локализация
    	$this->set('productSale', $this->lang('product_sale'));
    	$this->set('productInfo', $this->lang('product_info'));
    	$this->set('productPriceMoney', $this->dengi);
    	$this->set('catalog', $this->lang('catalog'));
    	$this->set('productPageThis', $this->PHPShopNav->getPage());
    
    	$d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
    	if (is_array($dataArray)) {
    		$total = count($dataArray);
    		// Проверка разделителя сетки
    		//if ($total < $cell)
    			//$this->grid = false;
    		foreach ($dataArray as $row) {
    
    			// Название
    			$this->set('productName', $row['name']);
    
    			// Артикул
    			$this->set('productArt', $row['uid']);
    
    			// Краткое описание
    			$this->set('productDes', $row['description']);
    
    			// Вес
    			$this->set('productWeight', $row['weight']);
    
    
    			// Максимальная дата изменения
    			if ($row['datas'] > $lastmodified)
    				$lastmodified = $row['datas'];
    
    			// Маленькая картинка
    			$this->set('productImg', $this->checkMultibase($row['pic_small']));
    
    			// Пустая картинка, заглушка
    			if (empty($row['pic_small']))
    				$this->set('productImg', $this->no_photo);
    
    			// Большая картинка
    			$this->set('productImgBigFoto', $this->checkMultibase($row['pic_big']));
    
    			// Ид товара
    			$this->set('productUid', $row['id']);
    
    			//~nah
    			if ($template!==false)
    				$this->catalog_product_icons($row);//"icon_".$row['id'];
    			//echo $uid;
    			//$this->set('Producticons',$uid);
    
    			/*
    				if($row['sklad']!=1)
    				{
    			$fp=round($row['price']/10);
    
    			$firstcreditpunch='
    			<span class="addtochart buyincredit" id="credit_'.$row['id'].'">
    			<input class="creditinput" rel="'.$row['id'].'" type="button" value="В кредит" >
    			<span class="firstcreditpunch"> '.$fp.' руб. первый взнос</span>';
    
    			$this->set('firstcreditpunch',$firstcreditpunch);
    
    			}
    			else	  $this->set('firstcreditpunch','');
    			*/
    			// Опции склада
    			$this->checkStore($row);
    			//echo time().'<br/>';
    			// Опции товара
    			//$this->option_select($row);
    			// Перехват модуля
    			//$this->setHook(__CLASS__, __FUNCTION__, $row);
    			$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    
    			if(empty($template))
    				$template=$this->getValue('templates.main_product_forma_' . $this->cell);
    
    			// Подключаем шаблон ячейки товара
    			$dis = $this->ParseTemplateReturn($template);
    			//$this->ParseTemplateReturn($template);
    			//echo $template;
    			// Убераем последний разделитель в сетке
    			if ($item == $total)
    				$this->setka_footer = false;
    
    			//$cell_name = 'd' . $j;
    			//$$cell_name = $dis;
    /*
    			if ($j == $this->cell) {
    				$table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    				$d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
    				$j = 0;
    			} elseif ($item == $total) {
    				$table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    			}
    
    			$j++;
    			$item++;
    */
    		}
    	}
    
    	//$this->lastmodified = $lastmodified;
    	return $dis;
    }
    
}

?>