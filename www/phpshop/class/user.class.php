<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__)."/obj.class.php");
}

/**
 * Библиотека данных администраторов
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopObj
 */
class PHPShopUser extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД пользователя
     */
    function PHPShopUser($objID) {
        $this->objID=$objID;
        $this->cache=true;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name27'];
        parent::PHPShopObj();
    }

     /**
     * Вывод данных по ключу
     * @param string $str ключ
     * @return string 
     */
    function getParam($str){
        return str_replace("\"","&quot;",parent::getParam($str));
    }

    /**
     * Вывод данных по ключу
     * @param string $str ключ
     * @return string 
     */
    function getValue($str){
        return $this->getParam($str);
    }

    /**
     * Вывод имени пользователя
     * @return string
     */
    function getName() {
        return $this->getParam("name");
    }

    /**
     * Вывод ID статуса
     * @return int 
     */
    function getStatus() {
        return $this->getParam("status");
    }

    /**
     * Вывод названия статуса
     * @return string 
     */
    function getStatusName(){
        $PHPShopUserStatus = new PHPShopUserStatus($this->getStatus());
        return $PHPShopUserStatus->getParam("name");
    }
    
    /**
     * Вывод размера скидки
     * @return float 
     */
    function getDiscount(){
        $PHPShopUserStatus = new PHPShopUserStatus($this->getStatus());
        return $PHPShopUserStatus->getDiscount();
        
    }

}

/**
 * Библиотека данных пользователей
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopUserStatus extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД статуса пользователя
     * @param array $import_data массив импорта данных
     */
    function PHPShopUserStatus($objID,$import_data = null) {
        $this->objID=$objID;
        $this->cache=true;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name28'];
        parent::PHPShopObj('id',$import_data);
    }

    /**
     * Вывод колонки прайса у пользователя
     * @return int
     */
    function getPrice() {
        return parent::getParam("price");
    }

    /**
     * Вывод скидки у пользователя
     * @return float
     */
    function getDiscount() {
        return parent::getParam("discount");
    }


}

/**
 * Библиотека функций для пользователей
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopClass
 */
class PHPShopUserFunction {

    /**
     * Проверка наивысшей скидки у пользователя
     * @param float $mysum стоимость заказа
     * @return array
     */
    function ChekDiscount($mysum) {
        global $PHPShopSystem;

        $maxsum=0;
        $userdiscount=0;
        $maxdiscount=0;

        $sql="select * from ".$GLOBALS['SysValue']['base']['table_name23']." where sum < '$mysum' and enabled='1'";
        $result=mysql_query($sql);
        while($row = mysql_fetch_array($result)) {
            $sum=$row['sum'];
            if($sum>$maxsum) {
                $maxsum=$sum;
                $maxdiscount=$row['discount'];
            }
        }

        if(!empty($_SESSION['UsersStatus'])) {
            $PHPShopUserStatus = new PHPShopUserStatus($_SESSION['UsersStatus']);
            $userdiscount = $PHPShopUserStatus->getDiscount();
        } else $userdiscoun=0;

        if($userdiscount>$maxdiscount) $maxdiscount=$userdiscount;

        $sum=$mysum-($mysum*@$maxdiscount/100);
        $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
        $array=array(0+@$maxdiscount,number_format($sum,$format,".",""));

        return $array;
    }
}
?>