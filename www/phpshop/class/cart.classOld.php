<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__)."/product.class.php");
    require_once(dirname(__FILE__)."/security.class.php");
}

/**
 * ������� �������
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopCart {
    var $_CART;
    /**
     * ����� �������� �������� �� ������
     */
    var $store_check=true;

    /**
     * �����������
     */
    function PHPShopCart() {
        global $PHPShopSystem,$PHPShopValutaArray;

        // ����� �������� �������� �� ������
        if($PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 1)
            $this->store_check=false;

        if(!class_exists('PHPShopProduct')) {
            PHPShopObj::loadClass('array');
            PHPShopObj::loadClass('product');
        }

        $this->Valuta=$PHPShopValutaArray->getArray();

        $this->_CART=&$_SESSION['cart'];
    }

    /**
     * ���������� � ������� ������
     * @param int $objID �� ������
     */
    function add($objID,$num,$parentID=false) {

        // ������ �� ������
        $objProduct = new PHPShopProduct($objID);

        // ���� ������� ������
        if (!empty($_REQUEST['addname'])) {
            $xid = $objID.'-'.$_REQUEST['addname'];
        }
        else $xid=$objID;

        // ������ �������
        $cart=array(
                "id"=>intval($objID),
                "name"=>PHPShopSecurity::CleanStr($objProduct->getParam("name")),
                "price"=>PHPShopProductFunction::GetPriceValuta($objID,$objProduct->getParam("price"),$objProduct->getParam("baseinputvaluta"),true),
                "uid"=>$objProduct->getParam("uid"),
                "num"=>abs($this->_CART[$objID]['num']+$num),
                "weight"=>$objProduct->getParam("weight"),
                "ed_izm"=>$objProduct->getParam("ed_izm"),
                "pic_small"=>$objProduct->getParam("pic_small"),
                "parent"=>intval($parentID),
                "user"=>$objProduct->getParam("user"));

        // �������� ���-�� ������ �� ������
        if($this->store_check) {
            if($cart['num'] > $objProduct->getParam("items"))
                $cart['num'] = $objProduct->getParam("items");
        }

        // ���� ������� ������
        if (!empty($_REQUEST['addname'])) $cart['name']=$cart['name'].'-'.$_REQUEST['addname'];

        if(!empty($cart['num']))
            $this->_CART[$xid]=$cart;
    }

    /**
     * �������� �� ������� ������
     * @param int $objID �� ������
     */
    function del($objID) {
        unset($this->_CART[$objID]);
    }

    /**
     * ������� ���� �������
     */
    function clean() {
        unset($this->_CART);
        $_SESSION['cart']=null;
    }

    /**
     * ����� ���������� �������
     * @return int
     */
    function getNum() {
        $num=0;
        if(is_array($this->_CART))
            foreach($this->_CART as $val) $num+=$val['num'];
        return $num;
    }

    /**
     * ��� �������
     * @return float
     */
    function getWeight() {
        $weight=0;
        foreach($this->_CART as $val) $weight+=$val['num']*$val['weight'];
        return $weight;
    }

    /**
     * �������������� ���������� ������ � �������
     * @param int $objID �� ������
     * @param int $num ���������� ������
     * @return int
     */
    function edit($objID,$num) {

        // ������ �� ������
        $objProduct = new PHPShopProduct(abs($objID));

        $this->_CART[$objID]['num']=abs($num);
        if(empty($this->_CART[$objID]['num'])) unset($this->_CART[$objID]);

        // �������� ���-�� ������ �� ������
        if($this->store_check) {
            if($this->_CART[$objID]['num'] > $objProduct->getParam("items"))
                $this->_CART[$objID]['num'] = $objProduct->getParam("items");
        }

        return $num;
    }

    /**
     * ����� �������
     * @param bool $order �������� ������
     * @return float
     */
    function getSum($order=true) {
        global $PHPShopSystem;

        $sum=0;
        if(is_array($this->_CART))
            foreach($this->_CART as $val) $sum+=$val['num']*$val['price'];
        $format=$PHPShopSystem->getSerilizeParam("admoption.price_znak");
        
        // ���� ������� ������ ������
        if($order and isset($_SESSION['valuta'])) {
            $valuta=$_SESSION['valuta'];
            $kurs=$this->Valuta[$valuta]['kurs'];
        }
        else $kurs=$PHPShopSystem->getDefaultValutaKurs();

        // �������� �� �����
        return number_format($sum*$kurs,$format,'.','');
    }

    /**
     * ������������ ������ ������ ������� � �������
     * @global obj $PHPShopOrder
     * @param string $function ��� ������� ������� ������
     * @param array $option ����� �������������� ������
     * @return string
     */
    function display($function,$option=false) {
        global $PHPShopOrder;
        $list=null;

        // ������ ������ � ������ ������ ��� ������
        if(is_array($this->_CART)) {
            foreach($this->_CART as $key=>$val) {
                $cart[$key]['price']=$PHPShopOrder->ReturnSumma($val['price'],0);
                $cart[$key]['total']=$PHPShopOrder->ReturnSumma($val['price']*$val['num'],0);
            }
        }

        if(is_array($this->_CART))
            foreach($this->_CART as $k=>$v)
                if(function_exists($function)) {
                    $option['xid']=$k;
                    $list.= call_user_func_array($function,array($v,$option));
                }

        return $list;
    }

    /**
     * ����� ����� ������� � ���������
     * @global obj $PHPShopOrder
     * @return float
     */
    function getTotal() {
        global $PHPShopOrder;
        return $PHPShopOrder->ReturnSumma($this->getSum(),$PHPShopOrder->ChekDiscount($this->getSum()));
    }

    /**
     * ������ �������
     * @return array
     */
    function getArray(){
        return $this->_CART;
    }

}

?>