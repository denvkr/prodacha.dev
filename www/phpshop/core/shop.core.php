<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/config_functions.php');

//���������� ������ ��� ��������� ����������
if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    setlocale(LC_ALL, 'rus'); 
    else
    setlocale(LC_ALL, 'ru_RU.CP1251'); 
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
/**
 * ���������� �������
 * @author PHPShop Software
 * @version 1.6
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShop
 * @package PHPShopShopCore
 */

//������ ��� ������ �������
//include $_SERVER['DOCUMENT_ROOT']."/catalog_product_icons.php";

class PHPShopShop extends PHPShopShopCore {

    /**
     * ����� �������
     * @var bool
     */
    var $debug = false;

    /**
     * ����� ����������� ������� ��, ������������� ��� ����� ����� true
     * @var bool
     */
    var $cache = true;

    /**
     * ����� ����� ��, ��������� �� ���� ��� ����������� ������, �������������  array('content','yml_bid_array')
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');

    /**
     * ������������ ����� ������ �������/��������� �� �������� ��� ����������� ������, ������������� �� ����� 100
     * @var int
     */
    var $max_item = 500;

    /**
     * ��� ������� ������� ������ �������� ������������� ������
     * @var string 
     */
    var $sort_template = null;
    var $ed_izm = null;

    var $first_time_catalog=null;

    /**
     * ��������� ������ �� ����������������� ����� menu-lvl3-href-modify_catalog_add-analog.txt
     * @var array
     */

    private $custom_menu_3=array();
    
    /**
     * ��������� ������ �� ����������������� ����� menu-column-qty_catalog_change.txt
     * @var array
     */
    private $custom_menu_count=array();
    
    private $CustomCatalogIDArray1;//=array(473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,509,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    private $CustomCatalogIDArray2;//=array(5,9,16,18,30,31,32,33,34,35,36,37,38,44,60,62,77,81,85,88,96,97,98,99,120,121,122,123,134,141,142,143,154,172,186,190,191,201,211,215,224,227,228,234,247,248,249,254,252,256,257,258,270,272,288,290,292,293,295,297,298,299,300,332,333,334,335,336,337,350,351,352,353,354,355,356,382,414,415,416,418,419,420,421,422,423,424,425,436,437,440,442,459,461,464,465,467,503,509,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    private $CustomCatalogIDArray2_1;//=array(5,9,16,18,30,31,32,33,34,35,36,37,38,44,60,62,77,81,85,88,96,97,98,99,120,121,122,123,134,141,142,143,154,172,186,190,191,201,211,215,224,227,228,234,247,248,249,254,252,256,257,258,270,272,288,290,292,293,295,297,298,299,300,332,333,334,335,336,337,350,351,352,353,354,355,356,382,414,415,416,418,419,420,421,422,423,424,425,436,437,440,442,459,461,464,465,467,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,509,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    private $CustomCatalogIDArray2_2;//=array(30,31,32,33,34,35,81,96,121,141,154,186,190,191,201,227,333,334,382,414,415,416,418,419,420,421,422,423,424,425,431,432,436,433,437,440,442,459,461,464,465,467,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    private $CustomCatalogIDArray3;//=array(472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,509,536,538,543,545,549,551,555,562,581,585,586,591,594,595);
    private $id_excluded=array();
    /**
     * �����������
     */
    function PHPShopShop() {

        // ����������
        $this->path = '/' . $GLOBALS['SysValue']['nav']['path'];

        // ������ �������
        $this->action = array("nav" => array("CID", "UID"));
        parent::PHPShopShopCore();

        $this->PHPShopOrm->cache_format = $this->cache_format;

        $this->page = $this->PHPShopNav->getPage();
        if (strlen($this->page) == 0)
            $this->page = 1;
        
        include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/core_catalog_config.php');
        $this->CustomCatalogIDArray1=$CustomCatalogIDArray1;
        //var_dump($this->CustomCatalogIDArray1);
        $this->CustomCatalogIDArray2=$CustomCatalogIDArray2;
        //var_dump($this->CustomCatalogIDArray2);
        $this->CustomCatalogIDArray2_1=$CustomCatalogIDArray2_1;
        //var_dump($this->CustomCatalogIDArray2_1);
        $this->CustomCatalogIDArray2_2=$CustomCatalogIDArray2_2;
        //var_dump($this->CustomCatalogIDArray2_2);
        $this->CustomCatalogIDArray3=$CustomCatalogIDArray3;
        //var_dump($this->CustomCatalogIDArray3);
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
     * ��������� �������� �������� � ����
     */
    function setActiveMenu() {

        $this->set('thisCat', $this->PHPShopCategory->getParam('parent_to'));

        // ������� ������� ��������
        $cat = $this->get('thisCat');
        if (empty($cat))
            $this->set('thisCat', intval($this->PHPShopNav->getId()));

        // ���� 3� ����������� ��������
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

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $data);
    }

    /**
     * ������������� ����� ������
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
                $this->set('productFiles', __("��� ������"));
            }
        } else
            $this->set('productFiles', __("����� ����� �������� ������ ����� ������"));

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ������ �����
     * @param array $row ������ ������
     */
    function cloud($row) {
        global $PHPShopCloudElement;

        $disp = $PHPShopCloudElement->index($row);
        $this->set('cloud', $disp);

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ������������� ������ ������
     * @param string $pages
     */
    function article($row) {

        // �������� ������
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

                // �������� ������
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // ���������� ������
                $dis.=ParseTemplateReturn($this->getValue('templates.product_pagetema_forma'));
            }

            if (!empty($dis)) {
                $this->set('temaContent', $dis);
                $this->set('temaTitle', __('������ �� ����'));

                // ��������� ��������� � ������
                $this->set('pagetemaDisp', ParseTemplateReturn($this->getValue('templates.product_pagetema_list')));
            }
        }

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    }

    /**
     * ����� �������� �������
     * ������� �������� � ��������� ���� rating.php
     * @return mixed
     */
    function rating($row) {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ����� �������� �����������
     * ������� �������� � ��������� ���� image_gallery.php
     * @return mixed
     */
    function image_gallery($row) {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ����� ����� �������
     * ������� �������� � ��������� ���� option_select.php
     * @return mixed
     */
    function option_select($row) {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ����� ������� ��������� ���������� ��� ������� ���������� ��������� UID
     */
    function UID() {
    	//echo 'UID';
        // �������� ������ � ������ �������
        if ($this->setHook(__CLASS__, __FUNCTION__, null, 'START'))
            return true;
		
        // ������������
        if (!PHPShopSecurity::true_num($this->PHPShopNav->getId()))
            return $this->setError404();

        // ������� ������
        $row = parent::getFullInfoItem(array('*'), array('id' => "=" . $this->PHPShopNav->getId(), 'enabled' => "='1'", 'parent_enabled' => "='0'"), __CLASS__, __FUNCTION__);

        // 404 ������
        if (empty($row['id']))
            return $this->setError404();
        
        // ���������
        $this->category = $row['category'];
        $this->PHPShopCategory = new PHPShopCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        // 404 ������ ����������
        if ($this->errorMultibase($this->category))
            return $this->setError404();

        // ������� ���������
        if (empty($row['ed_izm']))
            $ed_izm = $this->ed_izm;
        else
            $ed_izm = $row['ed_izm'];

        // ������������� �����
        $this->file($row);

        // ������ �����
        $this->cloud($row);

        // �����������
        $this->image_gallery($row);

        // ������� �������������
        $this->sort_table($row);

        // ����� ������
        $this->option_select($row);

        // �������
        $this->rating($row);

        // �������� ������ Multibase
        $this->checkMultibase($row['pic_small']);

		//nah
	
		if($row['sklad']!=1)
		{	
			//$fp=round($row['price']/10);
			//������� ������  style="left:-50px;"
			$firstcreditpunch='
			<span class="addtochart buyincredit2" id="credit_'.$row['id'].'">
									<input class="creditinputcart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="� ������">';
			$this->set('firstcreditpunch',$firstcreditpunch);
			//������� ������� �����
			/*$FastOrder='<span class="addtochart fast_order1" id="fast_order_'.$row['id'].'">
			 <input class="fast_ordercart" rel="'.$row['id'].'" cnt="n'.$row['id'].'" type="button" value="'.$this->lang('fast_order').'">
			</span>';
			*/
			//$sdvig_vlevo='left:-50px;';
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
        
        // ����� ������
        $this->checkStore($row);
        
        // ������ �� ����
        $this->article($row);

        // �������
        $this->parent($row);
        
        // �������� ������ � �������� �������
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');
        
        $this->product_icons($row);
      
    	//echo '2';
        // ���������� ������
        $this->add(ParseTemplateReturn($this->getValue('templates.main_product_forma_full')), true);

        // ���������� ������
        $this->odnotip($row);

        // ������ ������������ ���������
        $cat = $this->PHPShopCategory->getValue('parent_to');
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array(
                'name' => '�������',
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

        // ��������� �������� �������� � ����
        $this->setActiveMenu();

        // ��������� ������� ������ ��� ����� ��������
        $this->navigation($this->category, $row['name']);

        // ���� ���������
        $this->set_meta(array($row, $this->PHPShopCategory->getArray(), $parent_category_row));
        $this->lastmodified = $row['datas'];

        //if ($this->get('productName')=='�������� ���-3-DK7 Lander (������)') {
        //$this->set('test',$mod_price);
        //}
        //$this->add('test');
        // �������� ������ � ����� �������
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.product_page_full'));

    }

    /**
     * ����-����
     * @param array $row ������
     */
    function set_meta($row) {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }

    /**
     * ���������� ������
     * @param array $row ������ ������
     */
    function odnotip($row) {
        global $PHPShopProductIconElements;

        $this->odnotip_setka_num = 1;
        $this->line = false;
        $this->template_odnotip = 'main_spec_forma_icon';

        $UIDProductName=$row['name'];
        // �������� ������ � ������ �������
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

        // ������ ��� �������
        if (is_array($odnotip))
            foreach ($odnotip as $value) {
                if (!empty($value))
                    $odnotipList.=' id=' . trim($value) . ' OR';
            }

        $odnotipList = substr($odnotipList, 0, strlen($odnotipList) - 2);

        // ����� �������� �������� �� ������
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 2)
            $chek_items = ' and items>0';
        else
            $chek_items = null;

        if (!empty($odnotipList)) {

            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $result = $PHPShopOrm->query("select * from " . $this->objBase . " where (" . $odnotipList . ") " . $chek_items . " and  enabled='1' and parent_enabled='0' and sklad!='1' order by num");
            //�� ������������ ������ ��������� ���-�� ������� ��� �������������� � ������� ��� ������ � ������� <...> �� ������ ����������
            //$cnt=0;
            while ($row = mysql_fetch_assoc($result)) {
                //if ($cnt==6) break;
                $data[] = $row;
                //$cnt++;
            }

            // ����� �������
            if (!empty($data) and is_array($data))
                $disp = $PHPShopProductIconElements->seamply_forma($data, $this->odnotip_setka_num, $this->template_odnotip, $this->line);
        }

        if (!empty($disp)) {
            // ������� � ����������� �����
            if (PHPShopParser::check($this->getValue('templates.main_product_odnotip_list'), 'productOdnotipList')) {
                $this->set('productOdnotipList', $disp);
                $this->set('productOdnotip', __('������������� ������'));
                $this->set('UIDProductName', $UIDProductName);
            } else {
                // ������� � ������ �������
                $this->set('specMainTitle', __('������������� ������'));
                $this->set('specMainIcon', $disp);
            }

            // �������� ������ � �������� �������
            $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

            $odnotipDisp = ParseTemplateReturn($this->getValue('templates.main_product_odnotip_list'));
            $this->set('odnotipDisp', $odnotipDisp);
        }
        // ������� ��������� �������
        else {
            $this->set('specMainIcon', $PHPShopProductIconElements->specMainIcon(true, $this->category));
        }

        // �������� ������ � ����� �������
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    }

    /**
     * ����� �������� �������
     * @param array $row ������ ��������
     */
    function parent($row) {

        // �������� ������ � ������ �������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row, 'START'))
            return true;

        $select_value = array();
        $row['parent'] = PHPShopSecurity::CleanOut($row['parent']);

        if (!empty($row['parent'])) {
            $parent = explode(",", $row['parent']);

            // ������� ���������� � ������� �������� ������
            $this->set('ComStartCart', '<!--');
            $this->set('ComEndCart', '-->');

            // �������� ������ �������
            if (is_array($parent))
                foreach ($parent as $value) {
                    if (PHPShopProductFunction::true_parent($value))
                        $Product[$value] = $this->select(array('*'), array('uid' => '="' . $value . '"', 'enabled' => "='1'", 'sklad' => "!='1'"), false, false, __FUNCTION__);
                    else
                        $Product[intval($value)] = $this->select(array('*'), array('id' => '=' . intval($value), 'enabled' => "='1'"), false, false, __FUNCTION__);
                }

            // ���� �������� ������
            if (!empty($row['price'])) {
                $select_value[] = array($row['name'] . " -  (" . $this->price($row) . "
                    " . $this->get('productValutaName') . ')', $row['id'], false);
            }


            // ���������� ������ �������
            if (is_array($Product))
                foreach ($Product as $p) {
                    if (!empty($p)) {

                        // ���� ����� �� ������
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

            // �������� ������ � ����� �������
            $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
        }
    }

    /**
     * ����� ������� ��������� ���������� ��� ������� ���������� ��������� CID
    */
    function CID() {
        include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/core_catalog_config.php');
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
        // ID ���������
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopCategory(intval($this->category));
        $this->category_name = $this->PHPShopCategory->getName();

        // ������ �� �����������
        if (in_array(intval($this->category),$this->CustomCatalogIDArray1)) {
                $where=call_user_func_array('buildwheresql', array($this->category,1,0));
                //echo $where;
        	$this->PHPShopOrm->sql = 'select '.intval($this->category).' as id,name,num,'.intval($this->category).' as parent_to,yml,num_row,num_cow,sort,content,vid,name_rambler,servers,title,title_enabled,title_shablon,descrip,descrip_enabled,descrip_shablon,keywords,keywords_enabled,keywords_shablon,skin,skin_enabled,order_by,order_to,secure_groups,content_h,filtr,icon_description from ' . $this->getValue('base.categories').$where.' LIMIT 1';
        	//echo $this->PHPShopOrm->sql;
        	$this->PHPShopOrm->debug = $this->debug;
        	$parent_category_row = $this->PHPShopOrm->select();

        	// �������� ������
        	$this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'MIDDLE');

        	// ���� ��������
        	$this->CID_Category();

       		// �������� ������
        	$this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'END');
       	      	
        } else {
        	$parent_category_row = $this->select(array('*'), array('parent_to' => '=' . $this->category), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories')));

        	// �������� ������
        	$this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'MIDDLE');

        	// ���� ������
        	if (empty($parent_category_row['id'])) {
        		
        		$this->CID_Product();
                        //echo 'product';
        	}
        	// ���� ��������
        	else {
        	
        		$this->CID_Category();
                        //echo 'category';        	
        	}
        	
        	// �������� ������
        	$this->setHook(__CLASS__, __FUNCTION__, $parent_category_row, 'END');
        	 
        }
				
    }

    /**
     * ��������� SQL ������� �� �������� ��������� � ���������
     * ������� �������� � ��������� ���� query_filter.php
     * @return mixed
     */
    function query_filter() {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if (!empty($hook))
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * ����� ������� ������������� ������
     * ������� �������� � ��������� ���� sort_table.php
     * @param array $row ������ ��������
     * @return mixed
     */
    function sort_table($row) {

        // �������� ������
        if ($this->setHook(__CLASS__, __FUNCTION__, $row))
            return true;

        $this->doLoadFunction(__CLASS__, __FUNCTION__, $row);
    }
	
    /**
     * ����� ������ �������
     * @param $category
     */
    function CID_Product($category = null) {

        if (!empty($category))
            $this->category = intval($category);

        // �������� ������ � ������
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;
        
        // 404 ���� �������� �� ����������
        if(empty($this->category_name))
            return $this->setError404();
		//if ($this->category==15) echo $this->category;
        // ���� ��� ���������
        $this->objPath = './CID_' . $this->category . '_';

        // ������
        $this->set('productValutaName', $this->currency());

        // ���������� ����� ��� ������ ������
        $cell = $this->PHPShopCategory->getParam('num_row');

        // ������ ����������
        $order = $this->query_filter();
        //var_dump($order);
        // ���-�� ������� �� ��������
        $num_cow = $this->PHPShopCategory->getParam('num_cow');
        if (!empty($num_cow))
            $this->num_row = $num_cow;
        // ������� ������
        if (is_array($order)) {
        	//print_r($order);
        	$this->dataArray = parent::getListInfoItem(false, false, false, __CLASS__, __FUNCTION__, $order['sql']);            
            // ���������
            $this->setPaginator(count($this->dataArray), $order['sql']);
        } else {
            // ������� ������
            $this->PHPShopOrm->sql = "select *, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from " . $this->SysValue['base']['products'] . " where " . $order;
            $this->PHPShopOrm->debug = $this->debug;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
            // ���������
            $this->setPaginator(count($this->dataArray), $order);
        }
		//echo $this->PHPShopOrm->sql;
        // ��������� � ������ ������ � ��������
        $grid = $this->product_grid($this->dataArray, $cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // ������������ ���������
        $cat = $this->PHPShopCategory->getParam('parent_to');
		//echo $cat;

	// ������ ������������ ���������
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array();
        }
		//echo $this->PHPShopCategory->getName();
        //$this->set('catalogCat', $parent_category_row['name']);
		//$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)stihl(.*)$/i','$1�����$3',$this->PHPShopCategory->getName());
		//$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)viking(.*)$/i','$1������$3',$this->PHPShopCategory->getName());
		//if ($this->PHPShopNav->getId()!=135 && $this->PHPShopNav->getId()!=458) {
		//echo $this->PHPShopNav->getId();
		//$this->PHPShopCategory->objRow['name']=preg_replace('/^(.*)karcher(.*)$/i','$1������$3',$this->PHPShopCategory->getName());		
		//} else {
	$this->PHPShopCategory->objRow['name']=$this->PHPShopCategory->getName();
		//}
		//print_r($this->PHPShopCategory->objRow);	
        $this->set('catalogCategory', $this->PHPShopCategory->getName());
        $this->set('productId', $this->category);
        $this->set('catalogId', $cat);
        $this->set('pcatalogId', $this->category);

        // ������ �������
        //PHPShopObj::loadClass('sort');
        //$PHPShopSort = new PHPShopSort($this->category, $this->PHPShopCategory->getParam('sort'), true, $this->sort_template);
        //$this->set('vendorDisp', $PHPShopSort->display());
		//echo $PHPShopSort->display();
		//print_r($this->dataArray);
		//echo '-'.intval($this->dataArray['category']).'-';
		// ��������� �������� �������� � ����
	$this->setActiveMenu();
			
	// ���� ���������
	$this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));
			
	// ����������� ���������
	$this->other_cat_navigation($cat);
			
	// ��������� ������� ������ ��� ����� ��������
	$this->navigation($cat, $this->PHPShopCategory->getName());
		//echo '10';
		//echo $this->num_of_pages;
		//echo $this->PHPShopNav->getPage();
		//print_r($this->PHPShopNav);
			
	if ($this->PHPShopNav->getPage()==1) {
                if ($this->PHPShopCategory->getContent_h()!='' && $this->PHPShopCategory->getContent()!='') {
                // �������� �������� ����
                $this->set('catalogContent_h', $this->PHPShopCategory->getContent_h().'<div class="more_href page_nava">
                                            <a class="scroll" href="#more" title="������� � ���������� ��������."><span>���������</span><img id="more_arrow_img" src="..images/city_choose_arrow.png"></a>
                                            </div>');
                } else
                $this->set('catalogContent_h', $this->PHPShopCategory->getContent_h());
                // �������� �������� ���
		$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
                /*
                if ($this->PHPShopCategory->getContent_h()!='' && $this->PHPShopCategory->getContent()!='') {
                   $this->set('more_href','<div class="more_href page_nava" style="position:relative;width:100px;height:20px;clear:both;">
                                            <a class="scroll" href="#more" title="������� � ���������� ��������."><span>���������</span><img id="more_arrow_img" src="../images/city_choose_arrow.png" vspace="0" hspace="6px"></a>
                                            </div>'); 
                }
                 */
                
	}
		//echo $this->dataArray[2][category];
		//$this->stihl_catalog_settings($parent_category_row);
		//echo '$this->get(ComStartCart) '.$this->get('ComStartCart');
        // ������ �����
        $this->cloud($this->dataArray);

        // �������� ������ � ����� �������
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');
	
        // ���������� ������
        $this->parseTemplate($this->getValue('templates.product_page_list'));

    }

    /**
     * �������������� ��������� ��������� � ������ �������
     * @param Int $parent �� �������� ���������
     */
    function other_cat_navigation($parent) {

        // �������� ������ � ������ �������
        $this->setHook(__CLASS__, __FUNCTION__, $parent, 'START');

        // ��� ��������
        $dis = PHPShopText::h1($this->get('catalogCat'));

        $dataArray = array();
        $dis = null;

        // ������������� ����������� ����
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
        // ������� ������ �� �� ��� ���������� ������ � ����
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

        // �������� ������ � ����� �������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $parent, 'END');
        if ($hook)
            return true;

        $this->set('DispCatNav', $dis);
    }

    /**
     * ����� ������ ���������
     */
    function CID_Category() {
    	//$this->CustomCatalogIDArray1=array(473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,509,536,538,543,545,549,551,555,562,581,585,586,596);
        //$this->CustomCatalogIDArray2=array(5,9,16,18,30,31,32,33,34,35,36,37,38,44,60,62,77,81,85,88,96,97,98,99,120,121,122,123,134,141,142,143,154,172,186,190,191,201,211,215,224,227,228,234,247,248,249,254,252,256,257,258,270,272,288,290,292,293,295,297,298,299,300,332,333,334,335,336,337,350,351,352,353,354,355,356,382,414,415,416,418,419,420,421,422,423,424,425,436,437,440,442,459,461,464,465,467,503,509,536,538,543,545,549,551,555,562,581,585,586,596);
        //$this->CustomCatalogIDArray2_1=array(5,9,16,18,30,31,32,33,34,35,36,37,38,44,60,62,77,81,85,88,96,97,98,99,120,121,122,123,134,141,142,143,154,172,186,190,191,201,211,215,224,227,228,234,247,248,249,254,252,256,257,258,270,272,288,290,292,293,295,297,298,299,300,332,333,334,335,336,337,350,351,352,353,354,355,356,382,414,415,416,418,419,420,421,422,423,424,425,436,437,440,442,459,461,464,465,467,472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,509,536,538,543,545,549,551,555,562,581,585,586,596);
        //$this->CustomCatalogIDArray3=array(472,473,474,475,476,477,478,479,480,481,482,483,484,485,486,487,488,489,490,491,492,493,494,495,496,497,498,499,500,501,502,503,509,536,538,543,545,549,551,555,562,581,585,586,596);
        include_once($_SERVER['DOCUMENT_ROOT'] . '/custom_config/core_catalog_config.php');
        //echo 'CID_Category'.$this->PHPShopNav->getId();
    	$this->id_excluded=array();
    	    	
        // �������� ������ � ������ �������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook)
            return true;

        // ID ���������
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopCategory($this->category);

        // ������� �������
        if ($this->PHPShopCategory->getParam('skin_enabled') == 1)
            return $this->setError404();

        // �������� ���������
        $this->category_name = $this->PHPShopCategory->getName();
        //echo $this->PHPShopCategory->getName();

        //������ ������ �� ����� ������������ �� ����������������� ����
        $this->custom_menu_3=array();
        $this->custom_menu_3=custom_menu_1($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-lvl3-href-modify_catalog_add-analog.txt');
        $this->custom_menu_count=array();
        $this->custom_menu_count=custom_menu_count($_SERVER['DOCUMENT_ROOT'] . '/custom_config/menu-column-qty_catalog_change.txt');

        //�������� ������ �� ������������ ��������, ������� ���������� ������� ���������� phpshop
        $where=call_user_func_array('buildwheresql', array($this->PHPShopNav->getId(),2,$this->category));
        
        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }
        // ������� ������
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.categories'));
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $this->cache;
        $dis = null;
        //��������� ��� ��������� ������ �������� �������� ��� ������ ���������� �������� ���� name_rambler
        if (in_array(intval($this->PHPShopNav->getId()),$this->CustomCatalogIDArray1)) 
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
                        $custom_menu_count_item_id=0;
                        $custom_menu_count_item_cnt=4;			
			foreach ($dataArray as $row) {
				//������� ������� � ��� ��������� ��� ������, �� ���� � �� ��������, �� �������������
				if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray2)) {
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0) {
						$cnt_by_type++;
					}
					if (preg_match('/^.*\(.*\).*$/i',$row['name'])) {
						$cnt_by_maker++;
					}
				//���������� ��� ��������� ������� ������ ������� �� ����
				} else if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray3)) {
						$cnt_by_type++;
				}			
			}
                        //������ ������ ������� ������� � ���� ���� �������� ��� ������� ��������
                        if (is_array($this->custom_menu_count)){
                            foreach ($this->custom_menu_count as $custom_menu_count_item){
                                if ($this->PHPShopNav->getId()==$custom_menu_count_item['id']) {
                                    $custom_menu_count_item_id=$custom_menu_count_item['id'];
                                    $custom_menu_count_item_cnt=$custom_menu_count_item['cnt']; 
                                }
                            }
                        }
                        //�������� ������ �� ����
			foreach ($dataArray as $row) {
                            //���������� ��� ���������� ����������������� ������ ����: ����� (������)
                            $id_custom_menu_3_true=false;
                            $id_custom_menu_3_false=false;
                            $dis.=PHPShopText::li($row['name'], $this->path . '/CID_' . $row['id'] . '.html');
                            //if ($row['id']=76) echo $row['name'];
                            //�������� ������� ��� ��������� �� ������� $CustomCatalogIDArray2
                            if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray2)) {

                                    //�� ���� ������� ���� ���� ��� ������ � ���� ����� menu-lvl3-href-modify_catalog_add-analog.txt
                                    if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0) {
                                            foreach ($this->custom_menu_3 as $custom_menu_3_item) {
                                                    //������ ����������
                                                    if (in_array($row['id'],$custom_menu_3_item)===true) {
                                                            if ($custom_menu_3_item['id']==$row['id']) {
                                                                    $custom_href=$custom_menu_3_item['href'];
                                                                    $custom_css_option_width=$custom_menu_3_item['css_option_width'];
                                                                    $id_custom_menu_3_true=true;
                                                                    $catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false).$custom_href;
                                                                    array_push($catalog_items_by_type_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
                                                                    array_push($this->id_excluded,$custom_menu_3_item['sub_id']);
                                                                    $cnt1++;
                                                                    $cnt_cur_row_by_type++;
                                                                    break;
                                                            }
                                                    } else if (in_array($row['id'],$custom_menu_3_item)===false) {
                                                            $id_custom_menu_3_false=true;
                                                    }
                                            }
                                            if ($id_custom_menu_3_true===false && $id_custom_menu_3_false===true && in_array($row['id'],$this->id_excluded)===false){
                                                $catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
                                                array_push($catalog_items_by_type_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
                                                $cnt1++;
                                                $cnt_cur_row_by_type++;
                                            }
                                    }
                            //���������� ��� 472 �������� �� ������������� ������ $CustomCatalogIDArray3
                            } else if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray3)) {
                                $catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
                                array_push($catalog_items_by_type_table_td,$catalog_items_content);
                                $cnt1++;
                                $cnt_cur_row_by_type++;
                            }
                            //print_r($catalog_items_by_type_table_td);
                            //�� ���� ��� �������� �� ������ $CustomCatalogIDArray2
                            if (preg_match('/^.*\(.*\).*$/i',$row['name'])==0 || in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray2)) {
                                $cnt_catalog_items_by_type_table_td=count($catalog_items_by_type_table_td);
                                $cnt_catalog_items_by_type_table_td++;
                                //echo '$cnt1='.$cnt1.'<br>';
                                //echo '$cnt_by_type='.$cnt_by_type.'<br>';
                                //echo '$cnt_catalog_items_by_type_table_td='.$cnt_catalog_items_by_type_table_td.'<br>';
                                //echo '$custom_menu_count_item_cnt='.$custom_menu_count_item_cnt.'<br>';
                                //echo '$cnt1%$custom_menu_count_item_cnt='.$cnt1%$custom_menu_count_item_cnt.'<br>';
                                //echo '$cnt_cur_row_by_type==$cnt_by_type'.$cnt_cur_row_by_type.' '.$cnt_by_type.'<br>';
                                //if ( $cnt_by_type>$cnt_catalog_items_by_type_table_td && ($cnt_by_type-$cnt_catalog_items_by_type_table_td)==1 ){
                                   //echo $custom_menu_count_item_cnt.' '.$cnt_by_type.' '.count($catalog_items_by_type_table_td).' '.$cnt_cur_row_by_type.' '.$cnt1.'<br>';
                                   //$custom_menu_count_item_cnt=$cnt_cur_row_by_type;
                                   //$cnt_cur_row_by_type=$cnt_by_type;
                                //}
                                //
                                ///��������� ������ � �������
                                if ($cnt1%$custom_menu_count_item_cnt==0 || $cnt_cur_row_by_type==$cnt_by_type || ($cnt_by_type-$cnt_cur_row_by_type)==1) {
                                    $catalog_items_table1.=PHPShopText::tr2($catalog_items_by_type_table_td,$custom_menu_count_item_cnt);
                                    //echo $catalog_items_table1;
                                    //$catalog_items_table1[]=$catalog_items_by_type_table_td;
                                    $catalog_items_by_type_table_td=array();
                                    $cnt1=0;
                                }
                            }
                        }

			//�������� ������� �� �������������
			foreach ($dataArray as $row) {
				
                            //�� �������������
                            if (preg_match('/^.*\(.*\).*$/i',$row['name'])) {
                                    $catalog_items_content=PHPShopText::a($this->path . '/CID_' . $row['id'] . '.html', $row['name'],false,false,false,false,false);
                                    array_push($catalog_items_by_maker_table_td,$catalog_items_content);//PHPShopText::td($catalog_items_content,false,false,false)
                                    $cnt2++;
                                    $cnt_cur_row_by_maker++;
                            }

                            if ($cnt2%$custom_menu_count_item_cnt==0 || $cnt_cur_row_by_maker==$cnt_by_maker) {
                                    $catalog_items_table2.=PHPShopText::tr2($catalog_items_by_maker_table_td,$custom_menu_count_item_cnt);
                                    $catalog_items_by_maker_table_td=array();
                                    $cnt2=0;
                            }
                            $custom_menu_count_item_id=0;
                            $custom_menu_count_item_cnt=4;
                             
                        }
                        //echo $catalog_items_table1;
                        //��������� ������ $catalog_items_table1
                        //$catalog_items_table1_cnt=count($catalog_items_table1);
                        //$catalog_items_table2_cnt=count($catalog_items_table2);
                        //$catalog_items_table1_sorted=array();
                        //$catalog_items_table2_sorted=array();
                        //$catalog_items_table1_sorted_cnt=count($catalog_items_table1_sorted);
                        //$catalog_items_table1=array_slice($catalog_items_table1, $catalog_items_table1_cnt-1);
                        //var_dump($catalog_items_table1);
                        //for ($cnt_sort=0;$cnt_sort<=$catalog_items_table1_cnt;$cnt_sort++){
                            //sort($catalog_items_table1[$cnt_sort],SORT_LOCALE_STRING);
                            //for ($cnt_sub_sort=0;$cnt_sub_sort<count($catalog_items_table1[$cnt_sort]);$cnt_sub_sort++) {
                                //if (($cnt_sub_sort+1)<=count($catalog_items_table1[$cnt_sort]) && (ord(strtolower(substr($catalog_items_table1[$cnt_sort][$cnt_sub_sort],36,1)))>=ord(strtolower(substr($catalog_items_table1[$cnt_sort][$cnt_sub_sort+1],36,1)))))
                                //   $catalog_items_table1_sorted[$cnt_sort][]=$catalog_items_table1[$cnt_sort][$cnt_sub_sort+1];
                                //if (($cnt_sub_sort+1)<=count($catalog_items_table1[$cnt_sort]) && (ord(strtolower(substr($catalog_items_table1[$cnt_sort][$cnt_sub_sort],36,1)))<ord(strtolower(substr($catalog_items_table1[$cnt_sort][$cnt_sub_sort+1],36,1)))))
                                   //$catalog_items_table1_sorted[$cnt_sort][]=$catalog_items_table1[$cnt_sort][$cnt_sub_sort];
                            //}
                        //}
                        //print_r($catalog_items_table1_sorted);
                        //$catalog_items_table1='';
                        //for ($cnt_sort=0;$cnt_sort<=$catalog_items_table1_cnt;$cnt_sort++){
                        //    $catalog_items_table1.=PHPShopText::tr2($catalog_items_table1,$custom_menu_count_item_cnt);//$custom_menu_count_item_cnt
                        //}
                        if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray2_1)) {
                                $disp1=PHPShopText::table($catalog_items_table1,1,1,'center','98%',false,0,'catalog_items_table1');
                                $disp2=PHPShopText::table($catalog_items_table2,1,1,'center','98%',false,0,'catalog_items_table2');
                        } else {
                                $disp = PHPShopText::ul($dis);
                        }

        //$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
        //$disp = PHPShopText::ul($dis);
        //echo $this->category_name;
        /*
		$this->category_name=preg_replace('/^(.*)stihl(.*)$/i','$1�����$3',$this->category_name);
		$this->category_name=preg_replace('/^(.*)viking(.*)$/i','$1������$3',$this->category_name);
		$this->category_name=preg_replace('/^(.*)karcher(.*)$/i','$1������$3',$this->category_name);
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

        // ������ ������������ ��������� ��� meta
        $cat = $this->PHPShopCategory->getValue('parent_to');
        if (!empty($cat)) {
            $parent_category_row = $this->select(array('id,name,parent_to'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.categories'), 'cache' => 'true'));
        } else {
            $cat = 0;
            $parent_category_row = array(
                'name' => '�������',
                'id' => 0
            );
        }
        
        $this->set('productValutaName', $this->currency());

        // ��������� �������� �������� � ����
        $this->setActiveMenu();

        // ���� ���������
        $this->set_meta(array($this->PHPShopCategory->getArray(), $parent_category_row));

        // ��������� ������� ������ ��� ����� ��������
        $this->navigation($this->PHPShopCategory->getParam('parent_to'), $this->category_name);

        // �������� ������ � ����� �������
        $hook =$this->setHook(__CLASS__, __FUNCTION__, false, 'END');
        //if ($hook)
        //    return true;
        //print_r($this->get('catalogList'));//preg_split("/\<a.*<\/a\>/",
        //
        //
        //��������� ��������������� ������ �� ����
        $href_start_pos=0;
        $href_end_pos=0;
        $catalog_items_table1_sorted=array();
        while ($href_start_pos!==false) {
            //��������� ������
            $href_start_pos=strpos($this->get('catalogList'), '<a href');
            if ($href_start_pos===false) break;
            $href_end_pos=strpos($this->get('catalogList'), '</a>');
            //��������� �� ������� �� ��� ������
            if (substr($this->get('catalogList'),($href_end_pos+4),5)=='&nbsp') {
                $href_end_pos=strpos($this->get('catalogList'), '</a>',$href_end_pos+9);
            }
            $this_get_cataloglist_part=substr($this->get('catalogList'),$href_start_pos,(($href_end_pos+4)-$href_start_pos));
            //���� ������ �� ������� ����� ������ ����������
            $name_start_pos=strpos($this_get_cataloglist_part, '">');
            $name_end_pos=strpos($this_get_cataloglist_part, '</a>');
            $this_get_name=substr($this_get_cataloglist_part,$name_start_pos+2,($name_end_pos-($name_start_pos+2)));
            $catalog_items_table1_sorted[]=array('name'=>trim($this_get_name),'href'=>$this_get_cataloglist_part);
            //��������� ����� ������ ��� ����������� �������
            $this_get_cataloglist_part=substr($this->get('catalogList'),($href_end_pos+4));
            //��� �������� ���������� ����� ������ ���������� ���������� ������, ��� �� ����� ������
            $this->set('catalogList',$this_get_cataloglist_part);
        }
        //��������� ������
        usort($catalog_items_table1_sorted, PHPShopText::array_submenuhead2_cmp('name'));
        //var_dump($catalog_items_table1_sorted);
        //������� ��������������� ������ �� ����
        $cnt1=1;
        $catalog_items_table1='';
        $catalog_items_by_type_table_td=array();
        $catalog_items_table1_sorted_cnt=count($catalog_items_table1_sorted);
        foreach ($catalog_items_table1_sorted as $catalog_items_table1_sorted_item=>$val){
            
            array_push($catalog_items_by_type_table_td,$val['href']);
            if ($cnt1%4==0){
                $catalog_items_table1.=PHPShopText::tr2($catalog_items_by_type_table_td,4);
                //echo $catalog_items_by_type_table_td.'<br>';
                $catalog_items_by_type_table_td=array();
                $cnt_cur_row_by_type=$cnt1;
            }
            if ($catalog_items_table1_sorted_cnt%4<>0 && $cnt1==$catalog_items_table1_sorted_cnt){
                $catalog_items_table1.=PHPShopText::tr2($catalog_items_by_type_table_td,4);
                //echo $catalog_items_by_type_table_td.'<br>';
                $catalog_items_by_type_table_td=array();
            }
            $cnt1++;
        }

        //��������� ��������������� ������ �� �������������
        $href_start_pos=0;
        $href_end_pos=0;
        $catalog_items_table2_sorted=array();
        while ($href_start_pos!==false) {
            //��������� ������
            $href_start_pos=strpos($this->get('catalogList1'), '<a href');
            if ($href_start_pos===false) break;
            $href_end_pos=strpos($this->get('catalogList1'), '</a>');

            $this_get_cataloglist_part=substr($this->get('catalogList1'),$href_start_pos,(($href_end_pos+4)-$href_start_pos));
            //���� ������ �� ������� ����� ������ ����������
            $name_start_pos=strpos($this_get_cataloglist_part, '">');
            $name_end_pos=strpos($this_get_cataloglist_part, '</a>');
            $this_get_name=substr($this_get_cataloglist_part,$name_start_pos+2,($name_end_pos-($name_start_pos+2)));
            $catalog_items_table2_sorted[]=array('name'=>trim($this_get_name),'href'=>$this_get_cataloglist_part);
            //��������� ����� ������ ��� ����������� �������
            $this_get_cataloglist_part=substr($this->get('catalogList1'),($href_end_pos+4));
            //��� �������� ���������� ����� ������ ���������� ���������� ������, ��� �� ����� ������
            $this->set('catalogList1',$this_get_cataloglist_part);
        }
        //��������� ������
        usort($catalog_items_table2_sorted, PHPShopText::array_submenuhead2_cmp('name'));
        
        //var_dump($catalog_items_table1_sorted);
        //������� ��������������� ������ �� ����
        $cnt2=1;
        $catalog_items_table2='';
        $catalog_items_by_maker_table_td=array();
        $catalog_items_table2_sorted_cnt=count($catalog_items_table2_sorted);
        foreach ($catalog_items_table2_sorted as $catalog_items_table2_sorted_item=>$val){
            
            array_push($catalog_items_by_maker_table_td,$val['href']);
            if ($cnt2%4==0){
                $catalog_items_table2.=PHPShopText::tr2($catalog_items_by_maker_table_td,4);
                //echo $catalog_items_by_type_table_td.'<br>';
                $catalog_items_by_maker_table_td=array();
                $cnt_cur_row_by_maker=$cnt2;
            }
            if ($catalog_items_table2_sorted_cnt%4<>0 && $cnt2==$catalog_items_table2_sorted_cnt){
                $catalog_items_table2.=PHPShopText::tr2($catalog_items_by_maker_table_td,4);
                //echo $catalog_items_by_type_table_td.'<br>';
                $catalog_items_by_maker_table_td=array();
            }
            $cnt2++;
        }


        if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray2_1)) {
                $disp1=PHPShopText::table($catalog_items_table1,1,1,'center','98%',false,0,'catalog_items_table1');
                $disp2=PHPShopText::table($catalog_items_table2,1,1,'center','98%',false,0,'catalog_items_table2');
        } else {
                $disp = PHPShopText::ul($dis);
        }
        //������� �������� ��������� � ���������� ������� � ������������� �������� �� ����
        $this->set('catalogList', $disp1);
        //������� �������� ��������� � ���������� ������� � ������������� �������� �� �������������
        $this->set('catalogList1', $disp2);        
        $this->catalog_improvements();
		
		if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray2_1)) {
			$this->set('productId', $this->PHPShopNav->getId());
			$this->set('productPageThis', $this->PHPShopNav->getPage());
		}
		if ($this->PHPShopNav->getPage()==1) {
                        if ($this->PHPShopCategory->getContent_h()!='' && $this->PHPShopCategory->getContent()!='') {
                        // �������� �������� ����
			$this->set('catalogContent_h', $this->PHPShopCategory->getContent_h().'<div class="more_href page_nava">
                                            <a class="scroll" href="#more" title="������� � ���������� ��������."><span>���������</span><img id="more_arrow_img" src="..images/city_choose_arrow.png"></a>
                                            </div>');
                        } else
			$this->set('catalogContent_h', $this->PHPShopCategory->getContent_h());
                        // �������� �������� ���
			$this->set('catalogContent', Parser($this->PHPShopCategory->getContent()));
                        /*
                        if ($this->PHPShopCategory->getContent_h()!='' && $this->PHPShopCategory->getContent()!='') {
                           $this->set('more_href','<div class="more_href page_nava" style="position:relative;width:100px;height:20px;clear:both;">
                                                    <a class="scroll" href="#more" title="������� � ���������� ��������."><span>���������</span><img id="more_arrow_img" src="../images/city_choose_arrow.png" vspace="0" hspace="6px"></a>
                                                    </div>'); 
                        }
                         */
		}
		//echo 'catalog_end';
		// ���������� ������
		if (in_array($this->PHPShopNav->getId(),$this->CustomCatalogIDArray3)) {
			$this->parseTemplate($this->getValue('templates.catalog_info_forma_1'));
		} else
        	$this->parseTemplate($this->getValue('templates.catalog_info_forma'));
    }

    /**
     * ����� 404 ������ �� ������ /shop/
     */
    function index() {
        return $this->setError404();
    }  

	function catalog_improvements() {
		//���������� ������ ���� ��������
                $ceo_custom_catalog_productname=read_ceo_custom_menu($_SERVER['DOCUMENT_ROOT'] . '/custom_config/product-name_catalog_rename.txt');
		//�������� ������� �������
		$cid_id=$this->PHPShopNav->getId();

		if (in_array($cid_id,$this->CustomCatalogIDArray2_1)) {
			// ���� ��� ���������
			$this->objPath = './CID_' . $this->category . '_';	
		
			// ���������� ����� ��� ������ ������
			$cell = $this->PHPShopCategory->getParam('num_row');

			// ���-�� ������� �� ��������
			$num_cow = $this->PHPShopCategory->getParam('num_cow');
			if (!empty($num_cow))
				$this->num_row = $num_cow;

			$orderby=' sklad asc,price asc,outdated asc ';
			$orderby_1=' sorting asc,sortorder asc,price asc ';
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
			if (in_array($cid_id,$this->CustomCatalogIDArray2_2)) {
				if ($this->PHPShopNav->getPage()=='') {
	        		/*
	        		$sql="select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and enabled='1' and parent_enabled='0' order by ".$orderby." LIMIT 1,".$this->num_row;
	        		*/
	        		$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,sortorder,sorting from (select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,1 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='0' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,2 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,3 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='1') t1 order by ".$orderby_1." LIMIT 1,".$this->num_row;
	        	} else if ($this->PHPShopNav->isPageAll()) {
	        		/*
	        		$sql="select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and enabled='1' and parent_enabled='0' order by ".$orderby;
	        		*/
	        		$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,sortorder,sorting from (select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,1 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='0' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,2 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,3 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='1') t1 order by ".$orderby_1;
	        	} else {
	        		$num_row=$this->PHPShopNav->getPage()*$this->num_row;
	        		//echo $num_row.' '.$this->num_row;
	        		$sql_2=" LIMIT ".($num_row-$this->num_row).",".$this->num_row;
	        		/*
	        		$sql="select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and enabled='1' and parent_enabled='0' order by ".$orderby.$sql_2;
	        		*/
	        		$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,sortorder,sorting from (select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,1 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='0' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,2 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='0'".
	        		" union all ".
	        		"select distinct id,".$cid_id." as category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift,3 as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where dop_cat like "
	        		."'%#".$cid_id."#%' and parent_enabled='0' and sklad='1' and enabled='1' and outdated='1') t1 order by ".$orderby_1.$sql_2;
	         	}
					        	
			} else {
				if ($this->PHPShopNav->getPage()=='') {
					//select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat from u301639_test.phpshop_products
					$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where category in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
					." union all"
					." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby_1." LIMIT 1,".$this->num_row;
					//$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');
				} else if ($this->PHPShopNav->isPageAll()) {
					$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where category in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to in"
					." (SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where parent_to=".$cid_id
					." union all"
					." SELECT id FROM ".$GLOBALS['SysValue']['base']['categories']." where id=".$cid_id.")) and enabled='1' and parent_enabled='0' order by ".$orderby_1;
					//$select_array=array('id','category','name','content','price','price_n','sklad','p_enabled','enabled','uid','num','price2','price3','price4','price5');
				} else {
					$num_row=$this->PHPShopNav->getPage()*$this->num_row;
					//echo $num_row.' '.$this->num_row;
					$sql_2=" LIMIT ".($num_row-$this->num_row).",".$this->num_row;
					$sql="select distinct id,category,name,content,price,price_n,sklad,p_enabled,enabled,uid,num,pic_small,parent_enabled,parent,price2,price3,price4,price5,dop_cat,outdated,analog,vendor,vendor_array,gift, case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder,sorting from ".$GLOBALS['SysValue']['base']['products']." where category in"
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

			// ������ ����������
			$order = $this->query_filter();
			//print_r($order);
			$cnt=1;
			$last_cnt=0;

			$prod_row=array();

			//$db_rows=mysql_num_rows($res);
			$db_rows=count($res);
			// ��������� ����� ������� ���������
			//$this->num_page = $db_rows;
			//echo $db_rows;
			// ���-�� ������� � ���������
			//$num = ceil($this->num_page / $this->num_row);	

			// ���������
			//echo $order['sql'];
			//echo $order;
			//echo var_export($this->PHPShopNav->isPageAll());
			if ($this->PHPShopNav->isPageAll()) {
                            //echo 1;
				$this->setPaginator($num_cow, $order);
			} else {
                            //echo 2;
				$this->setPaginator($num_cow, $order['sql']);			
			}
			
			
			//����������� ���������� ��� ���������� ������ �������
			$db_rows_odd=false;
			
			//�������� ���� ������ ��� �������� ���-��
			if ( $db_rows % 2 ) {
				$db_rows_odd=false;
			} else if ( $db_rows % 1 ) {
				$db_rows_odd=true;
			}

			//echo '$db_rows_odd='.intval($db_rows_odd);
			//echo '$db_rows='.$db_rows;
			//echo '$num_cow='.$num_cow;
			//� ����������� �� ���� �������� ������ �������
			if (in_array($cid_id,$this->CustomCatalogIDArray2_2)  && $cell==1) {
				//echo 1;
				$disp_cat.='<div class="content"><table cellpadding="0" cellspacing="0" border="0" width="100%" id="manufacturers_products" style="display: table;">';
			} else {
				$disp_cat.='<div class="wrapper"><table cellspacing="0" cellpadding="0" border="0">';
			}
                        
			//��������� � ������ ����
			$comnotice_price_n='';
                        
			//�������� ���� ���������� ������ ������� � ���������
			foreach ($res as $prod_row) {
                                foreach ($ceo_custom_catalog_productname as $ceo_custom_catalog_productname_item) {
                                    if (in_array($cid_id,$ceo_custom_catalog_productname_item)) {
                                        $productname_mod=str_replace($ceo_custom_catalog_productname_item['str1'],$ceo_custom_catalog_productname_item['str2'],$prod_row['name']);
                                        $prod_row['name']=$productname_mod;
                                    }
                                }                              
				//��������� ��������� ������ 
				if (empty($prod_row['pic_small'])) {
					$prod_row['pic_small']='images/shop/no_photo.gif';
				}
				//� ����������� �� ������� ������ $prod_row['sklad']=1 (��� �����)
				//������������ ��������� ������� ������
				if (empty($prod_row['sklad'])) {
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
                                //�� ��������� ������ ������ ������
                                $region_info='m';
				if (isset($_COOKIE['sincity'])) {
					$region_info=$_COOKIE['sincity'];
				}
                                
				// ���� ����� �� ������
				if (empty($prod_row['sklad'])) {
					// ���� ��� ����� ����
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
				
						//������������ ����� ���� � ��������
						$mod_price=$this->add_space_to_price($mod_price);
						$comnotice_price_n='';		
					}
					// ���� ���� ����� ����
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
					
						//������������ ����� ���� � ��������
						$mod_price=$this->add_space_to_price($mod_price);
                                                $sdvig_vlevo=$this->price_n_left_pos($price,$prod_row['price_n']);
                                                $mod_price_n=$this->add_space_to_price($prod_row['price_n']);
                                                $mod_price_n=str_replace('<span class="price_cat_3">����:</span>','',$mod_price_n);
						$comnotice_price_n='<div class="prev_price" style="top: 245px;left: '.$sdvig_vlevo.'px;"><strike>'.$mod_price_n.'</strike></div>';
					}
				}
				// ����� ��� ����� | ���� � ������������
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
				
					//������������ ����� ���� � ��������
					$mod_price=$this->add_space_to_price($mod_price);
                                        $comnotice_price_n='';
				}				
				//��������� �������� ��� stihl
				if ( $cid_id==121 || $cid_id==228 || $cid_id==459 || $cid_id==461 || $cid_id==464) {
					// ���� � ������������+������ ���� ��� �����
					if ( !(empty($prod_row['sklad'])) && !(empty($prod_row['outdated'])) ) {
						//����� ��������� �� ���������� ������
                                                if ($cell>1){
                                                    $comnotice='<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';
                                                } else {
                                                    $comnotice='<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -12px;top:"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';                                                    
                                                }

						//$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
						$productnotice='';
                                                $outdated_style='';
						//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
						if ($cell>1) {
							//$productnotice='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice.'</noindex></span>';//$comnotice_30
						}
                                                $addtochart_outdated_class=' inactive';
                                                
						//������ ���� ���� ������ ������� ������ �� ����					
						if ( !(empty($prod_row['analog'])) ) {
							//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
							if ($cell>1) {
								$sdvig_vlevo='';
								$font_size='13px';
							} else {
								$sdvig_vlevo='left:-20px;';
								$font_size='14px';
							}
							//��������� ������ �� ����������� �����
                                                        $productnotice.='<a id="analog_href'.$prod_row['id'].'" href="/shop/UID_'.$prod_row['analog'].'.html">������</a>';
                                                        $addtochart_outdated_class='';
                                                        /*
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:'.$font_size.' !important;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></div>';
							$productnotice.='<a id="analog_href'.$prod_row['id'].'" style="visibility: hidden;" href="/shop/UID_'.$prod_row['analog'].'.html"></a>';
							$productnotice.='<script type="text/javascript">';
							$productnotice.='$(document).ready(function() {';
							$productnotice.='$("#price_comlain'.$prod_row['id'].'").click( function( event ) {';
							$productnotice.='	window.location =$("#analog_href'.$prod_row['id'].'").attr("href");';
							$productnotice.='	return false;';
							$productnotice.='});});';
							$productnotice.='</script>';
                                                        $outdated_style='style="display:relative;margin:-55px 15px 0px 0px;"';
                                                         */
						} else {
							//���� ��� ������� �� ������� ��������
							//$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
							//$productnotice='';
							//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
							//if ($cell>1) {
								//$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
                                                        $productnotice.='<a id="analog_href'.$prod_row['id'].'" href="/shop/UID_'.$prod_row['analog'].'.html" onclick="return false;">������</a>';
                                                                
							//}
							//$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
						}
						//$comnotice='';
                                                //if (empty($outdated_style))
                                                    //$outdated_style='style="display:relative;margin:-25px 15px 0px 0px;"';
                                                $outdated_style='';
                                                $comnotice_price_n='';
                                                $stihl_viking='';
					} else {
						//����� �� �������� � �������/��� �����
                                                $stihl_viking=' stihlvikingbuy';
						$comnotice_30='';
						$addtochart='<input type="button" style="font-size:9px;" onclick="stihl_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						$productnotice='<input type="button" style="font-size:9px;" onclick="stihl_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						//���� ����� ��� �����
						if (!(empty($prod_row['sklad']))) {
							//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
							if ($cell>1) {
								$sdvig_vpravo='left:20px;';
								$comnotice='<div class="prev_price lostandfound" style="position:absolute;font-size:11px !important;left: -117px;top: 25px;"><!--noindex--><!--/noindex--></div>';//$this->lang('sklad_mesage')
							} else {
								$sdvig_vpravo='';
                                                                $comnotice='<div class="prev_price lostandfound" style="position:relative;font-size:11px !important;left:-24px;top:5px;"><!--noindex--><!--/noindex--></div>';
							}
						} else {
							$comnotice='';
						}
						$outdated_style='';
                                                $comnotice_price_n='';
					}
				//��������� �������� ��� viking
				} else if ( $cid_id==295 || $cid_id==467 ) {
					// ���� � ������������+������ ���� ��� �����
					if (!(empty($prod_row['sklad'])) && !(empty($prod_row['outdated']))) {
						//����� ��������� �� ���������� ������
                                                if ($cell>1){
                                                    $comnotice='<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';
                                                } else {
                                                    $comnotice='<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -12px;top:"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';                                                    
                                                }
						//$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
						$productnotice='';
                                                $outdated_style='';
						//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
						if ($cell>1) {
							//$productnotice='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice.'</noindex></span>';//$comnotice_30
						}
                                                $addtochart_outdated_class=' inactive';
                                                
						//������ ���� ���� ������ ������� ������ �� ����					
						if ( !(empty($prod_row['analog'])) ) {
							//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
							if ($cell>1) {
								$sdvig_vlevo='';
								$font_size='13px';
							} else {
								$sdvig_vlevo='left:-20px;';
								$font_size='14px';
							}
							//��������� ������ �� ����������� �����
                                                        $productnotice.='<a id="analog_href'.$prod_row['id'].'" href="/shop/UID_'.$prod_row['analog'].'.html">������</a>';
                                                        $addtochart_outdated_class='';
                                                        /*
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:'.$font_size.' !important;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></div>';
							$productnotice.='<a id="analog_href'.$prod_row['id'].'" style="visibility: hidden;" href="/shop/UID_'.$prod_row['analog'].'.html"></a>';
							$productnotice.='<script type="text/javascript">';
							$productnotice.='$(document).ready(function() {';
							$productnotice.='$("#price_comlain'.$prod_row['id'].'").click( function( event ) {';
							$productnotice.='	window.location =$("#analog_href'.$prod_row['id'].'").attr("href");';
							$productnotice.='	return false;';
							$productnotice.='});});';
							$productnotice.='</script>';
                                                        $outdated_style='style="display:relative;margin:-55px 15px 0px 0px;"';
                                                         */
						} else {
							//���� ��� ������� �� ������� ��������
							//$comnotice_30=$this->lang('outdated_message');//'<div class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'..'</noindex></div>'
							//$productnotice='';
							//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
							//if ($cell>1) {
								//$productnotice.='<span class="prev_price" style="position:relative;display: inline-block;top:3px;margin:-15px 0px 0px 10px;font-size:12px !important;"><noindex>'.$comnotice_30.'</noindex></span>';
                                                        $productnotice.='<a id="analog_href'.$prod_row['id'].'" href="/shop/UID_'.$prod_row['analog'].'.html" onclick="return false;">������</a>';
                                                                
							//}
							//$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;border-bottom:1px;"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
						}
						//$comnotice='';
                                                //if (empty($outdated_style))
                                                    //$outdated_style='style="display:relative;margin:-25px 15px 0px 0px;"';
                                                $outdated_style='';
                                                $comnotice_price_n='';
                                                $stihl_viking='';
					} else {
						//����� �� �������� � �������/��� �����
                                                $stihl_viking=' stihlvikingbuy';
						$comnotice_30='';
						$addtochart='<input type="button" style="font-size:9px;" onclick="stihl_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						$productnotice='<input type="button" style="font-size:9px;" onclick="stihl_window(\''.$region_info.'\',\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'))"  value="'.$this->lang('stihl_string').'" />';
						//���� ����� ��� �����
						if (!(empty($prod_row['sklad']))) {
							//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
							if ($cell>1) {
								$sdvig_vpravo='left:20px;';
								$comnotice='<div class="prev_price lostandfound" style="position:absolute;font-size:11px !important;left: -117px;top: 25px;"><!--noindex--><!--/noindex--></div>';//$this->lang('sklad_mesage')
							} else {
								$sdvig_vpravo='';
                                                                $comnotice='<div class="prev_price lostandfound" style="position:relative;font-size:11px !important;left:-24px;top:5px;"><!--noindex--><!--/noindex--></div>';
							}
						} else {
							$comnotice='';
						}
						$outdated_style='';
                                                $comnotice_price_n='';
					}
				} else {
					// ���� � ������������+������ ���� ��� �����, ��� ���������������� ���������
					if (!(empty($prod_row['sklad'])) && !(empty($prod_row['outdated']))) {                                           
						if (in_array($cid_id,$this->CustomCatalogIDArray2_2)) {
							//$comnotice='';
							//$productnotice='';
							//$comnotice_30=$this->lang('outdated_message3');//$this->lang('sklad_mesage');
							if ($cell>1) {
                                                            //$productnotice.='<span class="outdated_message" style="position:relative;display: inline-block;top:3px;margin:-5px 0px 0px 10px;font-size:12px !important;"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';
                                                            $comnotice='<span class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';
							} else {
                                                            $comnotice='<span class="outdated_message" style="position: relative;font-size: 11px !important;left: -12px;"><noindex>'.$this->lang('outdated_message3').'</noindex></span>';
                                                        }
							//���� � ������������+������ ���� ��� �����, ��� ���� ��������� ���������
						} else {
                                                    //$comnotice='';
                                                    //$productnotice_0='<div class="outdated_message" style="position:relative;display: inline-block;top:3px;margin:-5px 0px 0px 10px;font-size:12px !important;"><noindex>'.$this->lang('outdated_message3').'</noindex></div>';
                                                    //$productnotice=$productnotice_0;
                                                    $comnotice='<div class="outdated_message" style="position:absolute;font-size:11px !important;left: -106px;top: 25px;"><noindex>'.$this->lang('outdated_message3').'</noindex></div>';
						}
						//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
						if ($cell>1) {
							$sdvig_vlevo='';
						} else {
							$sdvig_vlevo='left:-20px;';
						}
                                                $outdated_style='';
                                                $addtochart_outdated_class=' inactive';

						//����� ������ �� ����������� �����
						if ( !(empty($prod_row['analog'])) ) {
                                                        $productnotice='<a id="analog_href'.$prod_row['id'].'" href="/shop/UID_'.$prod_row['analog'].'.html">������</a>';
                                                        $addtochart_outdated_class='';
                                                        /*
							$productnotice.='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;'.$sdvig_vlevo.'"><noindex>'.$this->lang('outdated_message2').'</noindex></div>';
							$productnotice.='<a id="analog_href'.$prod_row['id'].'" style="visibility: hidden;" href="http://prodacha.ru/shop/UID_'.$prod_row['analog'].'.html"></a>';
							$productnotice.='<script type="text/javascript">';
							$productnotice.='$(document).ready(function() {';
							$productnotice.='$("#price_comlain'.$prod_row['id'].'").click( function( event ) {';
							$productnotice.='	window.location =$("#analog_href'.$prod_row['id'].'").attr("href");';
							$productnotice.='	return false;';
							$productnotice.='});});';
							$productnotice.='</script>';
                                                         */
                                                        //$outdated_style='style="display:relative;margin:-55px 15px 0px 0px;"';
						} else {
							//����� �������� ���� ��� ������ �� ������
							//$productnotice='<div id="price_comlain'.$prod_row['id'].'" class="price_comlain" style="position:relative;display: inline-block;margin:0px 95px 0px 0px;font-size:13px !important;	border-bottom:1px;'.$sdvig_vlevo.'"><!--noindex-->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--/noindex--></div>';
                                                        $productnotice='<a id="analog_href'.$prod_row['id'].'" href="/shop/UID_'.$prod_row['analog'].'.html" onclick="return false;">������</a>';
						}
						//$comnotice='';
                                                $comnotice_price_n='';
                                                //if (empty($outdated_style))
                                                //    $outdated_style='style="display:relative;margin:-25px 15px 0px 0px;"';
                                                $outdated_style='';
                                                //echo '$addtochart_outdated_class1='.$addtochart_outdated_class;
						//����� ������� ��� �����
					} else if (!(empty($prod_row['sklad'])) && empty($prod_row['outdated'])) {

                                                $addtochart_outdated_class='';
						$comnotice_30='';//$this->lang('sklad_mesage');
						//$productnotice='<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'" />';
						$productnotice='<input type="button" onclick="ask_product_availability(\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'));" value="'.$this->lang('product_notice').'">';
						//���������� ��������� �������������� ��� ������ � ��������� � ���-� ����� ������ 1
						if ($cell>1) {
							$sdvig_vpravo='left:20px;';
							$comnotice='<div class="prev_price lostandfound" style="position:absolute;font-size:11px !important;left: -117px;top: 25px;"></div>';//$this->lang('sklad_mesage') style="position:relative;font-size:11px !important;'.$sdvig_vpravo.'top:-10px;"
						} else {
							$sdvig_vpravo='';
							//$comnotice=$this->lang('sklad_mesage');
							$comnotice='<div class="prev_price lostandfound" style="position: relative;font-size: 11px !important;left: -24px;top: 5px;"></div>';//$this->lang('sklad_mesage') style="position:relative;font-size:11px !important;'.$sdvig_vpravo.'top:-10px;"
						}
						$outdated_style='';
						$comnotice_price_n='';
					} else {

						//����� ������ �� �������� �������� ��� �������
						//$addtochart='<input type="button" onclick="javascript:AddToCart('.$prod_row['id'].')"  value="'.$this->lang('product_sale').'" />';
                                                //����������� CEO 05-10-2015
                                                $addtochart_outdated_class='';
                                                $addtochart='<a href="#_tool_'.$prod_row['id'].'" id="a'.$prod_row['id'].'" onclick="javascript:AddToCart('.$prod_row['id'].')">'.$this->lang('product_sale').'</a>';
						//$productnotice='<input type="button" onclick="window.location.replace(\'/users/notice.html?productId='.$prod_row['id'].'\');"  value="'.$this->lang('product_notice').'" />';
						$productnotice='<input type="button" onclick="ask_product_availability(\'/shop/UID_'.$prod_row['id'].'.html\',document.getElementsByClassName(\'netref\'));" value="'.$this->lang('product_notice').'">';
						//$comnotice='<div class="prev_price" style="font-size:11px !important;top:-10px;"><noindex></noindex></div>';//$this->lang('sklad_mesage')
						$outdated_style='';
						$comnotice_30='';
                                                $comnotice='';
						if (!empty($prod_row['price_n'])){
                                                    $sdvig_vlevo=$this->price_n_left_pos($prod_row['price'],$prod_row['price_n']);
                                                    $mod_price_n=$this->add_space_to_price($prod_row['price_n']);
                                                    $mod_price_n=str_replace('<span class="price_cat_3">����:</span>','',$mod_price_n);
                                                    $comnotice_price_n='<div class="prev_price" style="top: 245px;left: '.$sdvig_vlevo.'px;"><strike>'.$mod_price_n.'</strike></div>';
						} else
                                                    $comnotice_price_n='';
					}
				}
				//������� ������ �������� ������ � ����� ������� ��� ���������������� ���������
				if (in_array($cid_id,$this->CustomCatalogIDArray2_2) && $cell==1) {

					$this->catalog_product_icons($prod_row);
					$this->sort_table($prod_row);
					//echo $this->get('Producticons');
					$disp_cat.='<tr>'
					.'<td class="panel_l panel_1_1" valign="top"><div id="_tool_'.$prod_row['id'].'" class="tool_'.$prod_row['id'].'">'
					.'<div class="tovar1"> <div class="item1">'
					.'<span class="popular-"></span>'
					.'<div class="thumb">'
					.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
					.'<tbody><tr>'
					.'<td height="150" align="top"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="phpshop/templates/prodacha/images/shop/no_photo.gif" onerror="NoFoto(this,\'phpshop/templates/prodacha\')" onload="EditFoto(this,)" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'// alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'"
					.'</tr>'
					.'</tbody></table>'
					.'</div>'
					.'<div class="descr_wrapper"><a href="/shop/UID_'.$prod_row['id'].'.html"><span class="description">'.$prod_row['name'].'</span></a>'
                                        .'<div style="clear:both;"></div>'
					.$this->get('Producticons')
					//.'<span class="prev_price"><noindex>'.$comnotice.$comnotice_30.'</noindex></span>'
                                        .'<span class="price">'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span></span>'					
					.'<div class="buybuttons">'
					.$ComStartCart.'<span class="addtochart'.$stihl_viking.'">'
					.$addtochart
					.'</span>'.$ComEndCart	
					.$ComStartNotice.'<span class="addtochart notice'.$addtochart_outdated_class.'">'
					.$productnotice
					.'</span>'.$ComEndNotice
                                        .$comnotice
                                        //.'549-core-cat-1'
					.'<!--@firstcreditpunch@-->'
					.'</div>'
					.'</div>'
					.'<div class="parameters">'
					.'<table  width="100%" border="0" cellspacing="0" cellpadding="0">'
					.'<tr>'
					.'<td height="150" valign=top ><div style="overflow:hidden; height:150px; padding-right:20px;">'.$this->get('vendorDisp').'</div></td>'
					.'</tr>'
					.'</table>'
					//.$this->get('vendorDisp')
					.'</div> </div>'
					.'</div>'
					.'</td>'
					.'</tr>'
					.'<tr><td class="setka" colspan="2" height="1"><img height="1" src="images/spacer.gif"></td>'
					.'</tr>';
					// ��������� � ������ ������ � ��������
					//$grid = $this->product_grid($prod_row, 1);
					//if (empty($grid))
					//	$grid = PHPShopText::h2($this->lang('empty_product_list'));
					//$this->add($grid, true);
				} else {
					if ($cell==1 || $cell==2 || $cell==3 || $cell==4) {
						//echo $this->lang('outdated_message');
                                                //style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;"
                                                // onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"
						if ($cnt == 1) {
							$disp_cat.='<tr><td class="panel_l panel_3_1"><div class="tovar">'
							.'<div class="item" id="_tool_'.$prod_row['id'].'">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
							.'<td height="150" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'// alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'"
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
							.$ComStartNotice.'<span class="addtochart'.$addtochart_outdated_class.'">'
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<div class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice
							.'</div>'
                                                        .$comnotice_price_n
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
                                                //style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;"
                                                // onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"
						if  ($cnt == 2) {
							$disp_cat.='<td class="panel_r panel_3_2"><div class="tovar">'
                                                        .'<div class="item" id="_tool_'.$prod_row['id'].'">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
							.'<td height="150" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'// alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'"
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
							.$ComStartNotice.'<span class="addtochart'.$addtochart_outdated_class.'">'
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<div class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice
							.'</div>'
                                                        .$comnotice_price_n
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
                                                        // style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;"
                                                        // onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"
							$disp_cat.='<td class="panel_l panel_3_2"><div class="tovar">'
                                                        .'<div class="item" id="_tool_'.$prod_row['id'].'">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
							.'<td height="150" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'// alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'"
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
							.$ComStartNotice.'<span class="addtochart'.$addtochart_outdated_class.'">'
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<div class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice
							.'</div>'
                                                        .$comnotice_price_n
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
                                                        //style="position: relative;display: block;float: left;margin: 0 30px 15px 0;width: 243px;height: 275px;background-image: url(..images/prod_bg.png) !important;background-position-x: 0px;background-position-y: 0px;background-size: initial;background-repeat-x: no-repeat;background-repeat-y: no-repeat;background-attachment: scroll;background-origin: initial;background-clip: initial;background-color: transparent;"
                                                        // onmouseover="this.style.backgroundPosition=\'0px -276px\';" onmouseout="this.style.backgroundPosition=\'0px 0px\';"
							$disp_cat.='<td class="panel_l panel_3_3"><div class="tovar">'
							.'<div class="item" id="_tool_'.$prod_row['id'].'">'
							.'<span class="new"></span>'
							.'<div class="thumb">'
							.'<table width="100%" border="0" cellspacing="0" cellpadding="0">'
							.'<tr>'
							.'<td height="150" align="center"><a href="/shop/UID_'.$prod_row['id'].'.html"><img src="'.$prod_row['pic_small'].'" lowsrc="images/shop/no_photo.gif"  onerror="NoFoto(this,images/shop/no_photo.gif)" onload="EditFoto(this,'.$GLOBALS['SysValue']['System']['width_icon'].')" alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'" border="0"></a></td>'// alt="'.$prod_row['name'].'" title="'.$prod_row['name'].'"
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
							.$ComStartNotice.'<span class="addtochart'.$addtochart_outdated_class.'">'
							.$productnotice
							.'</span>'.$ComEndNotice
							.'<div class="price" '.$outdated_style.'>'.$mod_price.'<span class="smallfont">&nbsp;'.$this->PHPShopSystem->getDefaultValutaCode(true).'</span>'
							.'<div style="clear:both"></div>'
							.$ComStartNotice.$comnotice.$ComEndNotice
							.'</div>'
                                                        .$comnotice_price_n
							.'</div>'
							.'</div>'
							.'</td><td class="setka" colspan="2"> <img width="1" src="images/spacer.gif"></td>';
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

    function price_n_left_pos($price,$old_price){
        if (strlen($price)==3){
        if (strlen($price)<strlen($old_price))
                $left=200-strlen($old_price)-46;
        else
                $left=200-strlen($old_price)-44;
        }
        else if (strlen($price)==4){
        if (strlen($price)<strlen($old_price))
                $left=200-strlen($old_price)-49;
        else
                $left=200-strlen($old_price)-47;
        }
        else if (strlen($price)==5){
        if (strlen($price)<strlen($old_price))
                $left=200-strlen($old_price)-51;
        else
                $left=200-strlen($old_price)-50;
        }
        else if (strlen($price)==6){
        if (strlen($price)<strlen($old_price))
                $left=200-strlen($old_price)-55;
        else
                $left=200-strlen($old_price)-53;
        }
        else if (strlen($price)==7){
        if (strlen($price)<strlen($old_price))
                $left=200-strlen($old_price)-58;
        else
                $left=200-strlen($old_price)-56;
        }
        else {
        if (strlen($price)<strlen($old_price))
                $left=200-strlen($old_price)-52;
        else
                $left=200-strlen($old_price)-50;
        }
        return $left;              
    }
}

?>