<?php

/**
 * ���������� ������ �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSearch 
 * @version 1.3
 * @package PHPShopShopCore
 */
class PHPShopSearch extends PHPShopShopCore {

    /**
     * ����� �������
     * @var int 
     */
    var $cell = 1;
    var $line = false;
    var $debug = false;
    var $cache = false;
    var $grid = false;
    var $empty_index_action = false;
    
    function PHPShopSearch() {

        // ������ �������
        $this->action = array("post" => "words", "get" => "words", "nav" => "index");
    	parent::PHPShopShopCore();
    }

    /**
     * ����� �� ���������, ����� ����� ������
     */
    function index() {

        $this->category_select();

        $this->set('searchSetA', 'checked');
        $this->set('searchSetC', 'checked');

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__);

        if (isset($_REQUEST['ajax']))
        	exit();
        
        // ���������� ������
        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    /**
     * ��������� SQL ������� �� �������� ��������� � ���������
     * ������� �������� � ��������� ���� query_filter.php
     * @return mixed
     */
    function query_filter($where = false) {

        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * ����������� ������������ ������ ������������
     * @param int $cat �� ��������
     * @param string $parent_name ������� ���� ���������
     * @return bool
     */
    function subcategory($cat, $parent_name = false) {
        if (!empty($this->ParentArray[$cat]) and is_array($this->ParentArray[$cat])) {
            foreach ($this->ParentArray[$cat] as $val) {

                $name = $this->PHPShopCategoryArray->getParam($val . '.name');
                $sup = $this->subcategory($val, $parent_name . ' / ' . $name);
                if (empty($sup)) {

                    // ���������� �������� ��������
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
            //���������� �������� ��������
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
     * ����� ��������� ��� ������
     */
    function category_select() {

            $this->value[] = array(__('��� �������'), 0, false);
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
        

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $this->ParentArray);
    }

    /**
     *  ����� ��������
     */
    function sort_select() {
        if (PHPShopSecurity::true_param(@$_REQUEST['v'], @$_REQUEST['cat']))
            if (is_array($_REQUEST['v'])) {
                PHPShopObj::loadClass('sort');
                if (PHPShopSecurity::true_num($_REQUEST['cat'])) {
                    $PHPShopSort = new PHPShopSort($_REQUEST['cat']);
                    $this->set('searchPageSort', $PHPShopSort->display());

                    // �������� ������
                    $this->setHook(__CLASS__, __FUNCTION__, $PHPShopSort);
                }
            }
    }

    /**
     * ����� ������ �� �������
     */
    function words() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $_REQUEST, 'START'))
            return true;

        // ������
        $this->set('productValutaName', $this->currency());

        // ��������� ������
        $this->category_select();

        // ������� ������
        $this->sort_select();

        // ������� ����� � Ajax �������
        if (isset($_REQUEST['ajax']))
        	$_REQUEST['words'] =  iconv('utf-8//IGNORE//TRANSLIT','cp1251', urldecode($_REQUEST['words']));
        //echo $_REQUEST['words'];
        // ������ ������
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
            // ������� ������
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->debug=false;
            $this->PHPShopOrm->mysql_error = false;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
			//print_r($this->dataArray);
            if (!empty($this->dataArray)) {

                // ���������
                $this->setPaginator(count($this->dataArray), $order);

                // Ajax Search

                if (isset($_REQUEST['ajax'])) {
	               	foreach ($this->dataArray as $row) {
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
                	// ��������� � ������ ������ � ��������
                	$grid=$this->product_grid($this->dataArray, $this->cell, $template=false, $this->line);
                	$this->add($grid, true);
                }
	                
            }
            else {
            	if (isset($_REQUEST['ajax'])) {
            		//echo 'false';
            		exit();
            	}
            	 
                $this->add(PHPShopText::h3(__('������ �� �������')), true);
            }
            // ������ � ������
            $this->write($this->get('searchString'), @$this->num_page, @$_REQUEST['cat'], @$_REQUEST['set']);

            // �������� ������
            $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');
        }
        if (isset($_REQUEST['ajax'])){
        	//echo 'false';
        	exit();
        }
        
        // ���������� ������
        $this->parseTemplate($this->getValue('templates.search_page_list'));
    }

    /**
     * ������ � ������ ������
     */
    function write($name, $num, $cat, $set) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.table_name18'));
        $PHPShopOrm->debug = $this->debug;

        // �������� ������
        $arg = func_get_args();
        $this->PHPShopModules->setHookHandler(__CLASS__, __FUNCTION__, $this, $arg);

        $PHPShopOrm->insert(array('name_new' => $name, 'num_new' => $num, 'datas_new' => time(), 'cat_new' => $cat, 'dir_new' => $_SERVER['HTTP_REFERER']));
    }

    /**
     * ��������� ����������
     */
    function setPaginator($count) {

        // ���-�� ������
        $this->count = $count;

        if (is_array($this->search_order)) {
            $SQL = " where enabled='1' and parent_enabled='0' and " . $this->search_order['string'] . " " . $this->search_order['sort'] . "
                 " . $this->search_order['prewords'] . " " . $this->search_order['sortV'];
        }else
            $SQL = null;


        // ����� �������
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

            // �������� ������
            $this->setHook(__CLASS__, __FUNCTION__, $nav);
        }
    }

    /**
     * ����� ����� � ��������
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null, $d6 = null, $d7 = null) {

        // �������� ������, ��������� � ������ ������� ������ ��� �����������
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
     * ��������� ����� �������
     * @param array $dataArray ������ ������
     * @param int $cell ������ ����� [1-5]
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
    
    	// �����������
    	$this->set('productSale', $this->lang('product_sale'));
    	$this->set('productInfo', $this->lang('product_info'));
    	$this->set('productPriceMoney', $this->dengi);
    	$this->set('catalog', $this->lang('catalog'));
    	$this->set('productPageThis', $this->PHPShopNav->getPage());
    
    	$d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
    	if (is_array($dataArray)) {
    		$total = count($dataArray);
    		// �������� ����������� �����
    		//if ($total < $cell)
    			//$this->grid = false;
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
    
    			if(empty($template))
    				$template=$this->getValue('templates.main_product_forma_' . $this->cell);
    
    			// ���������� ������ ������ ������
    			$dis = $this->ParseTemplateReturn($template);
    			//$this->ParseTemplateReturn($template);
    			//echo $template;
    			// ������� ��������� ����������� � �����
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