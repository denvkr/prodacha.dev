<?php

/**
 * ���� �������� ��� ������ ������
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

// ������
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 *  ����� ������������� �� �����
 *  @example $search=PHPShopSortSearch('�����','vendor'); $search->search($vendor_aray);
 */
class PHPShopSortSearch {

    /**
     * ������� ������������ �� �����
     * @param string $name ��� ��������������
     * @param string $tag ��� ����������
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
     * ����� � ������� ������������ ������ ������ ��������������
     * @param array $row ������ ������������� ������
     * @return string ��� �������������� � ����
     */
    function search($row) {
        if (is_array($row))
            foreach ($row as $val) {
                if (!empty($this->sort_array[$val[0]])) {

                    // �������� �� ������� ��������
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
 * �������� YML ��� ������ �������
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopClass
 */
class PHPShopYml {

    var $xml = null;

    /**
     * ����� �������������
     * @var bool 
     */
    var $vendor = false;

    /**
     * ������ �������� ���/��� ��������������
     * @var array 
     */
    var $vendor_name = array('vendor' => '�����');

    /**
     * ������ ������� �������
     * @var bool 
     */
    var $memory = true;
	
	var $debug = true;
    /**
     * �����������
     */
    function PHPShopYml() {
        global $PHPShopModules;

        $this->PHPShopSystem = new PHPShopSystem();
        $PHPShopValuta = new PHPShopValutaArray();
        $this->PHPShopValuta = $PHPShopValuta->getArray();

        // ������
        $this->PHPShopModules = &$PHPShopModules;

        // ������� ��������
        $this->percent = $this->PHPShopSystem->getValue('percent');

        // ������ �� ���������
        $this->defvaluta = $this->PHPShopSystem->getValue('dengi');
        $this->defvalutaiso = $this->PHPShopValuta[$this->defvaluta]['iso'];
        $this->defvalutacode = $this->PHPShopValuta[$this->defvaluta]['code'];

        // ���-�� ������ ����� ������� � ����
        $this->format = $this->PHPShopSystem->getSerilizeParam('admoption.price_znak');

        $this->setHook(__CLASS__, __FUNCTION__);
    }

    /**
     * ���������� ��������� ������� ���������� �������
     * @param string $class_name ��� ������
     * @param string $function_name ��� ������
     * @param mixed $data ������ ��� ���������
     * @param string $rout ������� ������ � ������� [END | START | MIDDLE], �� ��������� END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * ������ � ������
     * @param string $param ��� ��������� [catalog.param]
     * @param mixed $value ��������
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * ������� �� ������
     * @param string $param ��� ��������� [catalog.param]
     * @param bool $check �������� � �����
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
     * ������ �� ���������
     * @return array ������ ���������
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
     * ������ �� �������
     * @return array ������ �������
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
	            	
	            	//� ������ � ��� ������� ���-� ������� ��� ����������,  ��� �������� �� ������� ����������
	            	$vendor=$row['vendor'];
	            	$reg="1200";//���� �� ����� ���� ��� ��������
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
	            		if ($baseinputvaluta !== $this->defvaluta) {//���� ������ ���������� �� �������
	            			$vkurs = $this->PHPShopValuta[$baseinputvaluta]['kurs'];
	            	
	            			// �������� ���� � ������� ������
	            			$price = $price / $vkurs;
	            		}
	            	}
	            	
	            	$price = ($price + (($price * $this->percent) / 100));
	            	$price = round($price, $this->format);
	            	$pickup='';
	            	$delivery='';
	            	$local_delivery_cost='';
	            	//�) ��� ������� �� 4999 ���.
	            	if (intval($price)<=4999) {
	            		$pickup='<pickup>true</pickup>';
	            		$delivery='<delivery>false</delivery>';
	            	}
	            	//�) ��� ������� �� 5000 �� 9999 ���.
	            	if ( intval($price)>=5000 && intval($price)<=9999) {
	            		$pickup='<pickup>true</pickup>';
	            		$delivery='<delivery>true</delivery>';
	            		$local_delivery_cost='<local_delivery_cost>300</local_delivery_cost>';
	            	}
	            	//�) ��� ������� �� 10000 ���.
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
	            			"store" => '<store>true</store>',
                            "product_type" => $row['product_type'],
                            "product_model" => $row['product_model'],
                            "product_vendor" => $row['product_vendor']
	            	);
	            	
	            	// �������� ����������
	            	if (!empty($this->vendor))
	            		$array['vendor_array'] = unserialize($row['vendor_array']);
	            		
	            	$Products[$id] = $array;
	            }
            }
        return $Products;
    }

    /**
     * ��������� 
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
     * ������ 
     */
    function setCurrencies() {
        $this->xml.='<currencies>';
        $this->xml.='<currency id="' . $this->PHPShopValuta[$this->PHPShopSystem->getValue('dengi')]['iso'] . '" rate="1"/>';
        $this->xml.='</currencies>';
    }

    /**
     * ��������� 
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
     * ��������
     */
    function setDelivery() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name30']);
        $data = $PHPShopOrm->select(array('price'), array('flag' => "='1'"), false, array('limit' => 1));
        if (is_array($data))
            $this->xml.='<local_delivery_cost>' . $data['price'] . '</local_delivery_cost>';
    }

    /**
     * ������ 
     */
    function setProducts() {
        $vendor=null;
        $this->xml.='<offers>';
        $product = $this->product($vendor = true);

        // ���� ������ SEOURL
        if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
            $seourl_enabled = true;
        }

        // ����� �������������� �� �����
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

            // ��� ��������������
            if ($this->vendor){
                if(is_array($PHPShopSortSearch))
                    foreach($PHPShopSortSearch as $SortSearch)
                        $vendor.= $SortSearch->search($val['vendor_array']);
            }

            // ���� ���� bid
            if (!empty($val['yml_bid_array']['bid']))
                $bid_str = '  bid="' . $val['yml_bid_array']['bid'] . '" ';

            // ���� ���� cbid
            if (!empty($val['yml_bid_array']['cbid_enabled']))
                $bid_str.='  cbid="' . $val['yml_bid_array']['cbid'] . '" ';

            if (!empty($seourl_enabled))
                $seourl = '_' . PHPShopString::toLatin($val['name']);
            $xml ='<offer id="' . $val['id'] . '" available="' . $val['p_enabled'] . '" ' . $bid_str . '>
      <url>http://' . $_SERVER['SERVER_NAME'] . '/shop/UID_' . $val['id'] . $seourl . '.html?from=yml&amp;utm_source=market&amp;utm_medium=cpc&amp;utm_term='. $val['id'] . '&amp;utm_campaign=' .$this->encodestring($val['name']). '</url>
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
			
            //$xml.= '<sales_notes>����������� ����� ������ ' . $cart_min . ' ' . $this->defvalutacode . '</sales_notes>';

            $xml.='
</offer>
';

            // �������� ������, ��������� � ������ ������� ������ ��� �����������
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
     * ������ 
     */
    function serFooter() {
        $this->xml.='</shop></yml_catalog>';
    }

    /**
     * ���������� ���������, ����� ���������� 
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
    
    // ������� ������� ������ � ��������� � ���������
    function encodestring($st)
    {
    	$st=str_replace(array('&amp;','&quot;','&apos;'),'',$st);
        
    	// ������� �������� "��������������" ������.
    	$st=strtr($st,"������������������������",
    			"abvgdejzijklmnoprstufhye");
    	$st=strtr($st,"������������������������",
    			"ABVGDEJZIJKLMNOPRSTUFHYE");
    	// ����� - "���������������" � ��.
    	$st=strtr($st,
    			array(
    					"�"=>"yo",  "�"=>"c",  "�"=>"ch", "�"=>"sh",
    					"�"=>"sch", "�"=>"",   "�"=>"yu", "�"=>"ya",
    					"�"=>"C",   "�"=>"Ch", "�"=>"Sh", "�"=>"",
    					"�"=>"Sch", "�"=>"",   "�"=>"",   "�"=>"Yu",
    					"�"=>"Ya",	"("=>"",   ";"=>"",   ")"=>"", 
    					"-"=>"",    "/"=>"",   ","=>"",   "�"=>"",
    					"�"=>"",    "&"=>"",   "`"=>"",   "~"=>"", 
    					"!"=>"",    "@"=>"",   "#"=>"",   "$"=>"", 
    					"%"=>"",    "^"=>"",   "*"=>"",   "+"=>"", 
    					"="=>"",    "|"=>"",   "\\"=>"",  ":"=>"",
    					"'"=>"",    "."=>"",   "?"=>"",   "<"=>"",
    					">"=>"",    "{"=>"",   "{"=>"",   "}"=>"",
    					"["=>"",    "]"=>"",   " "=>"_" )
    	);
    	// ���������� ���������.
    	return $st;
    }

}

$PHPShopYml = new PHPShopYml();
$PHPShopYml->compile();
//print_r($PHPShopBase->SysValue);
?>