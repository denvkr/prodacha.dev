<?php

/**
 * ������� ������������� �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSortElements
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopSortElement extends PHPShopElements {

    /**
     * �����������
     */
    function PHPShopSortElement() {
        parent::PHPShopElements();
    }

    /**
     * ����� ������ �������������� ��� ������
     * @param string $var ��� ���������� � �������������
     * @param int $n �� �������������� ��� ������ ��������
     * @param string $title ��������� �����
     * @param string $target ���� ����� [/selection/  |  /selectioncat/]
     */
    function brand($var, $n, $title, $target = '/selection/') {

        // �� �������������� ��� ������ ��������
        $this->n = $n;

        // ���������� ����������
        PHPShopObj::loadClass('sort');

        $PHPShopSort = new PHPShopSort();
        $value = $PHPShopSort->value($n, $title);
        $forma = PHPShopText::p(PHPShopText::form($value . PHPShopText::button('OK', 'SortSelect.submit()'), 'SortSelect', 'get', $target, false, 'ok'));
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $title);

        // ���������� ������
        $dis = $this->parseTemplate($this->getValue('templates.left_menu'));

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $value);

        // ��������� ���������� �������
        $this->set($var, $dis);
    }

}

/**
 * ������� ���������� ������ ������� � �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductIconElements
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopProductIconElements extends PHPShopProductElements {

    /**
     * �������
     * @var bool
     */
    var $debug = false;

    /**
     * ������ �������
     * @var bool
     */
    var $memory = true;

    /**
     * ������ ������
     * @var string 
     */
    var $template = 'main_spec_forma_icon';

    /**
     * ����������� �� �����
     * @var string 
     */
    var $limitspec;

    /**
     * ����� ������ [1-5]
     * @var int 
     */
    var $celll;

    /**
     * �����������
     */
    function PHPShopProductIconElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();
    }

    /**
     * ������� "���������������-�������" ��� ���� �������
     * @param bool $force �������� ����������� ��� ���������� �������� ������
     * @param int $category �� ��������� ��� �������
     * @param int $cell ����� ������ [1-5]
     * @param int $limit ����������� �� �����
     * @return string
     */
    function specMainIcon($force = false, $category = null, $cell = 3, $limit = null, $line = false) {

        $this->limitspec = $limit;
        $this->cell = $cell;


        // ������� ������ �� �������� ��������
        if ($GLOBALS['SysValue']['nav']['path'] == 'shop') {

            switch ($GLOBALS['SysValue']['nav']['nav']) {

                // ������ ������ �������
                case "CID":

                    if (!empty($category))
                        $where['category'] = '=' . $category;

                    elseif (PHPShopSecurity::true_num($this->PHPShopNav->getId())) {
                        $category = $this->PHPShopNav->getId();
                        $where['category'] = '=' . $category;
                    }
                    break;

                // ������ ���������� ��������
                case "UID":
                    if (empty($force))
                        return false;
                    else
                        $where['category'] = '=' . $category;

                    $where['id'] = '!=' . $this->PHPShopNav->getId();
                    break;
            }
        }

        // ���-�� ������� �� ��������
        if (empty($this->limitspec))
            $this->limitspec = $this->PHPShopSystem->getParam('new_num');

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;


        // ���������� ���� �������� �����
        if (empty($this->limitspec))
            return false;

        // �������� ������ ��� ������� ���
        //$where['id']=$this->setramdom($limit);
        // ��������� ������� ����� ������ � �������� � �������
        $where['newtip'] = "='1'";
        $where['enabled'] = "='1'";
        $where['parent_enabled'] = "='0'";

        // �������� �� ��������� �������
        if ($limit == 1) {
            $array_pop = true;
            $limit++;
        }

        // ������ ������ ������� ������� �� ���������
        $memory_spec = $this->memory_get('product_spec.' . $category);

        // ������� �������
        if ($memory_spec != 2 and $memory_spec != 3)
            $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

        // �������� �� ��������� �������
        if (!empty($array_pop) and is_array($this->dataArray)) {
            array_pop($this->dataArray);
        }

        if (!empty($this->dataArray) and is_array($this->dataArray)) {
            $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
            $this->set('specMainTitle', $this->lang('newprod'));

            // ������� � ������
            $this->memory_set('product_spec.' . $category, 1);
        } else {
            // ������� ���������������
            unset($where['newtip']);
            $where['spec'] = "='1'";

            if ($memory_spec != 1 and $memory_spec != 3)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

            // �������� �� ��������� �������
            if (!empty($array_pop) and is_array($this->dataArray)) {
                array_pop($this->dataArray);
            }

            if (!empty($this->dataArray) and is_array($this->dataArray)) {
                $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                $this->set('specMainTitle', $this->lang('specprod'));

                // ������� � ������
                $this->memory_set('product_spec.' . $category, 2);
            } else {
                // ������� ��������� ����������� �������
                unset($where['id']);
                unset($where['spec']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => $this->limitspec), __FUNCTION__);

                // �������� �� ��������� �������
                if (!empty($array_pop) and is_array($this->dataArray)) {
                    array_pop($this->dataArray);
                }

                if (is_array($this->dataArray)) {
                    $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                    $this->set('specMainTitle', $this->lang('newprod'));

                    // ������� � ������
                    $this->memory_set('product_spec.' . $category, 3);
                }
            }
        }

        // �������� � ���������� ������� � ��������
        return $this->compile();
    }

    /**
     * ������� ������� ����� ������ ������� (���������)
     * @param array $row ������ ������ �������
     * @param int $cell ����������� ����� [1|2|3|4|5]
     * @param string $template ������ ������
     * @param bool $line ������� ����������� ����� �������
     * @return string
     */
    function seamply_forma($row, $cell = false, $template = 'main_spec_forma_icon', $line = false) {

        // ���������� ����� ��� ������ ������
        if (empty($cell))
            $cell = $this->PHPShopSystem->getParam('num_vitrina');

        $this->set('productInfo', $this->lang('productInfo'));

        // ��������� � ������ ������ � ��������
        $this->product_grid($row, $cell, $template, $line);

        // �������� � ���������� ������� � ��������
        return $this->compile();
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

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5, $d6, $d7);
    }

    /**
     * ���� ������ �� ������� � �������
     * @return string
     */
    function compile() {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook) {
            return $hook;
        }

        return parent::compile();
    }

}

/**
 * ������� ���������� ������ �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductIndexElements
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopProductIndexElements extends PHPShopProductElements {

    /**
     * �������
     * @var bool
     */
    var $debug = false;

    /**
     * ����� ������
     * @var int
     */
    var $cell;

    /**
     * ������ �������
     * @var bool
     */
    var $memory = false;

    /**
     * �����������
     */
    function PHPShopProductIndexElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();
    }

    /**
     * ������ ����������� ������ "������ ��������"
     * @param array $row ������ ������
     * @return string
     */
    function template_nowbuy($row) {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
        if ($hook)
            return $hook;

        return PHPShopText::li($row['name'], 'shop/UID_' . $row['id'] . '.html');
    }

    /**
     * ������� "������ ��������" ��� ������� ��������
     * @return string
     */
    function nowBuy() {

        // �������� ������� ������� ��������
        if ($this->PHPShopNav->index()) {
            $i = 1;
            //$this->limitpos = 9; // ���������� ��������� �������
            //$this->limitorders = 9; // ���������� ������������� �������
            
            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->sql = "select num_row from " . $GLOBALS['SysValue']['base']['system'] . " LIMIT 1";
            $data = $PHPShopOrm->select();
            if (is_array($data)) {
            	foreach ($data as $row) {
            		$this->limitpos=$row['num_row'];
            		$this->limitorders=$row['num_row'];
            	}
            } else {
            	$this->limitpos = 9; // ���������� ��������� �������
            	$this->limitorders = 9; // ���������� ������������� �������
            }        

            $disp = $li = null;
            $enabled = $this->PHPShopSystem->getSerilizeParam('admoption.nowbuy_enabled');
            $sort = null;

            // �������� ������
            $hook = $this->setHook(__CLASS__, __FUNCTION__);
            if ($hook)
                return $hook;

            // ���������� �����
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

            if (!empty($enabled)) {

                // ��������� ������
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $PHPShopOrm->debug = $this->debug;
                $data = $PHPShopOrm->select(array('orders'), false, array('order' => 'id desc'), array('limit' => $this->limitorders));

                if (is_array($data)) {
                    foreach ($data as $row) {
                        $order = unserialize($row['orders']);
                        $cart = $order['Cart']['cart'];
                        if (is_array($cart))
                            foreach ($cart as $good) {
                                if ($i > $this->limitpos)
                                    break;
                                $sort.=' id=' . intval($good['id']) . ' OR';
                            }
                    }
                    $sort = substr($sort, 0, strlen($sort) - 2);

                    // ���� ���� ������
                    if (!empty($sort)) {
                        $PHPShopOrm = new PHPShopOrm();
                        $PHPShopOrm->debug = $this->debug;
                        $PHPShopOrm->sql = "select * from " . $this->objBase . " where (" . $sort . ") and enabled='1' LIMIT 0," . $this->limitpos;
                        $PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
                        $dataArray = $PHPShopOrm->select();
                        if (is_array($dataArray)) {

                            // ������ ��������
                            if ($enabled == 2) {

                                // ���������� ����� ��� ������ ������
                                if (empty($this->cell))
                                    $this->cell = $this->PHPShopSystem->getParam('num_vitrina');
                                $this->set('productInfo', $this->lang('productInfo'));

                                // ��������� � ������ ������ � ��������
                                $this->product_grid($dataArray, $this->cell);

                                // �������� � ���������� ������� � ��������
                                $disp = $this->compile();
                            }
                            // ������ �������
                            else {
                                foreach ($dataArray as $row) {
                                    $li.=$this->template_nowbuy($row);
                                    $i++;
                                }

                                $disp = PHPShopText::ol($li);
                            }

                            return $disp;
                        }
                    }
                }
            }
        }
    }

    /**
     * �������� ���� �������� ������ Multibase
     * @param int $category
     * @return boolean 
     */
    function randMultibase() {

        $multi_cat = null;

        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            $where['parent_to'] = " > 0";
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->cache = true;
            $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 1),__CLASS__,__FUNCTION__);
            if (is_array($data)) {
                foreach ($data as $row) {
                    $multi_cat='=' . $row['id'];
                }
            }

            return $multi_cat;
        }
    }

    /**
     * ������� "���������������" �� ������� ��������
     * @return string
     */
    function specMain() {

        // �������� ������� ������� ��������
        if ($this->PHPShopNav->index()) {

            // ���������� ����� ��� ������ ������
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getParam('num_vitrina');

            // ���-�� ������� �� ��������
            $this->limit = $this->PHPShopSystem->getParam('spec_num');

            // ���������� ���� �������� �����
            if ($this->limit < 1)
                return false;

            // �������� ������
            $hook = $this->setHook(__CLASS__, __FUNCTION__);
            if ($hook)
                return $hook;

            $this->set('productInfo', $this->lang('productInfo'));

            // ��������� ������
            $where['id'] = $this->setramdom($this->limit);

            // ��������� ������� ����� ������ � ��������������� � �������
            $where['spec'] = "='1'";
            $where['enabled'] = "='1'";
            
            $randMultibase = $this->randMultibase();
            if(!empty($randMultibase))
                $where['category'] = $randMultibase;


            // �������
            if ($this->limit > 1)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);
            else
                $this->dataArray[] = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);

            // ������ ������� ������� ���������������, ����������� RAND ��������
            $count = count($this->dataArray);
            if ($count < $this->limit) {
                unset($where['id']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);
            }


            // ��������� � ������ ������ � ��������
            $this->product_grid($this->dataArray, $this->cell);

            // �������� � ���������� ������� � ��������
            return $this->compile();
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

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5, $d6, $d7);
    }

    /**
     * ���� ������ �� ������� � �������
     * @return string
     */
    function compile() {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook) {
            return $hook;
        }

        return parent::compile();
    }

}

/**
 * ������� ���������� ������ ��������� �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShopCatalogElement
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopShopCatalogElement extends PHPShopProductElements {

    /**
     * �������
     * @var bool
     */
    var $debug = false;
    var $cache = true;

    /**
     * ������ ����� ��� ������� � ���� ��� ����������� ����. �������� �������� �������� � YML ���������.
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');
    var $memory = true;

    /**
     * ��������� �� ��������� ��������. [false] - ��� ������� ���������, ��������� ������� � ��
     * @var bool
     */
    var $chek_catalog = true;
    var $grid = true;

    /**
     * �����������
     */
    function PHPShopShopCatalogElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['categories'];
        parent::PHPShopElements();
    }

    /**
     * ������ ������ ��������� ��������� � ��������
     * @param array $val ������ ������
     * @return string
     */
    function template_cat_table($val) {

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $val);
        if ($hook)
            return $hook;

        return PHPShopText::a('/shop/CID_' . $val['id'] . '.html', $val['name'], $val['name']) . ' | ';
    }

    
    /**
     * ����� ����� ��� leftCatalTable
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null) {

        // �������� ������, ��������� � ������ ������� ������ ��� �����������
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            } else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5);
    }
    
    /**
     * ������� ��������� � ��������
     * @return string
     */
    function leftCatalTable() {

        // ���������� ������ � Index
        if ($this->PHPShopNav->index()) {

            $dis = null;
            $podcatalog = null;

            $this->cell = $this->PHPShopSystem->getParam('num_row_adm');

            $table = null;
            $j = 1;
            $item = 1;

            // �������� ������
            $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
            if ($hook)
                return $hook;

            if (is_array($this->data))
                foreach ($this->data as $row) {
                    $dis = null;
                    $podcatalog = null;
                    $this->set('catalogId', $row['id']);
                    $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47));
                    $this->set('catalogTitle', $row['name']);
                    $this->set('catalogName', $row['name']);

                    // �������� �� ������� ������ � �������� ���������
                    if (stristr($row['content'], 'img') and strlen($row['content']) < 150)
                        $this->set('catalogContent', $row['content']);
                    else
                        $this->set('catalogContent', null);

                    // ����� ������� ��������� �� ����, ������ ������������
                    if (is_array($GLOBALS['Cache'][$this->objBase]))
                        foreach ($GLOBALS['Cache'][$this->objBase] as $val) {
                            if ($val['parent_to'] == $row['id'])
                                $podcatalog.=$this->template_cat_table($val);
                        }

                    $this->set('catalogPodcatalog', $podcatalog);

                    // �������� ������
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // ���������� ������
                    $dis.= ParseTemplateReturn("catalog/catalog_table_forma.tpl");

                    // ������ � ���������� (1-5)
                    if ($j < $this->cell) {
                        $cell_name = 'd' . $j;
                        $$cell_name = $dis;
                        $j++;
                        if ($item == count($this->data)) {
                            $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                        }
                    } else {
                        $cell_name = 'd' . $j;
                        $$cell_name = $dis;
                        $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                        $d1 = $d2 = $d3 = $d4 = $d5 = null;
                        $j = 1;
                    }
                    $item++;
                }

            $this->product_grid = $table;
            return $this->compile();
        }
    }

    /**
     * ����� ��������� ���������
     * @param array $replace ������ ������ ������
     * @param array $where ������ ���������� �������, ������������ ��� ������ ������������� ��������
     * PHPShopShopCatalogElement::leftCatal(false,$where['id']=1);
     * @return string
     */
    function leftCatal($replace = null, $where = null) {
        $dis = null;
        $i = 0;

        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $where, 'START');
        if ($hook)
            return $hook;

        // �������� �������
        if (empty($where))
            $where['parent_to'] = '=0';

        // �� �������� ������� ��������
        $where['skin_enabled '] = "!='1'";

        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $this->data = $PHPShopOrm->select(array('*'), $where, array('order' => 'num'), array("limit" => 100), __CLASS__, __FUNCTION__);
        if (is_array($this->data))
            foreach ($this->data as $row) {
				$dis.=$this->custommenuoutput($row,$i);
                $i++;
			}
/*
        // ������ ������
        if (is_array($replace)) {
            foreach ($replace as $key => $val)
                $dis = str_replace($key, $val, $dis);
        }
*/
		// �������� ������
		$hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
		if ($hook)
			return $hook;
			
        return $dis;
    }

    /**
     * ����� ������������
     * @param int $n �� ��������
     * @return string
     */
    function subcatalog($n) {

        $dis = null;
        
        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
        	return $hook;

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $where['parent_to'] = '=' . $n;

        // �� �������� ������� ��������
        $where['skin_enabled'] = "!='1'";

        // ����������
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'num'), array('limit' => 100), __CLASS__, __FUNCTION__);

        if(is_array($data))
            foreach($data as $row) {

                // ���������� ����������
				if (strpos($row['name'],"(")===false)
				{
					if ($row['id']==79) {
						//echo $row['id'];
						$this->set('catalogName',$row['name'].'&nbsp<a href="/shop/CID_417.html" title="���� ������ �������������">(���� ������ �������������)</a>');
						$this->set('width','');
						$this->set('catalogUid',$row['id']);
						$this->set('catalogTitle',$row['name']);
						// �������� ������
						$this->setHook(__CLASS__,__FUNCTION__,$row);
						
						// ���������� ������
						$dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
					} else if ($row['id']==135) {
							//echo $row['id'];
							$this->set('catalogName',$row['name'].'&nbsp<a href="/shop/CID_458.html" title="����� �������� �������� Karcher">(����� �������� �������� Karcher)</a>');
							$this->set('width','width: 55%;');
							$this->set('catalogUid',$row['id']);
							$this->set('catalogTitle',$row['name']);
							// �������� ������
							$this->setHook(__CLASS__,__FUNCTION__,$row);
						
							// ���������� ������
							$dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
						
					} else if ($row['id']!=79 && $row['id']!=417 && $row['id']!=135 && $row['id']!=458) {
						//echo ' '.$row['id'].' ';
						$this->set('catalogName',$row['name']);
						$this->set('width','');
						$this->set('catalogUid',$row['id']);
						$this->set('catalogTitle',$row['name']);
						// �������� ������
						$this->setHook(__CLASS__,__FUNCTION__,$row);
						
						// ���������� ������
						$dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
						
					}
					
				}
					
            }
			
        return $dis;
    }


	 function subcatalog2($n) {

        $dis="";
        
        // �������� ������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
        	return $hook;
        
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format=$this->cache_format;
        $PHPShopOrm->cache=$this->cache;
        $PHPShopOrm->debug=$this->debug;

        $where['parent_to']='='.$n;

        // ����������
        if($this->PHPShopSystem->ifValue($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled'))) {
            $where['servers']=" REGEXP 'i".$this->PHPShopSystem->getSerilizeParam('admoption.base_id')."i'";
        }

        $data=$PHPShopOrm->select(array('*'),$where,array('order'=>'num'),array('limit'=>100),__CLASS__,__FUNCTION__);

        if(is_array($data))
            foreach($data as $row) {

                // ���������� ����������
				if (strpos($row['name'],"(")!==false) {	
					$this->set('catalogName',$row['name']);
					$this->set('catalogUid',$row['id']);
					$this->set('catalogTitle',$row['name']);				
					// �������� ������
					$this->setHook(__CLASS__,__FUNCTION__,$row);
	
					// ���������� ������
					$dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
				}	
				
            }
        return $dis;
    }

    /**
     * �������� �����������
     * @param Int $id �� ��������
     * @return bool
     */
    function chek($n) {

        // ���� �������� � ������ ����, ������������ ���
        if ($this->memory_get('product_enabled.' . $n) == 1)
            return true;
        // ���� �������� � ������ ����, ����������� ����
        elseif ($this->memory_get('product_enabled.' . $n) == 2)
            return false;
        // ���� �������� � ������ ���, ������ � ��
        elseif (!empty($this->chek_catalog)) {

            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->cache_format = $this->cache_format;
            $PHPShopOrm->cache = $this->cache;
            $PHPShopOrm->debug = $this->debug;

            $where['parent_to'] = '=' . $n;

            // ����������
            if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
                $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            }

            $num = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 1), __CLASS__, __FUNCTION__);
            if (empty($num['id'])) {
                // ������� � ������
                $this->memory_set('product_enabled.' . $n, 1);
                return true;
            }
            else
                $this->memory_set('product_enabled.' . $n, 2);
        }
    }
	
	function custommenuoutput ($row,$i) {
		
		if ($row['id']=='288') {
			$PHPShopOrm->cache=true;
			$PHPShopOrm->cache_format=array('content'); // ������� ������ ������
			// ���������� ����������
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			$sql_1="select id,name,case parent_to when 37 then 1 when 44 then 2 when 16 then 3 when 5 then 4 when 36 then 5 end as category_id from ".$GLOBALS['SysValue']['base']['categories']." where parent_to in (37,44,16,5,36) and name not like '%(%)%' order by num";
			$sql_2="select id,name,case parent_to when 37 then 1 when 44 then 2 when 16 then 3 when 5 then 4 when 36 then 5 end as category_id from ".$GLOBALS['SysValue']['base']['categories']." where parent_to in (37,44,16,5,36) and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������</span>',true);
			$submenuhead='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_288.html" title="��������� � ������������">��������� � ������������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_37.html" title="���������">���������</a></span></li>';
			$submenuhead.='</ul>';
			//********************************************************************* 37 ***********************************************************************************
			//��������� ������� ������� ���� "�� ����"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();
			//$res1=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row1=array();

			//$db_rows1=mysql_num_rows($res1);

			$submenuhead.='<p id="catpage1_37" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_37" class="catalogPodcatalog1">';

			$submenuhead1_1='';
			$submenuhead2_1='';
			$submenuhead3_1='';
			$submenuhead4_1='';
			$submenuhead5_1='';	
			
			//while ($prod_row1 = mysql_fetch_array($res1,MYSQL_ASSOC)) {
			foreach ($res1 as $prod_row1) {	
				if ($prod_row1[category_id]==1) {
					$submenuhead1_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==2 && $prod_row1[id]==13) {
					$submenuhead2_1.='<li><span class="inside_menu_head" style="font-size:12px;"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a>&nbsp';
					$submenuhead2_1.='(<a href="/shop/CID_328.html" style="font-size:12px;" title="����������������">����������������</a>)</li>';
				} else if ($prod_row1[category_id]==2 && $prod_row1[id]!=328 && $prod_row1[id]!=13) {
					$submenuhead2_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==3) {
					$submenuhead3_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==4) {
					$submenuhead4_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==5) {
					$submenuhead5_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}				
			}
			
			$submenuhead.=$submenuhead1_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();
			//$res2=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row2=array();

			//$db_rows2=mysql_num_rows($res2);

			$submenuhead.='<p id="catpage2_37" class="menublock" >�� �������������:</p><ul id="ul_cat_page_2_37" class="catalogPodcatalog2">';

			$submenuhead1_2='';
			$submenuhead2_2='';
			$submenuhead3_2='';
			$submenuhead4_2='';
			$submenuhead5_2='';	
			
			//while ($prod_row2 = mysql_fetch_array($res2,MYSQL_ASSOC)) {
			foreach ($res2 as $prod_row2) {				
				if ($prod_row2[category_id]==1) {
					$submenuhead1_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==2) {
					$submenuhead2_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==3) {
					$submenuhead3_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==4) {
					$submenuhead4_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==5) {
					$submenuhead5_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}				
			}
			$submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';

			//$sql_1="select * from `phpshop_categories` where parent_to=44 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=44 and name like '%(%)%' order by num";
			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_288.html" title="��������� � ������������">��������� � ������������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_44.html" title="������������">������������</a></span></li>';
			$submenuhead.='</ul>';
			//********************************************************************* 44 ***********************************************************************************
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_44" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_44" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead2_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_44" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_44"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead2_2;			
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);					
			$submenuhead.='</li>';
			
			//$sql_1="select * from `phpshop_categories` where parent_to=16 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=16 and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>�������� ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>�������� ������������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_288.html" title="��������� � ������������">��������� � ������������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_16.html" title="�������� ������������ ��� ����������">�������� ������������ ��� ����������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 16 ***********************************************************************************
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_16" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_16" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead3_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_16" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_16"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead3_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);					
			$submenuhead.='</li>';
			//$sql_1="select * from `phpshop_categories` where parent_to=5 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=5 and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������� ��� ����������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������� ��� ����������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_288.html" title="��������� � ������������">��������� � ������������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_5.html" title="��������� ��� ����������">��������� ��� ����������</a></span></li>';
			$submenuhead.='</ul>';
			//********************************************************************* 48 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_5" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_5" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead4_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_5" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_5"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead4_2;			
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);					
			//$this->set('sub_li','</li>',true);	
			$submenuhead.='</li>';
			
			//$sql_1="select * from `phpshop_categories` where parent_to=36 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=36 and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;padding: 6px 0px 6px 15px;"><span style="font-size: 12px;line-height: 1;">��������, ����������</span>',true);						
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span style="font-size: 12px;line-height: 1;">��������, ����������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_288.html" title="��������� � ������������">��������� � ������������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_36.html" title="��������, ����������">��������, ����������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 36 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);
			
			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_36" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_36" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead5_1;	
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_36" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_36"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead5_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);					
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			$this->set('sub_li',$submenuhead,true);
			
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);				
			// �������� ������
			//$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));
			$this->memory_set('sub_li',$submenuhead);
		} 
		else if ($row['id']=='290') {

			//if ($this->memory_get('sub_li_290',true)==1) {

			// ���������� ����������
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			$sql_1="select id,name,case parent_to when 18 then 1 when 85 then 2 when 215 then 3 when 297 then 4 when 299 then 5 when 254 then 6 when 272 then 7 end as category_id from `phpshop_categories` where parent_to in (18,85,215,297,299,254,272) and name not like '%(%)%' order by num";
			$sql_2="select id,name,case parent_to when 18 then 1 when 85 then 2 when 215 then 3 when 297 then 4 when 299 then 5 when 254 then 6 when 272 then 7 end as category_id from `phpshop_categories` where parent_to in (18,85,215,297,299,254,272) and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>�������������</span>',true);
			$submenuhead='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>�������������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_18.html" title="�������������">�������������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 18 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();
			//$res1=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row1=array();

			//$db_rows1=mysql_num_rows($res1);

			$submenuhead.='<p id="catpage1_18" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_18" class="catalogPodcatalog1">';

			$submenuhead1_1='';
			$submenuhead2_1='';
			$submenuhead3_1='';			
			$submenuhead4_1='';
			$submenuhead5_1='';	
			$submenuhead6_1='';	
			$submenuhead7_1='';
			
			//while ($prod_row1 = mysql_fetch_array($res1,MYSQL_ASSOC)) {
			foreach ($res1 as $prod_row1) {	
				if ($prod_row1[category_id]==1) {
					$submenuhead1_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==2 && $prod_row1[id]==35) {
					$submenuhead2_1.='<li><span class="inside_menu_head" style="font-size:12px;"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a>&nbsp';
					$submenuhead2_1.='(<a href="/shop/CID_333.html" style="font-size:12px;" title="��������">��������</a>,&nbsp';
					$submenuhead2_1.='<a href="/shop/CID_334.html" style="font-size:12px;" title="���������">���������</a>)</span></li>';
				} else if ($prod_row1[category_id]==2 && $prod_row1[id]!=333 && $prod_row1[id]!=334 && $prod_row1[id]!=35) {
					$submenuhead2_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==3) {
					$submenuhead3_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==4) {
					$submenuhead4_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==5) {
					$submenuhead5_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}				
				if ($prod_row1[category_id]==6) {
					$submenuhead6_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==7) {
					$submenuhead7_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				
			}
			
			$submenuhead.=$submenuhead1_1;
			
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"	
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();			
			//$res2=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row2=array();

			//$db_rows2=mysql_num_rows($res2);

			$submenuhead.='<p id="catpage2_18" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_18"  class="catalogPodcatalog2">';
			$submenuhead1_2='';
			$submenuhead2_2='';
			$submenuhead3_2='';
			$submenuhead4_2='';
			$submenuhead5_2='';
			$submenuhead6_2='';
			$submenuhead7_2='';
			
			//while ($prod_row2 = mysql_fetch_array($res2,MYSQL_ASSOC)) {
			foreach ($res2 as $prod_row2) {
				if ($prod_row2[category_id]==1) {
					$submenuhead1_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==2) {
					$submenuhead2_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==3) {
					$submenuhead3_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==4) {
					$submenuhead4_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==5) {
					$submenuhead5_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==6) {
					$submenuhead6_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==7) {
					$submenuhead7_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
			}
			$submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select id,name from `phpshop_categories` where parent_to=85 and name not like '%(%)%'";
			//$sql_2="select id,name from `phpshop_categories` where parent_to=85 and name like '%(%)%' order by num";			
			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ���������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_85.html" title="��������">��������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 85 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_85" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_85" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			//}
			$submenuhead.=$submenuhead2_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_85" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_85"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead2_2;			
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);	
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select id,name from `phpshop_categories` where parent_to=215 and name not like '%(%)%'";
			//$sql_2="select id,name from `phpshop_categories` where parent_to=215 and name like '%(%)%' order by num";

			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_215.html" title="��������, ������������">��������, ������������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 215 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_215" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_215" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead3_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_215" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_215"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {			
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			//}
			$submenuhead.=$submenuhead3_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';

			//$sql_1="select id,name from `phpshop_categories` where parent_to=297 and name not like '%(%)%'";
			//$sql_2="select id,name from `phpshop_categories` where parent_to=297 and name like '%(%)%' order by num";

			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������� �������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_297.html" title="������� �������">������� �������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 215 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_297" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_297" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead4_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_297" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_297"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {			
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			//}
			$submenuhead.=$submenuhead4_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';

			//$sql_1="select id,name from `phpshop_categories` where parent_to=298 and name not like '%(%)%'";
			//$sql_2="select id,name from `phpshop_categories` where parent_to=298 and name like '%(%)%' order by num";

	
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>����������� �����������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_299.html" title="����������� �����������">c���������� �����������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 215 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_299" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_299" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead5_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_299" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_299"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {			
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			//}
			$submenuhead.=$submenuhead5_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������� ���� ������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_254.html" title="���������� ���� ������">���������� ���� ������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 215 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_254" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_254" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead6_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_254" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_254"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {			
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			//}
			$submenuhead.=$submenuhead6_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������� ����� � ������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_290.html" title="������� ��� ����� �� �������">������� ��� ����� �� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_272.html" title="������� ����� � ������">������� ����� � ������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 215 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_272" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_272" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead7_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_272" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_272"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {			
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			//}
			$submenuhead.=$submenuhead7_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';
			
			$this->set('sub_li',$submenuhead,true);
			//$this->memory_set('sub_li_290',$submenuhead);
			//$this->set('sub_li','</li>',true);

			//} else {
			//	$this->set('sub_li',$this->memory_get('sub_li_290'),true);
			//}
			
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);

			// �������� ������
			//$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));
			
		} 
		else if ($row['id']=='292') {
			// ���������� ����������
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			$sql_1="select id,name,case parent_to when 142 then 1 when 293 then 2 when 88 then 3 when 224 then 4 when 256 then 5 when 298 then 6 when 172 then 7 end as category_id from `phpshop_categories` where parent_to in (293,88,142,172,224,256,298) and name not like '%(%)%' order by num";
			$sql_2="select id,name,case parent_to when 142 then 1 when 293 then 2 when 88 then 3 when 224 then 4 when 256 then 5 when 298 then 6 when 172 then 7 end as category_id from `phpshop_categories` where parent_to in (293,88,142,172,224,256,298) and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������</span>',true);
			$submenuhead='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_142.html" title="��������">��������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 293 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();			
			//$res1=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row1=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_142" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_142" class="catalogPodcatalog1">';

			$submenuhead1_1='';
			$submenuhead2_1='';
			$submenuhead3_1='';
			$submenuhead4_1='';
			$submenuhead5_1='';
			$submenuhead6_1='';
			$submenuhead7_1='';
						
			//while ($prod_row1 = mysql_fetch_array($res1,MYSQL_ASSOC)) {
			foreach ($res1 as $prod_row1) {
				if ($prod_row1[category_id]==1 && $prod_row1[id]==145) {
					$submenuhead1_1.='<li><span class="inside_menu_head" style="font-size:12px;width: 99%;"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a>&nbsp';
					$submenuhead1_1.='(<a href="/shop/CID_426.html" style="font-size:12px;" title="���������� �������">���������� �������</a>,&nbsp';
					$submenuhead1_1.='<a href="/shop/CID_427.html" style="font-size:12px;" title="��������� ��� �������� �����">��������� ��� �������� �����</a>)</span></li>';
				} else if ($prod_row1[category_id]==1 && $prod_row1[id]==146) {
					$submenuhead1_1.='<li><span class="inside_menu_head" style="font-size:12px;width: 99%;"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a>&nbsp';
					$submenuhead1_1.='(<a href="/shop/CID_428.html" style="font-size:12px;" title="��������� ��� �������">��������� ��� �������</a>,&nbsp';
					$submenuhead1_1.='<a href="/shop/CID_429.html" style="font-size:12px;" title="�������� ��� �������">�������� ��� �������</a>)</span></li>';
				} else if ($prod_row1[category_id]==1 && $prod_row1[id]!=426 && $prod_row1[id]!=427 && $prod_row1[id]!=145 &&
						   $prod_row1[category_id]==1 && $prod_row1[id]!=428 && $prod_row1[id]!=429 && $prod_row1[id]!=146) {
					$submenuhead1_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==2) {
					$submenuhead2_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==3) {
					$submenuhead3_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==4) {
					$submenuhead4_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==5) {
					$submenuhead5_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==6) {
					$submenuhead6_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
				if ($prod_row1[category_id]==7) {
					$submenuhead7_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
			}
			$submenuhead.=$submenuhead1_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"	
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();			
			//$res2=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row2=array();

			//$db_rows2=mysql_num_rows($res2);

			$submenuhead.='<p id="catpage2_142" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_142"  class="catalogPodcatalog2">';

			$submenuhead1_2='';
			$submenuhead2_2='';
			$submenuhead3_2='';
			$submenuhead4_2='';
			$submenuhead5_2='';
			$submenuhead6_2='';
			$submenuhead7_2='';
						
			//while ($prod_row2 = mysql_fetch_array($res2,MYSQL_ASSOC)) {
			foreach ($res2 as $prod_row2) {
				if ($prod_row2[category_id]==1) {
					$submenuhead1_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==2) {
					$submenuhead2_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==3) {
					$submenuhead3_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==4) {
					$submenuhead4_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==5) {
					$submenuhead5_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';					
				}
				if ($prod_row2[category_id]==6) {
					$submenuhead6_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';
				}
				if ($prod_row2[category_id]==7) {
					$submenuhead7_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';
				}
			}
			$submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select * from `phpshop_categories` where parent_to=88 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=88 and name like '%(%)%' order by num";			

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>�����������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';							
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_293.html" title="���������">���������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 88 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_293" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_293" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead2_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_293" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_293"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead2_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select * from `phpshop_categories` where parent_to=142 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=142 and name like '%(%)%' order by num";			
			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ���������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������� �����������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';		
			$submenuhead.='<ul>';							
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_88.html" title="������� �����������">������� �����������</a><a href="/shop/CID_436.html" title="������� ������������">(������� ������������)</a></span></li>';
			$submenuhead.='</ul>';								
			//********************************************************************* 142 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_88" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_88" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead3_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_88" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_88"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead3_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';

			//$sql_1="select * from `phpshop_categories` where parent_to=172 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=172 and name like '%(%)%' order by num";			
			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>����������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';							
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_224.html" title="���������">���������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 172 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_224" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_224" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead4_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_224" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_224"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead4_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);						
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select * from `phpshop_categories` where parent_to=224 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=224 and name like '%(%)%' order by num";			
			
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������� ������������</span>';					
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';							
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_256.html" title="������� ������������">������� ������������</a></span></li>';
			$submenuhead.='</ul>';							
			//********************************************************************* 224 ***********************************************************************************					
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage1_256" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_256" class="catalogPodcatalog1">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead5_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"						
			//$res=$this->PHPShopOrm->query($sql_2);

			//$this->PHPShopOrm->clean();

			//$prod_row=array();

			//$db_rows=mysql_num_rows($res);

			$submenuhead.='<p id="catpage2_256" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_256"  class="catalogPodcatalog2">';

			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';					
			//}
			$submenuhead.=$submenuhead5_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';		
			//$this->set('sub_li',$submenuhead,true);						
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			
			//$sql_1="select * from `phpshop_categories` where parent_to=224 and name not like '%(%)%'";
			//$sql_2="select * from `phpshop_categories` where parent_to=224 and name like '%(%)%' order by num";

			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>��������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>����������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_298.html" title="����������">����������</a></span></li>';
			$submenuhead.='</ul>';
			//********************************************************************* 215 ***********************************************************************************
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);
			
			//$this->PHPShopOrm->clean();
			
			//$prod_row=array();
			
			//$db_rows=mysql_num_rows($res);
			
			$submenuhead.='<p id="catpage1_298" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_298" class="catalogPodcatalog1">';
			
			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';
			//}
			$submenuhead.=$submenuhead6_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"
			//$res=$this->PHPShopOrm->query($sql_2);
			
			//$this->PHPShopOrm->clean();
			
			//$prod_row=array();
			
			//$db_rows=mysql_num_rows($res);
			
			$submenuhead.='<p id="catpage2_298" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_298"  class="catalogPodcatalog2">';
			
			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//if ($prod_row[category_id]==2) {
			//	$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';
			//}
			//}
			$submenuhead.=$submenuhead6_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			$submenuhead.='</li>';
				
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������, ������������</span>',true);
			$submenuhead.='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>����������</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_292.html" title="������� ��� ��������������� �������">������� ��� ��������������� �������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_172.html" title="����������">����������</a></span></li>';
			$submenuhead.='</ul>';
			//********************************************************************* 224 ***********************************************************************************
			//��������� ������� ������� ���� "�� ����"
			//$res=$this->PHPShopOrm->query($sql_1);
			
			//$this->PHPShopOrm->clean();
			
			//$prod_row=array();
			
			//$db_rows=mysql_num_rows($res);
			
			$submenuhead.='<p id="catpage1_172" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_172" class="catalogPodcatalog1">';
			
			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';
			//}
			$submenuhead.=$submenuhead7_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"
			//$res=$this->PHPShopOrm->query($sql_2);
			
			//$this->PHPShopOrm->clean();
			
			//$prod_row=array();
			
			//$db_rows=mysql_num_rows($res);
			
			$submenuhead.='<p id="catpage2_172" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_172"  class="catalogPodcatalog2">';
			
			//while ($prod_row = mysql_fetch_array($res,MYSQL_ASSOC)) {
			//$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row[id].'.html" title="'.$prod_row[name].'">'.$prod_row[name].'</a></span></li>';
			//}
			$submenuhead.=$submenuhead7_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';			
			$this->set('sub_li',$submenuhead,true);
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);

			// �������� ������
			//$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));					
		}
		else if ($row['id']=='327') {
			// ���������� ����������
			$this->set('catalogId',$row['id']);
			//$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));
		
			$sql_1="select id,name,case parent_to when 328 then 1 end as category_id from `phpshop_categories` where parent_to in (328) and name not like '%(%)%' order by num";
			$sql_2="select id,name,case parent_to when 328 then 1 end as category_id from `phpshop_categories` where parent_to in (328) and name like '%(%)%' order by num";
		
			//$this->set('sub_li','<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>���������</span>',true);
			$submenuhead='<li onclick="document.getElementById(\'fade\').style.display=\'block\'" onmouseover="this.style.backgroundColor=\'#8ab943\'" onmouseout="this.style.backgroundColor=\'#e5e5e5\'" style="display:none;background: #e5e5e5;height: 24px;"><span>������� ������� ����� �</span>';
			$submenuhead.='<div class="submenuhead">';
			$submenuhead.='<div class="menutype">';
			$submenuhead.='<ul>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_327.html" title="">������� �� �������������</a></span></li>';
			$submenuhead.='<li><span class="inside_menu_head"><a href="/shop/CID_328.html" title="��������">������� ������� ����� �</a></span></li>';
			$submenuhead.='</ul>';
			//********************************************************************* 293 ***********************************************************************************
			//��������� ������� ������� ���� "�� ����"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_1;
			$res1=$this->PHPShopOrm->select();
			//$res1=$this->PHPShopOrm->query($sql_1);
		
			//$this->PHPShopOrm->clean();
		
			//$prod_row1=array();
		
			//$db_rows=mysql_num_rows($res);
		
			$submenuhead.='<p id="catpage1_328" class="menublock" >�� ����:</p><ul id="ul_cat_page_1_328" class="catalogPodcatalog1">';
		
			$submenuhead1_1='';
		
			//while ($prod_row1 = mysql_fetch_array($res1,MYSQL_ASSOC)) {
			foreach ($res1 as $prod_row1) {
				if ($prod_row1[category_id]==1) {
					$submenuhead1_1.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row1[id].'.html" title="'.$prod_row1[name].'">'.$prod_row1[name].'</a></span></li>';
				}
			}
			$submenuhead.=$submenuhead1_1;
			$submenuhead.='</ul>';
			//��������� ������� ������� ���� "�� �������������"
			$this->PHPShopOrm->cache = true;
			$this->PHPShopOrm->debug = $this->debug;
			$this->PHPShopOrm->sql=$sql_2;
			$res2=$this->PHPShopOrm->select();
			//$res2=$this->PHPShopOrm->query($sql_2);
		
			//$this->PHPShopOrm->clean();
		
			//$prod_row2=array();
		
			//$db_rows2=mysql_num_rows($res2);
		
			$submenuhead.='<p id="catpage2_327" class="menublock" >�� �������������:</p><ul id="ul_cat_page2_327"  class="catalogPodcatalog2">';
		
			$submenuhead1_2='';
		
			//while ($prod_row2 = mysql_fetch_array($res2,MYSQL_ASSOC)) {
			foreach ($res2 as $prod_row2) {
				if ($prod_row2[category_id]==1) {
					$submenuhead1_2.='<li><span class="inside_menu_head"><a href="/shop/CID_'.$prod_row2[id].'.html" title="'.$prod_row2[name].'">'.$prod_row2[name].'</a></span></li>';
				}
			}
			$submenuhead.=$submenuhead1_2;
			$submenuhead.='</ul>';
			$submenuhead.='</div>';
			$submenuhead.='</div>';
			//$this->set('sub_li',$submenuhead,true);
			//$this->set('sub_li','</li>',true);
			$submenuhead.='</li>';
			$this->set('sub_li',$submenuhead,true);
			$this->set('catalogTitle',$row['name']);
			$this->set('catalogName',$row['name']);
		
			// �������� ������
			//$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
		
			$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_4'));
		}
		else
		{
			// ���������� ����������
			$this->set('catalogId',$row['id']);
			$this->set('catalogI',$i);
			$this->set('catalogTemplates',$this->getValue('dir.templates').chr(47).$this->PHPShopSystem->getValue('skin').chr(47));

			//nah~ ����� ���� �������� �� 2 ����

			$this->set('catalogPodcatalog',$this->subcatalog($row['id']));
			
			$this->set('catalogPodcatalog2',$this->subcatalog2($row['id']));
			
			$this->set('catalogTitle',$row['name']);

			if ($row['id']=='228') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/stihl_small.png', 0, 'absmiddle','style="float: right; margin-right: 3px; margin-top: -4px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			}else if ($row['id']=='134') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/karcher_small.png', 0, 'absmiddle','style="float: right; margin-right: 1px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			}else if ($row['id']=='295') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/viking_small.png', 0, 'absmiddle','style="float: right; margin-right: 3px; margin-top: -5px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			}else if ($row['id']=='300') {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:11.5px !important;">'.$row['name'].PHPShopText::img('images/sale_small.png', 0, 'absmiddle','style="float: right; margin-right: 3px; margin-top: -4px;"').'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','2px');
				//$this->set('stihlimg','<div style="margin-top: -15px;margin-left: 90px;display: inline-block;width: 90px;height: 30px;background: url(\'../images/STIHL_Standard-orHKS-1001-1.png\') top center no-repeat;background-size:contain;"></div>');//PHPShopText::img('images/STIHL_Standard-orHKS-1001-1.png', 0, 'absmiddle')
			} else {
				$this->set('catalogName','<div style="display: inline;width: 214px;height: 34px;font-size:12px !important;">'.$row['name'].'</div>');
				$this->set('top_padding','15px');
				$this->set('bottom_padding','0px');			
			}
			
			// �������� ������
			$this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
			//echo $this->isAction('leftCatal_hook');
			// ���� ���� �����������
			if ($this->chek($row['id'])) {
				$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_3'));
			}
			// ���� ��� ������������
			else {
				if ($row['vid'] == 1) {
					$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_2'));
				} else {
					$dis.=$this->parseTemplate($this->getValue('templates.catalog_forma'));
				}
			}
		}
		return $dis;
	}

}

/**
 * ������� ������� �������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCartElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCartElement extends PHPShopElements {

    /**
     * �����������
     * @param bool $order ����� ������� � ������
     */
    function PHPShopCartElement($order = false) {

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();
        $this->order = $order;

        parent::PHPShopElements();
    }

    /**
     *  ���� �������
     */
    function miniCart() {

        // ���� ����� �� � �������� ��������� ������
        if ($this->PHPShopNav->notPath(array('order', 'done')) or !empty($this->order)) {

            if (!empty($_SESSION['compare']))
                $compare = $_SESSION['compare'];
            else
                $compare = array();
            $numcompare = 0;

            // ���� ���� ������ � �������
            if ($this->PHPShopCart->getNum() > 0)
                $this->set('orderEnabled', 'block');
            else
                $this->set('orderEnabled', 'none');

            // ���� ���� ���������
            if (count($compare) > 0) {
                if (is_array($compare)) {
                    foreach ($compare as $j => $v) {
                        $numcompare = count($compare);
                    }
                }
                $this->set('compareEnabled', 'block');
            } else {
                $numcompare = "--";
                $this->set('compareEnabled', 'none');
            }

            // �����������
            $this->set('tovarNow', $this->getValue('lang.cart_tovar_now'));
            $this->set('summaNow', $this->getValue('cart_summa_now'));
            $this->set('orderNow', $this->getValue('cart_order_now'));

            // ���������
            $this->set('numcompare', $numcompare);

            // �������
            $this->set('num', $this->PHPShopCart->getNum());

            // �����
            $this->set('sum', $this->PHPShopCart->getSum());
        }
        else
            $this->set('productValutaName', $this->PHPShopSystem->getDefaultValutaCode(true));

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__);
    }

}

/**
 * ������� ����� ������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCurrencyElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCurrencyElement extends PHPShopElements {

    /**
     * �����������
     */
    function PHPShopCurrencyElement() {
        parent::PHPShopElements();
        $this->setAction(array('post' => 'valuta'));
    }

    /**
     * ��������������� ����� ����� ������
     */
    function valuta() {
        $_SESSION['valuta'] = intval($_POST['valuta']);
        header("Location: " . $_SERVER['REQUEST_URI']);
    }

    /**
     * ����� ������ ������
     * @return string
     */
    function valutaDisp() {
        global $PHPShopValutaArray;

        if ($this->PHPShopNav->notPath('order')) {

            if (isset($_SESSION['valuta']))
                $valuta = $_SESSION['valuta'];
            else
                $valuta = $this->PHPShopSystem->getParam('dengi');

            $PHPShopValuta = $PHPShopValutaArray->getArray();

            if (is_array($PHPShopValuta))
                foreach ($PHPShopValuta as $v) {
                    if ($valuta == $v['id'])
                        $sel = "selected";
                    else
                        $sel = false;
                    $value[] = array($v['name'], $v['id'], $sel);
                }

            // ���������� ����������
            $this->set('leftMenuName', '������');
            $select = PHPShopText::select('valuta', $value, 100, "none", false, "ChangeValuta()");
            $this->set('leftMenuContent', PHPShopText::form($select, 'ValutaForm'));

            // �������� ������
            $this->setHook(__CLASS__, __FUNCTION__, $PHPShopValuta);

            // ���������� ������
            $dis = $this->parseTemplate($this->getValue('templates.valuta_forma'));
            return $dis;
        }
    }

}

/**
 * ������� ������ �����
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCloudElement
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopCloudElement extends PHPShopElements {

    var $debug = false;

    /**
     * ����� ������� ��� �������
     * @var int
     */
    var $page_limit = 100;

    /**
     * ����� ���� ��� ������
     * @var int
     */
    var $word_limit = 30;

    /**
     * ���� ������ ������ �����
     * @var string
     */
    var $color = "0x518EAD";

    /**
     * �����������
     */
    function PHPShopCloudElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopElements();
    }

    /**
     * ������ �����
     * @param array $row ������ ������
     * @return string
     */
    function index($row = null) {
        $disp = $dis = $CloudCount = $ArrayWords = $CloudCountLimit = null;
        $ArrayLinks = array();

        // �������� ������ � ������ �������
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
            return $hook;

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.cloud_enabled')) {
            switch ($GLOBALS['SysValue']['nav']['nav']) {

                case(""):
                    $tip = "search";
                    $str = array('enabled' => "='1'", 'keywords' => " !=''");
                    break;

                case("CID"):
                    $tip = "words";
                    if (empty($row))
                        return false;
                    else
                        $data = $row;
                    break;

                case("UID"):
                    $tip = "words";
                    if (empty($row))
                        return false;
                    else
                        $data[] = $row;
                    break;

                default:
                    $tip = "search";
                    $str = array('enabled' => "='1'", 'keywords' => " !=''");
                    break;
            }

            if (empty($row))
                $data = $this->PHPShopOrm->select(array('keywords', 'id'), $str, false, array("limit" => $this->page_limit), __CLASS__, __FUNCTION__);

            if (is_array($data))
                foreach ($data as $row) {
                    $explode = explode(", ", $row['keywords']);
                    foreach ($explode as $ev)
                        if (!empty($ev)) {
                            $ArrayWords[] = $ev;
                            $ArrayLinks[$ev] = $row['id'];
                        }
                }
            if (is_array($ArrayWords))
                foreach ($ArrayWords as $k => $v) {
                    $count = array_keys($ArrayWords, $v);
                    $CloudCount[$v]['size'] = count($count);
                }

            // ������� ������ ��������
            $i = 0;
            if (is_array($CloudCount))
                foreach ($CloudCount as $k => $v) {
                    if ($i < $this->word_limit)
                        $CloudCountLimit[$k] = $v;
                    $i++;
                }

            if (is_array($CloudCountLimit))
                foreach ($CloudCountLimit as $key => $val) {

                    // ������ ����
                    $key = str_replace('"', '', $key);
                    $key = str_replace("'", '', $key);
                    if ($tip == "words")
                        $disp.='<h1>' . $key . '</h1> ';
                    else
                        $disp.="<a href='/search/?words=" . $key . "' style='font-size:12pt;'>$key</a>";
                }

            // ������ ����
            $disp = str_replace('\n', '', $disp);

            if ($tip == "search" and !empty($disp))
                $disp = '
<div id="wpcumuluscontent">�������� ����...</div><script type="text/javascript">
var dd=new Date();
 var so = new SWFObject("/stockgallery/tagcloud.swf?rnd="+dd.getTime(), "tagcloudflash", "180", "180", "9", "' . $this->color . '");
so.addParam("wmode", "transparent");
so.addParam("allowScriptAccess", "always");
so.addVariable("tcolor", "' . $this->color . '");
so.addVariable("tspeed", "150");
so.addVariable("distr", "true");
so.addVariable("mode", "tags");
so.addVariable("tagcloud", "<tags>' . $disp . '</tags>");
so.write("wpcumuluscontent");</script>
';

            // ������ ����������
            $disp = str_replace('\n', '', $disp);
            $disp = str_replace(chr(13), '', $disp);
            $disp = str_replace(chr(10), '', $disp);

            // ���������� ����������
            if (!empty($disp)) {
                $this->set('leftMenuName', __("������ �����"));
                $this->set('leftMenuContent', $disp);

                // �������� ������ � ����� �������
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                // ���������� ������
                $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
            }
            return $dis;
        }
    }

}

?>