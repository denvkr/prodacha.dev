<?php

/**
 * Обработчик спецпредложений товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSpec
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopSpec extends PHPShopShopCore {

    var $debug = false;
    var $cache = true;
    var $cache_format = array('content', 'yml_bid_array');
    var $cell=1;
    var $where;
    var $count;
    var $line = false;
    /**
     * Конструктор
     */
    function PHPShopSpec() {

        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    /**
     * Вывод списка товаров
     */
    function index() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Путь для навигации
        $this->objPath = './spec_';

        // Валюта
        $this->set('productValutaName', $this->currency());

        $this->set('catalogCategory', $this->lang('specprod'));

        // Количество ячеек
        if (empty($this->cell))
            $this->cell = $this->PHPShopSystem->getValue('num_vitrina');
        $this->where=array('spec' => "='1'", 'enabled' => "='1'");//"spec='1'";
        // Фильтр сортировки
        $order = $this->query_filter("spec='1'");

        // Простой запрос
        if (is_array($order)) {
            $this->dataArray = parent::getListInfoItem(array('*'), $this->where, $order, __CLASS__, __FUNCTION__);
        } else {
            // Сложный запрос
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
        }

        // Пагинатор
        if (is_array($order))
            $this->setPaginator(count($this->dataArray));
        //print_r($this->dataArray);
        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid_spec($this->dataArray, $this->cell,false,false);
        
        if (empty($grid)) {
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        }
        $this->add($grid, true);

        // Заголовок
        $this->title = $this->lang('specprod') . " - " . $this->PHPShopSystem->getParam('title');

        //$this->set('catalogContent','test',true);
        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_page_spec_list'));
    }
    /**
     * Генератор сетки товаров
     * @param array $dataArray массив данных
     * @param int $cell разряд сетки [1-5]
     * @return string
     */
    function product_grid_spec($dataArray, $cell = 2, $template=false,$line=false) {
    
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
    			//if ($template!==false)
    			//	$this->catalog_product_icons($row);//"icon_".$row['id'];
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
    			$this->parseTemplate($template,false);
                        //echo $this->Disp;
    			//$this->ParseTemplateReturn($template);
    			//echo $template;
    			// Убераем последний разделитель в сетке
    			if ($item == $total)
    				$this->setka_footer = false;
    
    			$cell_name = 'd' . $j;
    			$$cell_name = $this->Disp;
                        //echo $j.' '.$this->cell.' '.$item.' '.$total;
    			if ($j == $this->cell) {
    				$table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    				$d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
    				$j = 0;
    			} elseif ($item == $total) {
    				$table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    			}
    
    			$j++;
    			$item++;
                        //echo $table;

    		}
    	}
    
    	//$this->lastmodified = $lastmodified;

    	return $table;
    }
	function add_space_to_price($mod_price) {
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
			case 7:
				$mod_price=substr($mod_price,0,1).' '.substr($mod_price,1,strlen($mod_price)-1);
				break;				
		}
                $hook=$this->setHook(__CLASS__, __FUNCTION__, $mod_price);                
		return $mod_price;
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

}

?>