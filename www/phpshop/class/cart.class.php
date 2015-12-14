<?php
if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/product.class.php");
    require_once(dirname(__FILE__) . "/security.class.php");
}

/**
 * Корзина товаров
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopClass
 */
class PHPShopCart {

    var $_CART;

    /**
     * Режим проверки остатков на складе
     */
    var $store_check = true;

    /**
     * Конструктор
     */
    function PHPShopCart($import_cart = false) {
        global $PHPShopSystem, $PHPShopValutaArray;
        //$this->debug = true;
        //print_r($PHPShopSystem);
        // Режим проверки остатков на складе
        if ($PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 1)
            $this->store_check = false;

        if (!class_exists('PHPShopProduct')) {
            PHPShopObj::loadClass('array');
            PHPShopObj::loadClass('product');
        }

        $this->Valuta = $PHPShopValutaArray->getArray();
        //echo 2;
        if ($import_cart)
            $this->_CART = $import_cart;
        else
            $this->_CART = &$_SESSION['cart'];
    }

    /**
     * Добавление в корзину товара
     * @param int $objID ИД товара
     */
    function add($objID, $num, $parentID = false) {

        // Данные по товару
        $objProduct = new PHPShopProduct($objID);

        // Учет свойств товара
        if (!empty($_REQUEST['addname'])) {
            $xid = $objID . '-' . $_REQUEST['addname'];
        }
        else
            $xid = $objID;

        // Имя товара
        $name = PHPShopSecurity::CleanStr($objProduct->getParam("name"));

        // Проверка на существование товара
        if (!empty($name)) {

        // Массив корзины nah
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

            // Проверка кол-ва товара на складе
            if ($this->store_check) {
                if ($cart['num'] > PHPShopSecurity::TotalClean($objProduct->getParam("items"),1))
                    $cart['num'] =PHPShopSecurity::TotalClean($objProduct->getParam("items"),1);
            }

            // Учет свойств товара
            if (!empty($_REQUEST['addname']))
                $cart['name'] = $cart['name'] . '-' . $_REQUEST['addname'];

            if (!empty($cart['num']))
                $this->_CART[$xid] = $cart;
        }
    }

    /**
     * Удаление из корзины товара
     * @param int $objID ИД товара
     */
    function del($objID) {
        unset($this->_CART[$objID]);
    }

    /**
     * Очистка всей корзины
     */
    function clean() {
        unset($this->_CART);
        $_SESSION['cart'] = null;
    }

    /**
     * Вывод количества товаров
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
     * Вес корзины
     * @return float
     */
    function getWeight() {
        $weight = 0;
        foreach ($this->_CART as $val)
            $weight+=$val['num'] * $val['weight'];
        return $weight;
    }

    /**
     * Редактирование количества товара в корзине
     * @param int $objID ИД товара
     * @param int $num количество товара
     * @return int
     */
    function edit($objID, $num) {

        // Данные по товару
        $objProduct = new PHPShopProduct(abs($objID));

        // Если корзина не пуста
        if (is_array($this->_CART)) {
            $this->_CART[$objID]['num'] = abs($num);
            if (empty($this->_CART[$objID]['num']))
                unset($this->_CART[$objID]);

            // Проверка кол-ва товара на складе
            if ($this->store_check) {
                if ($this->_CART[$objID]['num'] > $objProduct->getParam("items"))
                    $this->_CART[$objID]['num'] = $objProduct->getParam("items");
            }
            return $num;
        }
    }

    /**
     * Сумма корзины
     * @param bool $order параметр заказа
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
        
        // Если выбрана другая валюта
        if ($order and isset($_SESSION['valuta'])) {
            $valuta = $_SESSION['valuta'];
            $kurs = $this->Valuta[$valuta]['kurs'];
        }
        else
            $kurs = $PHPShopSystem->getDefaultValutaKurs();

        // Поправки по курсу
        return number_format($sum * $kurs, $format, '.', '');
    }

    /**
     * Шаблонизатор вывода списка товаров в корзине
     * @global obj $PHPShopOrder
     * @param string $function имя функции шаблона вывода
     * @param array $option масив дополнительных данных
     * @return string
     */
    function display($function, $option = false) {
        global $PHPShopOrder;
        $list = null;

        // Расчет данных с учетом скидки для заказа
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
     * Итого сумма корзины с доставкой
     * @global obj $PHPShopOrder
     * @return float
     */
    function getTotal() {
        global $PHPShopOrder;
        return $PHPShopOrder->ReturnSumma($this->getSum(), $PHPShopOrder->ChekDiscount($this->getSum()));
    }

    /**
     * Массив корзины
     * @return array
     */
    function getArray() {
        return $this->_CART;
    }

}

?>