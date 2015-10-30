<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
/**
 * ������������ ����� ���� ������ �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShopCore
 * @version 1.3
 * @package PHPShopClass
 */

 //������ ��� ������ �������
//include $_SERVER['DOCUMENT_ROOT']."/catalog_product_icons.php"; //

class PHPShopShopCore extends PHPShopCore {

    /**
     * ����-�������� ��� ���������� ���� ������
     * @var string
     */
    var $no_photo = 'images/shop/no_photo.gif';

    /**
     * �������
     * @var bool
     */
    var $debug = true;

    /**
     * �����������, ������������� [true]
     * @var bool
     */
    var $cache = true;

    /**
     * �������������� ��������� ����
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');

    /**
     * ��������� ����� � ����� �������
     * @var bool
     */
    var $grid = true;

    /**
     * ����� ������ ������� �� 1 ��������, ������������� 100-300
     * @var int
     */
    var $max_item = 100;

    /**
     * ������ ��������� ���������� ������� � �������. ��� �������������� ������� � ����� ������� ��������� ������ [false]
     * @var bool
     */
    var $memory = true;
    var $multi_cat = array();
	
    /**
     * �������� ��������� ������� ��� ������� � �������� �������
     * @var int
     */	
	public $num_of_pages=0;
     /**
     * ���-�� ����� � ������ ����� �������
     * @var int
     */	       
    //var $cell;
    /**
     * �����������
     */
    function PHPShopShopCore() {
        global $PHPShopValutaArray;

        // ��� ��
        $this->objBase = $GLOBALS['SysValue']['base']['products'];

        // ������ �����
        $this->Valuta = $PHPShopValutaArray->getArray();

        PHPShopObj::loadClass('product');
        parent::PHPShopCore();

        // ������ ������
        $this->dengi = $this->PHPShopSystem->getParam('dengi');
    }
	
	//������ ������������� ������
    function catalog_product_icons($row) {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    //������ ������������� ������
    function product_icons($row) {
    
    	// �������� ������
    	if ($this->setHook(__CLASS__, __FUNCTION__, $row))
    		return true;
    
    	$this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }
        
    /**
     * ��������� SQL ������� ��� �������
     * @param string $where �������� ������
     * @return mixed
     */
    function query_filter($where = false) {
        global $SysValue;

        if (!empty($where))
            $where.=' and ';

        $sort = null;

        $this->set('productRriceOT', 0);
        $this->set('productRriceDO', 0);

        $v = @$SysValue['nav']['query']['v'];
        $s = PHPShopSecurity::TotalClean(@$SysValue['nav']['query']['s'], 1);
        $f = PHPShopSecurity::TotalClean(@$SysValue['nav']['query']['f'], 1);

        if ($this->PHPShopNav->isPageAll())
            $p = PHPShopSecurity::TotalClean($p, 1);

        // ���������� �� ���������������
        if (is_array($v)) {
            foreach ($v as $key => $value) {
                if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                    $hash = $key . "-" . $value;
                    $sort.=" and vendor REGEXP 'i" . $hash . "i' ";
                }
            }
        }


        // ��������
        $percent = $this->PHPShopSystem->getValue('percent');

        // ���������� �������������� �������������
        switch ($f) {
            case(1): $order_direction = "";
                $this->set('productSortNext', 2);
                $this->set('productSortImg', 1);
                $this->set('productSortT', 1);
                break;
            case(2): $order_direction = " desc";
                $this->set('productSortNext', 1);
                $this->set('productSortImg', 2);
                $this->set('productSortT', 2);
                break;
            default: $order_direction = "";
                $this->set('productSortNext', 2);
                $this->set('productSortImg', 1);
                $this->set('productSortT', 1);
                break;
        }
        switch ($s) {
            case(1): $order = array('order' => 'name' . $order_direction);
                $this->set('productSortA', 'sortActiv');
                break;
            case(2): $order = array('order' => 'price' . $order_direction);
                $this->set('productSortB', 'sortActiv');
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                $this->set('productSortC', 'sortActiv');
                break;
            default:
                $order = array('order' => 'num' . $order_direction);
                $this->set('productSortC', 'sortActiv');
                break;
        }

        // ����������� ������ ������� ���������� � ������
        foreach ($order as $key => $val)
            $string = $key . ' by ' . $val;

        // ��� ��������
        if ($this->PHPShopNav->isPageAll()) {
            $sql = "select * from " . $this->getValue('base.products') . " where (" . $where . " enabled='1' and parent_enabled='0') " . $sort . " " . $string . ' limit ' . $obj->max_item;
        }

        // ����� �� ����
        elseif (isset($_POST['priceSearch']) or !empty($sort)) {

            if (!empty($_POST['priceOT']) or !empty($_POST['priceDO'])) {
                $priceOT = TotalClean($_POST['priceOT'], 1);
                $priceDO = TotalClean($_POST['priceDO'], 1);

                $this->set('productRriceOT', $priceOT);
                $this->set('productRriceDO', $priceDO);

                // �������������
                if ($priceDO == 0)
                    $priceDO = 1000000000;

                if (empty($priceOT))
                    $priceOT = 0;

                // ���� � ������ ��������� ������
                $priceOT/=$this->currency('kurs');
                $priceDO/=$this->currency('kurs');

                // ������� ������ �� ����
                $price_sort = "and price >= " . ($priceOT / (100 + $percent) * 100) . " AND price <= " . ($priceDO / (100 + $percent) * 100);
            }

            $sql = "select * from " . $this->getValue('base.products') . " where " . $where . " enabled='1' and parent_enabled='0' " . $price_sort . " " . $sort . $string . ' limit 0,' . $this->max_item;
        }
        else {
            // ���������� ������ ��������� ���������� ����������
            return $order;
        }

        // ���������� SQL ������� ������
        return $sql;
    }

    /**
     * ������
     * @param string $name ��� ���� � ������� ����� ��� ������
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
     * ������� �� ��
     * @param array $select ������ ������� �������
     * @param array $where ������ ������� �������
     * @param array $order ������ ������� �������
     * @param array $option ������ ������� �������
     * @param string $function_name ��� ������� ��� �������
     * @param array $from ������ �����
     * @return array
     */
    function select($select, $where, $order = false, $option = array('limit' => 1), $function_name = false, $from = false) {

        if (is_array($from)) {
            $base = @$from['base'];
            $cache = @$from['cache'];
            $cache_format = @$from['cache_format'];
        } else {
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
     * ��������� ������
     * @param array $row ������ ������ ������
     * @param bool $newprice ���������� ����
     * @return float
     */
    function price($row, $newprice = false) {

        // �������� ������, ��������� � ������ ������� ������ ��� �����������
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, $newprice);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        // ���� ���� ����� ����
        if (empty($newprice))
            $price = $row['price'];
        else
            $price = $row['price_n'];

        return PHPShopProductFunction::GetPriceValuta($row['id'], array($price, $row['price2'], $row['price3'], $row['price4'], $row['price5']), $row['baseinputvaluta']);
    }

    /**
     * ��������� ����������
     * @param int $count ���������� ������� �� ��������
     * @param string $sql SQL ������ � ���� ������ ��� ������� ������� (���������� AND � OR � ����� �������, ������� �� WHERE)
     */
    function setPaginator($count, $sql = null) {
    	//echo $this->objPath;
    	//echo $this->nav_len;
        // �������� ������ � ������ �������
        if ($this->setHook(__CLASS__, __FUNCTION__, array('count' => $count, 'sql' => $sql), 'START'))
            return true;

        // ���-�� ������
        $this->count = $count;
        $SQL = null;
        $delim = ' | ';

        // ������� �� ���������� WHERE
        $nWhere = 1;
        if (is_array($this->where)) {
            foreach ($this->where as $pole => $value) {
                $SQL.=$pole . $value;
                if ($nWhere < count($this->where))
                    $SQL.=$this->PHPShopOrm->Option['where'];
                $nWhere++;
            }
        }
        else
            $SQL = $sql;
		
		//echo $SQL.'<br />';
		//echo $this->objBase.'<br />';
        // ����������
        $sort = '?';
        if (!empty($_GET['s']) and is_numeric($_GET['s']))
            $sort.='s=' . $_GET['s'] . '&';
        if (!empty($_GET['f']) and is_numeric($_GET['f']))
            $sort.='f=' . $_GET['f'] . '&';

        // �������
        if (!empty($_GET['v']) and is_array($_GET['v']))
            foreach ($_GET['v'] as $key => $val) {
                if (is_numeric($key) and is_numeric($val))
                    $sort.='v[' . $key . ']=' . $val . '&';
            }

        $sort = substr($sort, 0, strlen($sort) - 1);

        // ����� �������
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . ' where ' . $SQL);
        $row = mysql_fetch_array($result);
        $this->num_page = $row['count'];
		//echo "select COUNT('id') as count from " . $this->objBase . ' where '. $SQL.'<br />';
        $i = 1;
        $navigat = $delim;

        // ���-�� ������� � ���������
        $num = ceil($this->num_page / $this->num_row);//floor
		//echo $num;
		$this->num_of_pages=$num;
		//echo $this->num_of_pages;
		//echo strlen((int) $this->page);
		//echo strlen($this->page);
		//print_r($this->PHPShopNav->getName());
        // 404 ������ ��� ��������� ���������
        if ($this->page==1 || empty($this->page)) {//preg_match('/^[0-9]{1,3}$/i', $this->page) (($this->page > $this->num_page) && $this->page != 'ALL') {
			// ���� ������ �������������
			if (preg_match ('/^.*CID\_[0-9]{1,5}\_[^ALL].*\.html$/i', $this->PHPShopNav->getUrl())) {
				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
			}
        }
        
        //� ������ ������������� ������ �������� ���������� �� � ����������� ��������
       if ($this->PHPShopNav->getName()=='CID' || $this->PHPShopNav->getName()=='UID') {
       	//����������� ����� url ����� ������� ������������� ���� ��� ���� � ������� ��� ���� ������������� ������ ��� ����� ��� 301
       	$first_found=stripos($this->PHPShopNav->getUrl(),'_');
       	$last_found=strripos($this->PHPShopNav->getUrl(),'_');
		//�������� ����� ������ ����� ���������� _
       	if ( $first_found!=$last_found ) {
       		//����������� ���� ����� ����� ��������� ���� ��� ���� ��� ��������� _ �� ��� � 301
       		$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));

       		If (stripos($url_part,'_')==true) {
       			header("HTTP/1.1 301 Moved Permanently");
       			header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
				//echo '1';
       		}
       		//���� � ������ ��� .html �� ��� 301
       		$url_part=stripos($this->PHPShopNav->getUrl(),'.html');
       		if ($url_part==false) {
       			header("HTTP/1.1 301 Moved Permanently");
       			header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');	
       			//echo '2';
       		}
       		//�������� ��� �������� ������ ����� ������� _
       		$first_found=strripos($this->PHPShopNav->getUrl(),'_');
       		$last_found=stripos($this->PHPShopNav->getUrl(),'.html');
       		$url_part=substr($this->PHPShopNav->getUrl(),$first_found+1,($last_found-$first_found-1));
       		//echo $url_part.' ';
       		//���� ��� ����� � ������� ��� ��� ����� �� ����� ���������� � 301
       		if ($url_part!='ALL') {
       			if (strlen((int) $url_part)!=strlen($url_part)) {
       				header("HTTP/1.1 301 Moved Permanently");
       				header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
       				//echo '3';
       			}      			
       		}       		

       		//������ �������� ��������� ������������� �� ������ �������� ���� ���������
       		//echo $this->page;
       		//print_r($this->PHPShopNav);
       		//echo $this->num_of_pages;
       		if ($url_part!='ALL') {
       			if ($url_part-$this->num_of_pages>0) {
       				header("HTTP/1.1 301 Moved Permanently");
       				header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
       				//echo '4';
       			}
       		}   		
       	}
       }
        /*
        if (($this->page != 'ALL' && strlen((int) $this->page)!=strlen($this->page)) || ($this->page > $num)) {
        	// ���� ������ �������������
        	if ( preg_match ('/^.*CID\_[0-9]{1,5}\_.*\.html$/i', $this->PHPShopNav->getUrl()) )
        		header("HTTP/1.1 301 Moved Permanently");
        		header("Location:".$this->curPageURL().'/'.$this->PHPShopNav->getPath().'/'.$this->PHPShopNav->getName().'_'.$this->PHPShopNav->getId().'.html');
        	}
        }
        */
		//echo $num.' '.$this->page.' '.$this->PHPShopNav->getUrl();
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
                //echo 'i='.$i.'$this->page='.$this->page;
                if ($i != $this->page) {
                    if ($i == 1)
                        $navigat.=PHPShopText::a(substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort, $p_start . '-' . $p_end) . $delim;
                    else {
                        if ($i > ($this->page - $this->nav_len) and $i < ($this->page + $this->nav_len))
                            $navigat.=PHPShopText::a($this->objPath . $i . '.html' . $sort, $p_start . '-' . $p_end) . $delim;
                        else if ($i - ($this->page + $this->nav_len) < 3 and (($this->page - $this->nav_len) - $i) < 3)
                            $navigat.=".";
                    }
                }
                else
                    $navigat.=PHPShopText::b($p_start . '-' . $p_end) . $delim;
                $i++;
            }
			//echo $navigat.'<br>';
            $nav = $this->getValue('lang.page_now') . ': ';
            //echo $p_do.' '.$p_to;
            // ������� ����� ������ �������� CID_X_1.html
            if ($p_do == 1)
                $nav.=PHPShopText::a(substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort, PHPShopText::img('images/shop/3.gif', 0, 'absmiddle'), '&laquo; ' . $this->lang('nav_back')); //absmiddle
            else
                $nav.=PHPShopText::a($this->objPath . ($p_do) . '.html' . $sort, PHPShopText::img('images/shop/3.gif', 0, 'absmiddle'), '&laquo; ' . $this->lang('nav_back')); //absmiddle

            $nav.='  ' . $navigat . '  ';

            // ������� ����� ������ �������� CID_X_0.html
            if ($p_to == 0)
                $nav.=PHPShopText::a(substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort, PHPShopText::img('images/shop/4.gif', 0, 'absmiddle'), $this->lang('nav_forw') . ' &raquo;'); //absmiddle
            else
                $nav.=PHPShopText::a($this->objPath . ($p_to) . '.html' . $sort, PHPShopText::img('images/shop/4.gif', 0, 'absmiddle'), $this->lang('nav_forw') . ' &raquo;'); //absmiddle

            // �������� ������ �������� ���
            if (strtoupper($this->page) == 'ALL')
                $nav.=PHPShopText::nbsp(2) . PHPShopText::b(__('��� �������'));
            else
                $nav.=PHPShopText::nbsp(2) . PHPShopText::a($this->objPath . 'ALL.html' . $sort, __('��� �������'));
			
            //echo $nav;
            // ��������� ���������� �������������
            $this->set('productPageNav', $nav);

            // �������� ������ � ����� �������
            $this->setHook(__CLASS__, __FUNCTION__, $nav, 'END');
        }
    }

    /**
     * �������� ����������� ������ Multibase
     * @param string $img ����� �����������
     * @param bool $return ������� ���������� ��������
     */
    function checkMultibase($img) {

        $base_host = $this->PHPShopSystem->getSerilizeParam('admoption.base_host');
        if ($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host)) {
            $source_img = eregi_replace("/UserFiles/", "http://" . $base_host . "/UserFiles/", $img);
            return $source_img;
        }
        else
            return $img;
    }

    /**
     * �������� ����� �������� ������ Multibase
     * @param int $category
     * @return boolean 
     */
    function errorMultibase($category) {


        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {

            if (empty($this->multi_cat)) {
                $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->cache = true;
                $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 100));
                if (is_array($data)) {
                    foreach ($data as $row) {
                        $this->multi_cat[] = $row['id'];
                    }
                }
            }

            if (!in_array($category, $this->multi_cat))
                return true;
        }
    }

    /**
     * �������� �������������� ������ ������ �� ������
     * @param array $row ����� ������ �� ������
     */
    function checkStore($row = array()) {

        // ������� ���������
        if (empty($row['ed_izm']))
            $row['ed_izm'] = $this->lang('product_on_sklad_i');
        $this->set('productEdIzm', $row['ed_izm']);

        // ���������� ��������� ������
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1 and $row['items'] > 0)
            $this->set('productSklad', $this->lang('product_on_sklad') . " " . $row['items'] . " " . $row['ed_izm']);
        else
            $this->set('productSklad', '');

        // ���� ����� �� ������
        if ($row['sklad']==0) {

            $this->set('Notice', '');
            //$this->set('ComStartCart', '');
            //$this->set('ComEndCart', '');

            // ���� ��� ����� ����
            if (empty($row['price_n'])) {
            	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
            		$price=$row['price2'];
            	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$price=$row['price3'];
            	}
            	else {
            		$price=$row['price'];
            	}
            	 
            	$mod_price=strval($price);            	
                //$this->set('productPrice', $this->price($row));
		        //$mod_price=strval($this->price($row));

            	//������������ ����� ���� � ��������
            	$mod_price=$this->add_space_to_price($mod_price);
		        
		$this->set('productPrice',$mod_price);
                $this->set('productPriceRub', '');

            }

            // ���� ���� ����� ����
            else {
            	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
            		$price=$row['price2'];
            	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$price=$row['price3'];
            	}
            	else {
            		$price=$row['price'];
            	}            	
                //$productPrice = $this->price($row);
                $productPriceNew = $this->price($row, true);
                //$this->set('productPrice', $productPrice);
                $mod_price=strval($price);
                
            	//������������ ����� ���� � ��������
            	$mod_price=$this->add_space_to_price($mod_price);

                $this->set('productPrice',$mod_price);
                $this->set('productPriceRub', PHPShopText::strike($productPriceNew));
                
            }
        }

        // ����� ��� ����� | ���� � ������������
        
        else {
        	//��� �����
        	if ($row['sklad']==1 && empty($row['outdated'])) {        		
        		$this->set('collaboration','lostandfound');
        		//$this->set('productPriceRub', $this->lang('sklad_mesage'));
        		$this->set('price_comlain','<span class="price_comlain" ><noindex>����� �������?</noindex></span>');
        	}
        	//���� � ������������
        	if ( !(empty($row['outdated'])) ) {
        		$this->set('collaboration','outdated');
        		
        		//�� ������ ����� ���� ��������
        		$url=$GLOBALS['SysValue']['nav']['url'];

        		$s1=strpos($url,"UID");

				if ($s1===false) {
					$this->set('productPriceRub', '<noindex>'.$this->lang('outdated_message').'</noindex>');
					//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
					$sdvig_vlevo='left:-20px;';
				} else {
					$sdvig_vlevo='';
					$this->set('productPriceRub', '<strong style="position:relative;top:65px;left:-4px;font-size:13px;width:100%">'.$this->lang('outdated_message').'</strong>');
				}
				if ( !(empty($row['analog'])) ) {
					$this->set('price_comlain','<span id="price_comlain'.$row['id'].'" class="price_comlain" style="position:relative;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></span>'.
							'<a id="analog_href'.$row['id'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$row['analog'].'.html"></a>'.
							'<script type="text/javascript">$(document).ready(function() {'.
							'$("#price_comlain'.$row['id'].'").click( function( event ) {'.
							'	window.location=$("#analog_href'.$row['id'].'").attr("href");'.
							'	return false;'.
							'});});'.
							'</script>');					
				} else {
					$this->set('price_comlain','<span id="price_comlain'.$row['id'].'" class="price_comlain" style="position:relative;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></span>');
				}

        	}
        		 
        	if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
        		$price=$row['price2'];
        	} else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
        		$price=$row['price3'];
        	}
        	else {
        		$price=$row['price'];
        	}

        	$mod_price=strval($price);

        	//������������ ����� ���� � ��������
        	$mod_price=$this->add_space_to_price($mod_price);
        	 
        	$this->set('productPrice',$mod_price);
         	//echo 'test5';        	 
        	/* 
            $this->set('collaboration','lostandfound');
            if ( ($_COOKIE['sincity']=="sp") AND ($row['price2']!=0) ) {
            	$price=$row['price2'];
            } else if( ($_COOKIE['sincity']=="chb") AND ($row['price3']!=0) ) {
            		$price=$row['price3'];
            	}
            else {
            	$price=$row['price'];
            }
            
            $mod_price=strval($price);            
            //$mod_price=strval($this->price($row));
            
            //������������ ����� ���� � ��������
            $mod_price=$this->add_space_to_price($mod_price);
            
            $this->set('productPrice',$mod_price);
            
            //$this->set('productPrice', $this->price($row));
            $this->set('productPriceRub', $this->lang('sklad_mesage'));
            //$this->set('ComStartNotice', null);
            //$this->set('ComEndNotice', null);
            //$this->set('ComStartCart', PHPShopText::comment('<'));
            //$this->set('ComEndCart', PHPShopText::comment('>'));
             */

        }

        // ���� ���� ���������� ������ ����� �����������
        if ($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId'])) {
            //$this->set('ComStartCart', PHPShopText::comment('<'));
            //$this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productPrice', null);
            $this->set('productValutaName', null);
        }
        //��� �������� stihl ������������ ������ ��������		
        //$this->stihl_catalog_settings($row);
        //���������� �� ���� sort.hook.php
        if (empty($this->category)) {
                $this->PHPShopCategory = new PHPShopCategory($row['category']);
        }		
        $this->doLoadFunction('PHPShopShop', 'sort_table', $row, 'shop');

        // �������� ������, ��������� � ������ ������� ������ ��� �����������
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

    }

    /**
     * ����� ����� � ��������
     * @return string
     */
    function setCell() {

        // ���������� ����������� �����
        if ($this->grid)
            $this->grid_style = 'class="setka"';
        else
            $this->grid_style = '';

        $Arg = func_get_args();
        $item = 1;
        $tr = '<tr>';

        foreach ($Arg as $key => $value)
            if ($key < $this->cell)
                $args[] = $value;

        $num = count($args);

        // ������ CSS ������ ����� ������
        switch ($num) {
            // ����� � 1 ������
            case 1:
                $panel = array('panel_l panel_1_1');
                break;

            // ����� � 2 ������
            case 2:
                $panel = array('panel_l panel_2_1', 'panel_r panel_2_2');
                break;

            // ����� � 3 ������
            case 3:
                $panel = array('panel_l panel_3_1', 'panel_r panel_3_2', 'panel_l panel_3_2');
                break;

            // ����� � 4 ������
            case 4:
                $panel = array('panel_l panel_4_1', 'panel_r panel_4_2', 'panel_l panel_4_3', 'panel_l panel_4_4');
                break;

            // ����� � 5 ������
            case 5:
                $panel = array('panel_l panel_5_1', 'panel_r panel_5_2', 'panel_l panel_5_3', 'panel_l panel_5_4', 'panel_l panel_5_5');
                break;

            default: $panel = array('panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l');
        }

        if (is_array($args))
            foreach ($args as $key => $val) {
                $tr.='<td class="' . $panel[$key] . '" valign="top">' . $val . '</td>';

                if ($item < $num and $num == $this->cell)
                    $tr.='<td ' . $this->grid_style . '><img src="images/spacer.gif" width="1"></td>';

                $item++;
            }
        $tr.='</tr>';

        if (!empty($this->setka_footer))
            $tr.='<tr><td ' . $this->grid_style . ' colspan="' . ($this->cell * 2) . '" height="1"><img height="1" src="images/spacer.gif"></td></tr>';

        return $tr;
    }

    /**
     * ��������� ����� �������
     * @param array $dataArray ������ ������
     * @param int $cell ������ ����� [1-5]
     * @return string
     */
    function product_grid($dataArray, $cell = 2) {

        if (empty($cell))
            $cell = 2;
        $this->cell = $cell;
        $this->setka_footer = true;

        $table = null;
        $j = 1;
        $item = 1;
        $lastmodified = 0;

        // �����������
        $this->set('productSale', $this->lang('product_sale'));
        $this->set('productInfo', $this->lang('product_info'));
        $this->set('productPriceMoney', $this->dengi);
        $this->set('catalog', $this->lang('catalog'));
        $this->set('productPageThis', $this->PHPShopNav->getPage());

        $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
        if (is_array($dataArray)) {
            $total = count($dataArray);
            foreach ($dataArray as $row) {

                // ��������
                $this->set('productName', $row['name']);

                // �������
                $this->set('productArt', $row['uid']);

                // ������� ��������
                $this->set('productDes', $row['description']);

                // ���
                $this->set('productWeight', $row['weight']);
                

                // ������������ ���� ���������
                if ($row['datas'] > $lastmodified)
                    $lastmodified = $row['datas'];

                // ��������� ��������
                $this->set('productImg', $this->checkMultibase($row['pic_small']));

                // ������ ��������, ��������
                if (empty($row['pic_small']))
                    $this->set('productImg', $this->no_photo);

                // ������� ��������
                $this->set('productImgBigFoto', $this->checkMultibase($row['pic_big']));

                // �� ������
                $this->set('productUid', $row['id']);
				
				//~nah
				$this->catalog_product_icons($row);//"icon_".$row['id'];
				//echo $uid;
				//$this->set('Producticons',$uid);

/*
				if($row['sklad']!=1)
				{	 
					 $fp=round($row['price']/10);
	
					 $firstcreditpunch='
					 <span class="addtochart buyincredit" id="credit_'.$row['id'].'">
					<input class="creditinput" rel="'.$row['id'].'" type="button" value="� ������" >
					<span class="firstcreditpunch"> '.$fp.' ���. ������ �����</span>';
					 
					 $this->set('firstcreditpunch',$firstcreditpunch);
					 
				}	 
				else	  $this->set('firstcreditpunch','');
*/
                // ����� ������
                $this->checkStore($row);
				//echo time().'<br/>';
                // ����� ������
                //$this->option_select($row);
                // �������� ������
                //$this->setHook(__CLASS__, __FUNCTION__, $row);
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                // ���������� ������ ������ ������
                $dis = ParseTemplateReturn($this->getValue('templates.main_product_forma_' . $this->cell));


                // ������� ��������� ����������� � �����
                if ($item == $total)
                    $this->setka_footer = false;

                $cell_name = 'd' . $j;
                $$cell_name = $dis;

                if ($j == $this->cell) {
                    $table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
                    $j = 0;
                } elseif ($item == $total) {
                    $table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                }

                $j++;
                $item++;
            }
        }

        $this->lastmodified = $lastmodified;
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
		return $hook;
	}
	
	function stihl_catalog_settings($row) {

			//��� �������� stihl ������������ ������ ��������

        $stihl_catalog_search=false;
		$viking_catalog_search=false;
		$sql="select id,name from ".$GLOBALS['SysValue']['base']['categories']." where name like '%stihl%' or name like '%viking%'";
		$PHPShopOrm = new PHPShopOrm();
		$PHPShopOrm->cache = true;
		$PHPShopOrm->debug = $this->debug;
		$PHPShopOrm->sql=$sql;
		$res=$PHPShopOrm->select();

	   foreach ($res as $catalog_id_rows) {		
        	if ($row['category']==$catalog_id_rows[id]) {
				if (preg_match("/stihl/i", $catalog_id_rows[name])) {
					$stihl_catalog_search=true;
				}
				if (preg_match("/viking/i", $catalog_id_rows[name])) {
					$viking_catalog_search=true;
				}
        	}
        }
//echo $stihl_catalog_search.$viking_catalog_search;
		if ($stihl_catalog_search==true || $viking_catalog_search==true) {
            $this->set('ComStartNotice', null);
            $this->set('ComEndNotice', null);
		} else {
			if (empty($row['sklad'])) {
				$this->set('ComStartNotice', '<!--');
				$this->set('ComEndNotice', '-->');
			}
		}
	}
	
	function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"];
		 }
		 return $pageURL;
	}
}

?>