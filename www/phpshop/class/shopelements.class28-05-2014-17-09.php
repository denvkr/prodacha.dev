<?php

/**
 * Класс создания элементов товаров
 * Примеры использования размещены в папке phpshop/inc/
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductElements
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopProductElements extends PHPShopElements {

    /**
     * @var bool кэширование
     */
    var $cache = false;

    /**
     * @var array чистка элементов кэша
     */
    var $cache_format = array('content');

    /**
     * @var bool разделитель между товарами
     */
    var $grid = false;

    /**
     * @var bool запоминание параметов выполнения функций и модулей
     * При проектировании модулей и хуков следует отключить память [false]
     */
    var $memory = false;

    /**
     * Имя изображения заглушки
     * @var string 
     */
    var $no_photo = 'images/shop/no_photo.gif';
    var $total = 0;
    var $product_grid;

    /**
     * Конструктор
     */
    function PHPShopProductElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];

        // Библиотека поддержки товаров
        PHPShopObj::loadClass('product');
        parent::PHPShopElements();

        // Валюта товара
        $this->dengi = $this->PHPShopSystem->getParam('dengi');
    }

    /**
     * Валюта
     * @return string
     */
    function currency($name = 'code') {

        if (isset($_SESSION['valuta']))
            $currency = $_SESSION['valuta'];
        else
            $currency = $this->dengi;

        $row = $this->select(array($name), array('id' => '=' . intval($currency)), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.currency'), 'cache' => 'true'));

        return $row[$name];
    }

    /**
     * Форма заготовка
     */
    function seamply() {

        // Количество ячеек для вывода товара
        $cell = 2;

        // Кол-во товаров на странице
        $limit = 4;

        $this->dataArray = $this->select(array('*'), array('enabled' => "='1'"), array('order' => 'RAND()'), array('limit' => $limit), __FUNCTION__);

        // Добавляем в дизайн ячейки с товарами
        $this->product_grid($this->dataArray, $cell);

        // Собираем и возвращаем таблицу с товарами
        $this->compile();
    }

    /**
     * Расчет случайного вывода товаров
     * @param int $limit кол-во товаров для вывода
     * @return array
     */
    function setramdom($limit) {

        // Если не сохранено в кэше
        if (empty($_SESSION['max_item'])) {
            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->cache = false;
            $PHPShopOrm->sql = 'SELECT MAX(id) as max_item FROM ' . $GLOBALS['SysValue']['base']['products'];
            $data = $PHPShopOrm->select();

            if (is_array($data[0]))
                $this->max_item = $data[0]['max_item'];
            else
                $this->max_item = 0;

            // Добавляем в кэш общее кол-во товаров
            $_SESSION['max_item'] = $this->max_item;
        }
        else
            $this->max_item = $_SESSION['max_item'];

        $limit_start = rand(1, $this->max_item / rand(1, 7));
        return ' BETWEEN ' . $limit_start . ' and ' . round($limit_start + $limit + $this->max_item / 3);
    }

    /**
     * Выборка из БД
     */
    function select($select, $where, $order = false, $option = array('limit' => 1), $function_name = false, $from = false) {

        $cache_format = null;

        if (is_array($from)) {
            $base = $from['base'];
            $cache = $from['cache'];

            if (!empty($from['cache_format']))
                $cache_format = $from['cache_format'];
        }
        else {
            $base = $this->objBase;
            $cache = $this->cache;
            $cache_format = $this->cache_format;
        }

        $PHPShopOrm = new PHPShopOrm($base);
        $PHPShopOrm->objBase = $base;
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $cache;
        $PHPShopOrm->cache_format = $cache_format;
        $result = $PHPShopOrm->select($select, $where, $order, $option, __CLASS__, $function_name);

        return $result;
    }

    /**
     * Сбор данных по товарам в таблицу
     * @return string
     */
    function compile() {
        $table = '<table cellpadding="0" cellspacing="0" border="0">' . $this->product_grid . '</table>';
        $this->product_grid = null;
        return $table;
    }

    /**
     * Проверка режима Multibase
     */
    function checkMultibase($pic_small) {

        $base_host = $this->PHPShopSystem->getSerilizeParam('admoption.base_host');
        if ($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host))
            $this->set('productImg', eregi_replace("/UserFiles/", "http://" . $base_host . "/UserFiles/", $pic_small));
    }

    /**
     * Проверка дополнительных данных товара по складу
     * @param array $row масив данных по товару
     */
    function checkStore($row) {

        // Показывать состояние склада
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1 and $row['items'] > 0)
            $this->set('productSklad', $this->lang('product_on_sklad') . " " . $row['items'] . " " . $this->lang('product_on_sklad_i'));
        else
            $this->set('productSklad', '');

        // Если товар на складе
        if (empty($row['sklad'])) {

            $this->set('Notice', '');
            $this->set('ComStartCart', '');
            $this->set('ComEndCart', '');
            $this->set('ComStartNotice', '<!--');
            $this->set('ComEndNotice', '-->');

            // Если нет новой цены
            if (empty($row['price_n'])) {
            	$mod_price=strval($this->price($row));
            	
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
            	
            	$this->set('productPrice',$mod_price);            	
                //$this->set('productPrice', $this->price($row));
                $this->set('productPriceRub', '');
            }

            // Если есть новая цена
            else {
                $productPrice = $this->price($row);
                $productPriceNew = $this->price($row, true);
                $mod_price=strval($productPrice);
                
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
                
                $this->set('productPrice',$mod_price);                
                //$this->set('productPrice', $productPrice);
                $this->set('productPriceRub', PHPShopText::strike($productPriceNew));
            }
        }

        // Товар под заказ
        else {
        	$mod_price=strval($this->price($row));
        	
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
        	
        	$this->set('productPrice',$mod_price);        	
            //$this->set('productPrice', $this->price($row));
            $this->set('productPriceRub', $this->lang('sklad_mesage'));
            $this->set('ComStartNotice', '');
            $this->set('ComEndNotice', '');
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productNotice', $this->lang('product_notice'));
        }

        // Если цены показывать только после аторизации
        if ($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId'])) {
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productPrice', PHPShopText::comment('<'));
            $this->set('productValutaName', PHPShopText::comment('>'));
        }


        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }
/*
        $mod_price=strval($this->get('productPrice'));
        
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
        $this->set('productPrice',$mod_price);
*/
        //if ($this->get('productName')=='Мотоблок МКМ-3-DK7 Lander (Пахарь)') {
        	//PHPShopProductFunction::GetPriceValuta($row['id'],$row['price'],2);
        	//$this->set('test',$this->get('productPrice'));
        	//$this->get('productPriceRub');
        	//PHPShopProductFunction::GetPriceValuta($row['id'],$row['price'],$row['baseinputvaluta'])
        //}        
    }

    /**
     * Стоимость товара
     * @param array $row массив данных товара
     * @param bool $newpric изменилась цена
     * @return float
     */
    function price($row, $newprice = false) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        // Если есть новая цена
        if(empty($newprice)) 
		{
		
		//nah
			if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) )
				$price=$row['price2'];
			else		
				$price=$row['price'];	
		
		}	
        else $price=$row['price_n'];

        return PHPShopProductFunction::GetPriceValuta($row['id'], array($price, $row['price2'], $row['price3'], $row['price4'], $row['price5']), $row['baseinputvaluta']);
    }

    /**
     * Генератор сетки товаров
     * @param array $dataArray массив данных
     * @param int $cell разрад сетки [1-5]
     * @param string $template файл шаблона
     * @param bool $line показывать сетку разделитель
     * @return string
     */
    function product_grid($dataArray, $cell, $template = false, $line = true) {

        if (!empty($line))
            $this->grid = true;
        else
            $this->grid = false;

        if (empty($cell))
            $cell = 2;
        $this->cell = $cell;
        $this->setka_footer = true;

        $table = null;
        $j = 1;
        $item = 1;

        $this->set('productInfo', $this->lang('productInfo'));
        $this->set('productSale', $this->lang('productSale'));
        $this->set('productValutaName', $this->currency());

        $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
        if (is_array($dataArray)) {
            $this->total = count($dataArray);
            foreach ($dataArray as $row) {

                // Определяем переменные
                $this->set('productName', $row['name']);
                $this->set('productArt', $row['uid']);
                $this->set('productDes', $row['description']);
                $this->set('productPageThis', $this->PHPShopNav->getPage());

                // Пустая картинка
                if (empty($row['pic_small']))
                    $this->set('productImg', $this->no_photo);
                else
                    $this->set('productImg', $row['pic_small']);

                // Проверка режима Multibase
                $this->checkMultibase($row['pic_small']);

                $this->set('productImgBigFoto', $row['pic_big']);
                $this->set('productPriceMoney', $this->PHPShopSystem->getValue('dengi'));

                $this->set('productUid', $row['id']);
                $this->set('catalog', $this->lang('catalog'));

                // Опции склада
                $this->checkStore($row);

                // Шаблон ячейки товара
                if (empty($template))
                    $template = 'main_product_forma_' . $this->cell;


                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row);

                // Подключаем шаблон ячейки товара
                $dis = ParseTemplateReturn($this->getValue('templates.' . $template));


                // Убераем последний разделитель в сетке
                if ($item == $this->total)
                    $this->setka_footer = false;

                $cell_name = 'd' . $j;
                $$cell_name = $dis;

                if ($j == $this->cell) {
                    $table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
                    $j = 0;
                } elseif ($item == $this->total) {
                    $table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                }

                $j++;
                $item++;
            }
        }
        $this->product_grid = $table;

        return $table;
    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell() {

        // Оформление разделителя ячеек
        if ($this->grid)
            $this->grid_style = 'class="setka"';
        else
            $this->grid_style = '';

        $Arg = func_get_args();
        $item = 1;
        $tr = '<tr>';

        foreach ($Arg as $key => $value)
            if ($key < $this->cell and $this->total >= $this->cell)
                $args[] = $value;
            elseif (!empty($value))
                $args[] = $value;

        $num = count($args);

        // Расчет CSS стилей сетки товара
        switch ($num) {

            // Сетка в 1 ячейку
            case 1:
                $panel = array('panel_l panel_1_1');
                break;

            // Сетка в 2 ячейки
            case 2:
                $panel = array('panel_l panel_2_1', 'panel_r panel_2_2');
                break;

            // Сетка в 3 ячейки
            case 3:
                $panel = array('panel_l panel_3_1', 'panel_r panel_3_2', 'panel_l panel_3_2');
                break;

            // Сетка в 4 ячейки
            case 4:
                $panel = array('panel_l panel_4_1', 'panel_r panel_4_2', 'panel_l panel_4_3', 'panel_r panel_4_4',);
                break;

            // Сетка в 5 ячейки
            case 5:
                $panel = array('panel_l panel_5_1', 'panel_r panel_5_2', 'panel_l panel_5_3', 'panel_l panel_5_4', 'panel_l panel_5_5');
                break;

            default: $panel = array('panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l');
        }

        if (is_array($args))
            foreach ($args as $key => $val) {
                $tr.='<td class="' . $panel[$key] . '" valign="top">' . $val . '</td>';

                if ($item < $num and $num <= $this->cell)
                    $tr.='<td ' . $this->grid_style . '><img src="images/spacer.gif" width="1"></td>';

                $item++;
            }
        $tr.='</tr>';

        if (!empty($this->setka_footer))
            $tr.='<tr><td ' . $this->grid_style . ' colspan="' . ($this->cell * 2) . '" height="1"><img height="1" src="images/spacer.gif"></td></tr>';

        return $tr;
    }

}

?>