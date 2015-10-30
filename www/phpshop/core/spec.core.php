<?php

/**
 * ���������� ��������������� �������
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
     * �����������
     */
    function PHPShopSpec() {

        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    /**
     * ����� ������ �������
     */
    function index() {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // ���� ��� ���������
        $this->objPath = './spec_';

        // ������
        $this->set('productValutaName', $this->currency());

        $this->set('catalogCategory', $this->lang('specprod'));

        // ���������� �����
        if (empty($this->cell))
            $this->cell = $this->PHPShopSystem->getValue('num_vitrina');
        $this->where=array('spec' => "='1'", 'enabled' => "='1'");//"spec='1'";
        // ������ ����������
        $order = $this->query_filter("spec='1'");

        // ������� ������
        if (is_array($order)) {
            $this->dataArray = parent::getListInfoItem(array('*'), $this->where, $order, __CLASS__, __FUNCTION__);
        } else {
            // ������� ������
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
        }

        // ���������
        if (is_array($order))
            $this->setPaginator(count($this->dataArray));
        //print_r($this->dataArray);
        // ��������� � ������ ������ � ��������
        $grid = $this->product_grid_spec($this->dataArray, $this->cell,false,false);
        
        if (empty($grid)) {
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        }
        $this->add($grid, true);

        // ���������
        $this->title = $this->lang('specprod') . " - " . $this->PHPShopSystem->getParam('title');

        //$this->set('catalogContent','test',true);
        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.product_page_spec_list'));
    }
    /**
     * ��������� ����� �������
     * @param array $dataArray ������ ������
     * @param int $cell ������ ����� [1-5]
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
    			$this->parseTemplate($template,false);
                        //echo $this->Disp;
    			//$this->ParseTemplateReturn($template);
    			//echo $template;
    			// ������� ��������� ����������� � �����
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

}

?>