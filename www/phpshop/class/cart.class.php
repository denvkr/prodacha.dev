<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/product.class.php");
    require_once(dirname(__FILE__) . "/security.class.php");
}

/**
 * ������� �������
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopClass
 */
class PHPShopCart {

    var $_CART;

    /**
     * ����� �������� �������� �� ������
     */
    var $store_check = true;

    /**
     * �����������
     */
    function PHPShopCart($import_cart = false) {
        global $PHPShopSystem, $PHPShopValutaArray;

        // ����� �������� �������� �� ������
        if ($PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 1)
            $this->store_check = false;

        if (!class_exists('PHPShopProduct')) {
            PHPShopObj::loadClass('array');
            PHPShopObj::loadClass('product');
        }

        $this->Valuta = $PHPShopValutaArray->getArray();

        if ($import_cart)
            $this->_CART = $import_cart;
        else
            $this->_CART = &$_SESSION['cart'];
			
		$this->debug = false;
    }

    /**
     * ���������� � ������� ������
     * @param int $objID �� ������
     */
    function add($objID, $num, $parentID = false) {

        // ������ �� ������
        $objProduct = new PHPShopProduct($objID);

        // ���� ������� ������
        if (!empty($_REQUEST['addname'])) {
            $xid = $objID . '-' . $_REQUEST['addname'];
        }
        else
            $xid = $objID;

        // ��� ������
        $name = PHPShopSecurity::CleanStr($objProduct->getParam("name"));

        // �������� �� ������������� ������
        if (!empty($name)) {

        // ������ ������� nah
        $cart = array(
                "id" => intval($objID),
                "name" => $name,
                "price" => PHPShopProductFunction::GetPriceValuta($objID,$objProduct->getParam("price"),$objProduct->getParam("baseinputvaluta"),true),
				"price2" => PHPShopProductFunction::GetPriceValuta($objID,$objProduct->getParam("price2"),$objProduct->getParam("baseinputvaluta"),true),
        		"price3" => PHPShopProductFunction::GetPriceValuta($objID,$objProduct->getParam("price3"),$objProduct->getParam("baseinputvaluta"),true),        		
                "uid" => $objProduct->getParam("uid"),
                "num" => abs($this->_CART[$objID]['num'] + $num),
                "weight" => $objProduct->getParam("weight"),
                "ed_izm" => $objProduct->getParam("ed_izm"),
                "pic_small" => $objProduct->getParam("pic_small"),
                "parent" => intval($parentID),
                "user" => $objProduct->getParam("user"));

            // �������� ���-�� ������ �� ������
            if ($this->store_check) {
                if ($cart['num'] > PHPShopSecurity::TotalClean($objProduct->getParam("items"),1))
                    $cart['num'] =PHPShopSecurity::TotalClean($objProduct->getParam("items"),1);
            }

            // ���� ������� ������
            if (!empty($_REQUEST['addname']))
                $cart['name'] = $cart['name'] . '-' . $_REQUEST['addname'];

            if (!empty($cart['num']))
                $this->_CART[$xid] = $cart;
        }
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
        $_SESSION['cart'] = null;
    }

    /**
     * ����� ���������� �������
     * @return int
     */
    function getNum() {
        $num = 0;
        if (is_array($this->_CART))
            foreach ($this->_CART as $val)
                $num+=$val['num'];
        return $num;
    }

    /**
     * ��� �������
     * @return float
     */
    function getWeight() {
        $weight = 0;
        foreach ($this->_CART as $val)
            $weight+=$val['num'] * $val['weight'];
        return $weight;
    }

    /**
     * �������������� ���������� ������ � �������
     * @param int $objID �� ������
     * @param int $num ���������� ������
     * @return int
     */
    function edit($objID, $num) {

        // ������ �� ������
        $objProduct = new PHPShopProduct(abs($objID));

        // ���� ������� �� �����
        if (is_array($this->_CART)) {
            $this->_CART[$objID]['num'] = abs($num);
            if (empty($this->_CART[$objID]['num']))
                unset($this->_CART[$objID]);

            // �������� ���-�� ������ �� ������
            if ($this->store_check) {
                if ($this->_CART[$objID]['num'] > $objProduct->getParam("items"))
                    $this->_CART[$objID]['num'] = $objProduct->getParam("items");
            }
            return $num;
        }
    }

    /**
     * ����� �������
     * @param bool $order �������� ������
     * @return float
     */
    function getSum($order = true) {
        global $PHPShopSystem;

        $sum = 0;
        if (is_array($this->_CART))
            foreach ($this->_CART as $val)
			{
				if ( ($_COOKIE['sincity']=="sp") AND ($val['price2']!=0) ) {
					$realprice=$val['price2'];
				} else if( ($_COOKIE['sincity']=="chb") AND ($val['price3']!=0) ) {
					$realprice=$val['price3'];
				}
				else {
					$realprice=$val['price'];
				}
				/*				 
				 if ( ($_COOKIE['sincity']=="sp") AND ($val['price2']!=0) )
					 $realprice=$val['price2'];
				 else
					 $realprice=$val['price'];
				 */
				 $sum+=$val['num']*$realprice;
				 
				 
			}	 
        $format=$PHPShopSystem->getSerilizeParam("admoption.price_znak");
        
        // ���� ������� ������ ������
        if ($order and isset($_SESSION['valuta'])) {
            $valuta = $_SESSION['valuta'];
            $kurs = $this->Valuta[$valuta]['kurs'];
        }
        else
            $kurs = $PHPShopSystem->getDefaultValutaKurs();

        // �������� �� �����
        return number_format($sum * $kurs, $format, '.', '');
    }

    /**
     * ������������ ������ ������ ������� � �������
     * @global obj $PHPShopOrder
     * @param string $function ��� ������� ������� ������
     * @param array $option ����� �������������� ������
     * @return string
     */
    function display($function, $option = false) {
        global $PHPShopOrder;
        $list = null;

        // ������ ������ � ������ ������ ��� ������
        if (is_array($this->_CART)) {
            foreach ($this->_CART as $key => $val) {
				
            	if ( ($_COOKIE['sincity']=="sp") AND ($val['price2']!=0) ) {
					$realprice=$val['price2'];
				} else if( ($_COOKIE['sincity']=="chb") AND ($val['price3']!=0) ) {
					$realprice=$val['price3'];
				}
				else {
					$realprice=$val['price'];
				}
			
                $cart[$key]['price']=$PHPShopOrder->ReturnSumma($realprice,0);
                $cart[$key]['total']=$PHPShopOrder->ReturnSumma($realprice*$val['num'],0);
            }
        }

        if (is_array($this->_CART))
            foreach ($this->_CART as $k => $v)
                if (function_exists($function)) {
                    $option['xid'] = $k;
                    $list.= call_user_func_array($function, array($v, $option));
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
        return $PHPShopOrder->ReturnSumma($this->getSum(), $PHPShopOrder->ChekDiscount($this->getSum()));
    }

    /**
     * ������ �������
     * @return array
     */
    function getArray() {
        return $this->_CART;
    }

}

?>