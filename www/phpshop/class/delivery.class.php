<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__)."/obj.class.php");

/**
 * Библиотека доставки
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopDelivery extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД доставки
     */
    function PHPShopDelivery($objID=false) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['delivery'];
        parent::PHPShopObj();
    }

    /**
     * Расчет стоимости доставки
     * @param float $sum сумма заказа
     * @param float $weight вес заказа
     * @return float
     */
    function getPrice($sum,$weight=0) {
        $row = $this->objRow;
        if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
            return 0;
        } else {
            if ($row['taxa']>0) {
                $addweight=$weight-500;
                if ($addweight<0) {
                    $addweight=0;
                }
                $addweight=ceil($addweight/500)*$row['taxa'];
                if ($this->objID==69 && $sum>=1000 && $sum<5000) {
                   $endprice=300+$addweight; 
                } else if ($this->objID==69 && $sum>=5000) {
                   $endprice=0+$addweight;                     
                } else {
                    $endprice=$row['price']+$addweight;                    
                }
                return $endprice;
            } else {
                if ($this->objID==69 && $sum>=1000 && $sum<5000) {
                    return 300;
                } else if ($this->objID==69 && $sum>=5000) {
                    return 0;                    
                }    else {
                    return $row['price'];
                }
            }
        }
    }

    /**
     * Вывод города доставки
     * @return string
     */
    function getCity() {
        return parent::getParam("city");
    }

    function getPriceDefault() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $row=$PHPShopOrm->select(array('price'),array('flag'=>"='1'",'enabled'=>"='1'"),false,array('limit'=>1));
        return $row['price'];
    }
}


/**
 * Массив доставок
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopDeliveryArray extends PHPShopArray {

    function PHPShopDeliveryArray() {
        $this->order=array('order'=>'id');
        $this->objBase=$GLOBALS['SysValue']['base']['delivery'];
        parent::PHPShopArray('id',"city",'price','enabled','PID','is_folder');
    }
}
?>