<?php

/**
 * Файл выгрузки для Яндекс Маркет
 * @package PHPShopCore
 */
$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("modules");

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 *  Вывод характеристик по имени
 *  @example $search=PHPShopSortSearch('Бренд','vendor'); $search->search($vendor_aray);
 */
class PHPShopSortSearch {

    /**
     * Выборка характеритик по имени
     * @param string $name имя характеристики
     * @param string $tag тэг обрамления
     */
	var $debug = true;
    function PHPShopSortSearch($name, $tag) {

        $this->tag = $tag;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name20']);
        $PHPShopOrm->debug = false;
        $data = $PHPShopOrm->select(array('id'), array('name' => '="' . $name . '"'), false, array('limit' => 1));
        if (is_array($data)) {
            $sort_category = $data['id'];
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name21']);
            $PHPShopOrm->debug = false;
            $data = $PHPShopOrm->select(array('id,name'), array('category' => '=' . $sort_category), false, array('limit' => 100));
            if (is_array($data)) {
                foreach ($data as $val)
                    $this->sort_array[$val['id']] = $val['name'];
            }
        }
    }

    /**
     * Поиск в массиве характеритик товара нужной характеристики
     * @param array $row массив характеристик товара
     * @return string имя характеристики в тэге
     */
    function search($row) {
        if (is_array($row))
            foreach ($row as $val) {
                if (!empty($this->sort_array[$val[0]])) {

                    // Проверка на сложный параметр
                    if(strstr($this->tag,' ')){
                        $tag_array=  explode(" ", $this->tag);
                        $tag_start=$this->tag;
                        $tag_end=$tag_array[0];
                    }
                    else{
                        $tag_start=$tag_end=$this->tag;
                    }
                          
                    return '
                        <' . $tag_start . '>' . $this->sort_array[$val[0]] . '</' . $tag_end . '>';
                }
            }
    }

}

/**
 * Создание YML для Яндекс Маркета
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopYml {

    var $xml = null;

    /**
     * вывод характеристик
     * @var bool 
     */
    var $vendor = false;

    /**
     * массив значений тег/имя характеристики
     * @var array 
     */
    var $vendor_name = array('vendor' => 'Бренд');

    /**
     * память событий модулей
     * @var bool 
     */
    var $memory = true;
	
	var $debug = true;
    /**
     * Конструктор
     */
    function PHPShopYml() {
        global $PHPShopModules;

        $this->PHPShopSystem = new PHPShopSystem();
        $PHPShopValuta = new PHPShopValutaArray();
        $this->PHPShopValuta = $PHPShopValuta->getArray();

        // Модули
        $this->PHPShopModules = &$PHPShopModules;

        // Процент накрутки
        $this->percent = $this->PHPShopSystem->getValue('percent');

        // Валюта по умолчанию
        $this->defvaluta = $this->PHPShopSystem->getValue('dengi');
        $this->defvalutaiso = $this->PHPShopValuta[$this->defvaluta]['iso'];
        $this->defvalutacode = $this->PHPShopValuta[$this->defvaluta]['code'];

        // Кол-во знаков после запятой в цене
        $this->format = $this->PHPShopSystem->getSerilizeParam('admoption.price_znak');

        $this->setHook(__CLASS__, __FUNCTION__);
    }

    /**
     * Назначение перехвата события выполнения модулем
     * @param string $class_name имя класса
     * @param string $function_name имя метода
     * @param mixed $data данные для обработки
     * @param string $rout позиция вызова к функции [END | START | MIDDLE], по умолчанию END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * Запись в память
     * @param string $param имя параметра [catalog.param]
     * @param mixed $value значение
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * Выборка из памяти
     * @param string $param имя параметра [catalog.param]
     * @param bool $check сравнить с нулем
     * @return
     */
    function memory_get($param, $check = false) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            if (isset($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]])) {
                if (!empty($check)) {
                    if (!empty($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]]))
                        return true;
                }
                else
                    return $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]];
            }
            elseif (!empty($check))
                return true;
        }
        else
            return true;
    }

    /**
     * Данные по каталогам
     * @return array массив каталогов
     */
    function category() {
        $Catalog = array();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
        $data = $PHPShopOrm->select(array('id,name,parent_to'), false, false, array('limit' => 1000));

        if (is_array($data))
            foreach ($data as $row) {
                $Catalog[$row['id']]['id'] = $row['id'];
                $Catalog[$row['id']]['name'] = $row['name'];
                $Catalog[$row['id']]['parent_to'] = $row['parent_to'];
            }

        return $Catalog;
    }

    /**
     * Данные по товарам
     * @return array массив товаров
     */
    function product() {
        $Products = array();

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $data = $PHPShopOrm->select(array('*'), array('yml' => "='1'", 'enabled' => "='1'", 'parent_enabled' => "='0'"), false, array('limit' => 1000000));
        if (is_array($data))
            foreach ($data as $row) {
	            if ( $row['sklad']==0) {
	            	$id = $row['id'];
	            	$name = htmlspecialchars($row['name']);
	            	$category = $row['category'];
	            	$uid = $row['uid'];
	            	$price = $row['price'];
	            	
	            	//у товара в его массиве хар-к находим тот показатель,  что отвечает за предмет сортировки
	            	$vendor=$row['vendor'];
	            	$reg="1200";//ищем по этому коду наш параметр
	            	$s=strpos($vendor,$reg);
	            	/*
	            	 if ($row['id']==1116) {
	            	echo $row['id'].' '.$s;
	            	}
	            	*/
	            	if ($s===false) {
	            		$picture_samovyvoz=false;
	            	} else {
	            		$picture_samovyvoz=true;	            		
	            	}
	            	
	            	if ($row['p_enabled'] == 1 && $picture_samovyvoz==true)
	            		$p_enabled = "true";
	            	else
	            		$p_enabled = "false";
	            	
	            	$description = trim(PHPShopString::mySubstr($row['description'], 300));
	            	$baseinputvaluta = $row['baseinputvaluta'];
	            	
	            	if ($baseinputvaluta) {
	            		if ($baseinputvaluta !== $this->defvaluta) {//Если валюта отличается от базовой
	            			$vkurs = $this->PHPShopValuta[$baseinputvaluta]['kurs'];
	            	
	            			// Приводим цену в базовую валюту
	            			$price = $price / $vkurs;
	            		}
	            	}
	            	
	            	$price = ($price + (($price * $this->percent) / 100));
	            	$price = round($price, $this->format);
	            	$pickup='';
	            	$delivery='';
	            	$local_delivery_cost='';
	            	//а) для товаров до 2999 руб.
	            	if (intval($price)<=2999) {
	            		$pickup='<pickup>true</pickup>';
	            		$delivery='<delivery>false</delivery>';
	            	}
	            	//б) для товаров от 3000 до 9999 руб.
	            	if ( intval($price)>=3000 && intval($price)<=9999) {
	            		$pickup='<pickup>true</pickup>';
	            		$delivery='<delivery>true</delivery>';
	            		$local_delivery_cost='<local_delivery_cost>300</local_delivery_cost>';
	            	}
	            	//в) для товаров от 10000 руб.
	            	if (intval($price)>=10000) {
	            		$pickup='<pickup>true</pickup>';
	            		$delivery='<delivery>true</delivery>';
	            		$local_delivery_cost='<local_delivery_cost>0</local_delivery_cost>';
	            	}
	            	
	            	$array = array(
	            			"id" => $id,
	            			"category" => $category,
	            			"name" => $name,
	            			"picture" => $row['pic_small'],
	            			"price" => $price,
	            			"p_enabled" => $p_enabled,
	            			"yml_bid_array" => unserialize($row['yml_bid_array']),
	            			"uid" => $uid,
	            			"description" => $description,
	            			"pickup" => $pickup,
	            			"delivery" => $delivery,
	            			"local_delivery_cost" => $local_delivery_cost,
	            			"sales_notes" => $GLOBALS['SysValue']['lang']['yandex_mark_sales_notes'],
	            			"store" => '<store>true</store>'
	            	);
	            	
	            	// Параметр сортировки
	            	if (!empty($this->vendor))
	            		$array['vendor_array'] = unserialize($row['vendor_array']);
	            		
	            	$Products[$id] = $array;
	            }
            }
        return $Products;
    }

    /**
     * Заголовок 
     */
    function setHeader() {
        $this->xml.='<?xml version="1.0" encoding="windows-1251"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="' . date('Y-m-d H:m') . '">

<shop>
<name>' . $this->PHPShopSystem->getName() . '</name>
<company>' . $this->PHPShopSystem->getValue('company') . '</company>
<url>http://' . $_SERVER['SERVER_NAME'] . '</url>';
    }

    /**
     * Валюты 
     */
    function setCurrencies() {
        $this->xml.='<currencies>';
        $this->xml.='<currency id="' . $this->PHPShopValuta[$this->PHPShopSystem->getValue('dengi')]['iso'] . '" rate="1"></currency>';
        $this->xml.='</currencies>';
    }

    /**
     * Категории 
     */
    function setCategories() {
        $this->xml.='<categories>';
        $category = $this->category();
        foreach ($category as $val) {
		    if (($val['parent_to'] !="1") and ($val['id'] != "1")) {
			
            if ((empty($val['parent_to'])) or ($val['id'] == $val['parent_to']))
                $this->xml.='<category id="' . $val['id'] . '">' . $val['name'] . '</category>';
            else
                $this->xml.='<category id="' . $val['id'] . '" parentId="' . $val['parent_to'] . '">' . $val['name'] . '</category>';
				}
        }

        $this->xml.='</categories>';
    }

    /**
     * Доставка
     */
    function setDelivery() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name30']);
        $data = $PHPShopOrm->select(array('price'), array('flag' => "='1'"), false, array('limit' => 1));
        if (is_array($data))
            $this->xml.='<local_delivery_cost>' . $data['price'] . '</local_delivery_cost>';
    }

    /**
     * Товары 
     */
    function setProducts() {
        $vendor=null;
        $this->xml.='<offers>';
        $product = $this->product($vendor = true);

        // Учет модуля SEOURL
        if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
            $seourl_enabled = true;
        }

        // Поиск характеристики по имени
        if ($this->vendor) {
            if (is_array($this->vendor_name))
                foreach ($this->vendor_name as $vendor_tag => $vendor_name)
                    $PHPShopSortSearch[] = new PHPShopSortSearch($vendor_name, $vendor_tag);
        }

        $seourl = null;

        foreach ($product as $val) {

            $bid_str = null;
            $bid_str = null;
            $vendor = null;

            // Тэг характеристики
            if ($this->vendor){
                if(is_array($PHPShopSortSearch))
                    foreach($PHPShopSortSearch as $SortSearch)
                $vendor.= $SortSearch->search($val['vendor_array']);
            }

            // Если есть bid
            if (!empty($val['yml_bid_array']['bid']))
                $bid_str = '  bid="' . $val['yml_bid_array']['bid'] . '" ';

            // Если есть cbid
            if (!empty($val['yml_bid_array']['cbid_enabled']))
                $bid_str.='  cbid="' . $val['yml_bid_array']['cbid'] . '" ';

            if (!empty($seourl_enabled))
                $seourl = '_' . PHPShopString::toLatin($val['name']);
            $xml ='<offer id="' . $val['id'] . '" available="' . $val['p_enabled'] . '" ' . $bid_str . '>
	  <url>http://' . $_SERVER['SERVER_NAME'] . '/shop/UID_' . $val['id'] . $seourl . '.html?utm_source=market&amp;utm_medium=cpc&amp;utm_term='. $val['id'] . '</url> 
      <price>' . $val['price'] . '</price>
      <currencyId>' . $this->defvalutaiso . '</currencyId>
      <categoryId>' . $val['category'] . '</categoryId>
      <picture>http://' . $_SERVER['SERVER_NAME'] . $val['picture'] . '</picture>
      <name>' . $val['name'] . '</name>
      <description>' . $val['description'] . '</description>' .
      $val['pickup'].
      $val['delivery'].
      $val['local_delivery_cost'].
      '<sales_notes>'.$val['sales_notes'].'</sales_notes>'.
      '<manufacturer_warranty>true</manufacturer_warranty>'.
      $val['store'].
                    $vendor . '
      ';
            $cart_min = $this->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
            //if (!empty($cart_min))
			$cart_min=5000;
			
            //$xml.= '<sales_notes>минимальная сумма заказа ' . $cart_min . ' ' . $this->defvalutacode . '</sales_notes>';

            $xml.='
</offer>
';

            // Перехват модуля, занесение в память наличия модуля для оптимизации
            if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
                $hook = $this->setHook(__CLASS__, __FUNCTION__, $xml);
                if ($hook) {
                    $this->xml.= $hook;
                } else {
                    $this->xml.= $xml;
                    $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
                }
            }
            else
                $this->xml.= $xml;
        }
        $this->xml.='</offers>';
    }

    /**
     * Подвал 
     */
    function serFooter() {
        $this->xml.='</shop></yml_catalog>';
    }

    /**
     * Компиляция документа, вывод результата 
     */
    function compile() {
        $this->setHeader();
        $this->setCurrencies();
        $this->setCategories();
        $this->setDelivery();
        $this->setProducts();
        $this->serFooter();
        echo $this->xml;
    }

}

$PHPShopYml = new PHPShopYml();
$PHPShopYml->compile();
//print_r($PHPShopBase->SysValue);
?>