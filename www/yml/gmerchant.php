<?php

/**
 * Файл выгрузки для GOOGLE MERCHANT CENTER
 * @author dkrasavin
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
    private $debug = true;
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
 * Создание RSS для GOOGLE MERCHANT CENTER
 * @author dkrasavin
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopRSS {

    private $xml = null;

    /**
     * вывод характеристик
     * @var bool 
     */
    private $vendor = true;

    /**
     * массив значений тег/имя характеристики
     * @var array 
     */
    private $vendor_name = array('vendor' => 'Бренд');

    private $Catalog=array();
    /**
     * память событий модулей
     * @var bool 
     */
    private $memory = true;
    
    private $debug = true;
    
    private $productStatus = ['new','refurbished','used'];
    
    private $productAvailableStatus = ['in stock','out of stock','preorder']; 
    
    /**
     * Конструктор
     */
    function PHPShopRSS() {
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
    function category($id) {
        //$Catalog = array();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name']);
        $data = $PHPShopOrm->select(array('id,name,parent_to'), array('id' => "=$id"), false, array('limit' => 1));

        if (is_array($data))
            //foreach ($data as $row) {
                if ( $data['parent_to']<>0 ) {
                    //$this->Catalog[$row['id']]['id'] = $row['id'];
                    $this->Catalog[]= $data['name'] ;
                    //$this->Catalog[$row['id']]['parent_to'] = $row['parent_to'];
                    $this->category( $data['parent_to'] );
                } else {
                    $this->Catalog[]= $data['name'] ;                    
                    return $this->Catalog;
                }
            //}
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
	            	$name = htmlspecialchars($row['name'],ENT_QUOTES|ENT_XML1,'cp1251');
	            	$category = $row['category'];
	            	$uid = $row['uid'];
	            	$price = $row['price'];
	            	$content = strip_tags($row['content']);
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
	            	//а) для товаров до 4999 руб.
	            	if (intval($price)<=4999) {
	            		$pickup='<pickup>true</pickup>';
	            		$delivery='<delivery>false</delivery>';
	            	}
	            	//б) для товаров от 5000 до 9999 руб.
	            	if ( intval($price)>=5000 && intval($price)<=9999) {
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
                                        "content" => $content,
	            			"picture" => $row['pic_small'],
	            			"price" => $price,
                                        "productAvailableStatus" => $this->productAvailableStatus[0],
	            			"p_enabled" => $p_enabled,
	            			"yml_bid_array" => unserialize($row['yml_bid_array']),
	            			"uid" => $uid,
	            			"description" => $description,
	            			"pickup" => $pickup,
	            			"delivery" => $delivery,
                                        "pic_big" => $row['pic_big'],
	            			"local_delivery_cost" => $local_delivery_cost,
	            			"sales_notes" => $GLOBALS['SysValue']['lang']['yandex_mark_sales_notes'],
	            			"store" => '<store>true</store>'
	            	);
	            	
	            	// Параметр сортировки
	            	if ($this->vendor) {
                            $array['vendor_array'] = unserialize($row['vendor_array']);
                            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                            //var_dump($array['vendor_array'][42][0]);
                            if (!empty($array['vendor_array'][42][0])){
                                $data = $PHPShopOrm->select(array('name'), array('id' => '='.$array['vendor_array'][42][0]), false, array('limit' => 1));
                                if (is_array($data)){
                                    $array['vendor_name']=$data['name'];
                                }                                
                            } else{
                                $array['vendor_name']='';
                            }                                
                        }

	            	$Products[$id] = $array;
	            }
            }
            //echo $this->vendor;
            //var_dump($Products);
        return $Products;
    }

    /**
     * Заголовок 
     */
    function setHeader() {
        $this->xml.='<?xml version="1.0" encoding="UTF-8"?><rss xmlns:g="http://base.google.com/ns/1.0" version="2.0"><channel><title>'.trim($this->PHPShopSystem->getName()).'</title><link>https://' . $_SERVER['SERVER_NAME'] . '</link><description>'.trim($this->PHPShopSystem->getDescription()).' '.trim($this->PHPShopSystem->getName()). '</description>';
    }

    /**
     * Валюты 
     */
    function setCurrencies() {
        $this->xml.='<currencies>';
        $this->xml.='<currency id="' . $this->PHPShopValuta[$this->PHPShopSystem->getValue('dengi')]['iso'] . '" rate="1"/>';
        $this->xml.='</currencies>';
    }

    /**
     * Категории 
     */
    function setCategories() {
        $this->xml.='<categories>';
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
        //var_dump($PHPShopSortSearch);
        $seourl = null;

        foreach ($product as $val) {

            $bid_str = null;
            $bid_str = null;
            $vendor = null;
            $this->xml.='<item>';            
            $this->Catalog=array();
            
            $this->category($val['category']);
            //var_dump($this->Catalog);
            //exit();
            // Тэг характеристики
            if ($this->vendor){
                if(is_array($PHPShopSortSearch))
                    foreach($PHPShopSortSearch as $SortSearch)
                $vendor.= $SortSearch->search($val['vendor_array']);
            }
            //var_dump($vendor);
            // Если есть bid
            if (!empty($val['yml_bid_array']['bid']))
                $bid_str = '  bid="' . $val['yml_bid_array']['bid'] . '" ';

            // Если есть cbid
            if (!empty($val['yml_bid_array']['cbid_enabled']))
                $bid_str.='  cbid="' . $val['yml_bid_array']['cbid'] . '" ';

            if (!empty($seourl_enabled))
                $seourl = '_' . PHPShopString::toLatin($val['name']);
                if (stripos ( $val['vendor_name'] ,',')===false)
                    $vendor_name = $val['vendor_name'];
                else
                    $vendor_name = stristr ( $val['vendor_name'] , ',' ,true );
                
                if (empty($vendor_name))
                    $vendor_name='N/A';
                
            $xml ='<title>' . trim(substr($val['name'],0,70)) . '</title>' .
                  '<link>https://' . $_SERVER['SERVER_NAME'] . '/shop/UID_'.$val['id'].'.html</link>' .
                  '<description>' . trim(substr($val['description'],0,1000)) . '</description>' .
                  '<g:id>' . $val['id'] . '</g:id>' .
                  '<g:condition>' . $this->productStatus[0]. '</g:condition>' .
                  '<g:price>' . $val['price'] . '</g:price>' .
                  '<g:availability>' .$val['productAvailableStatus']. '</g:availability>' .
                  '<g:image_link>https://' . $_SERVER['SERVER_NAME'] . $val['pic_big'] . '</g:image_link>' .
                  '<g:brand>' . htmlspecialchars ( $vendor_name ). '</g:brand>' .
                  '<g:product_type>' . implode( '>' ,array_reverse($this->Catalog) ) . '</g:product_type>' .
                  '<g:google_product_category>536</g:google_product_category>' .
                  '<g:adwords_redirect>https://' . $_SERVER['SERVER_NAME'] . '/shop/UID_'.$val['id'].'.html</g:adwords_redirect>';
            $cart_min = $this->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
            //if (!empty($cart_min))
			$cart_min=5000;
			
            //$xml.= '<sales_notes>минимальная сумма заказа ' . $cart_min . ' ' . $this->defvalutacode . '</sales_notes>';

            $xml.='</item>';

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
        //$this->xml.='</offers>';
    }

    /**
     * Подвал 
     */
    function setFooter() {
        $this->xml.='</channel></rss>';
    }

    /**
     * Компиляция документа, вывод результата 
     */
    function compile() {
        $this->setHeader();
        //$this->setCurrencies();
        //$this->setCategories();
        //$this->setDelivery();
        $this->setProducts();
        $this->setFooter();
		$handle = fopen("gmerchant.xml","w+");
		fwrite ( $handle , $this->xml,strlen($this->xml) );
		fclose($handle);
        echo $this->xml;
    }
}

$PHPShopRSS = new PHPShopRSS();
$PHPShopRSS->compile();
//print_r($PHPShopBase->SysValue);
?>