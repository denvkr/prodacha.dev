<?php

/**
 * ���������� ���������
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopNewprice
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopNewprice extends PHPShopShopCore {

    var $debug = false;
    var $cache = true;
    var $cache_format = array('content', 'yml_bid_array');

    /**
     * ����� �������
     * @var int 
     */
    var $cell;

    /**
     * �����������
     */
    function PHPShopNewprice() {

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
        $this->objPath = './newprice_';

        // ������
        $this->set('productValutaName', $this->currency());

        $this->set('catalogCategory', $this->lang('newprice'));

        // ���������� �����
        if (empty($this->cell))
            $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

        // ������ ����������
        $order = $this->query_filter("price_n!=''");

        // ������� ������
        if (is_array($order)) {
            $this->dataArray = parent::getListInfoItem(array('*'), array('price_n' => "!=''", 'enabled' => "='1'"), $order, __CLASS__, __FUNCTION__);
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

        // ��������� � ������ ������ � ��������
        $grid = $this->product_grid($this->dataArray, $this->cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // ���������
        $this->title = $this->lang('newprice') . " - " . $this->PHPShopSystem->getParam('title');

        // �������� ������
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // ���������� ������
        $this->parseTemplate($this->getValue('templates.product_page_spec_list'));
    }

}

?>