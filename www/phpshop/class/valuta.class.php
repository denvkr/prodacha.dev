<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__)."/obj.class.php");
}
require_once(dirname(__FILE__)."/array.class.php");

/**
 * Библиотека валют
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopValuta extends PHPShopObj {

    /**
     * Констуруктор
     * @param int $objID ИД валюты
     */
    function PHPShopValuta($objID) {
        $this->objID=$objID;
        $this->cache=true;
        $this->objBase=$GLOBALS['SysValue']['base']['currency'];
        parent::PHPShopObj();
    }

    /**
     * Вывод имени валюты
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Вывод ISO валюты
     * @return string
     */
    function getIso() {
        return parent::getParam("iso");
    }

    /**
     * Вывод курса валюты
     * @return float
     */
    function getKurs() {
        return parent::getParam("kurs");
    }

    /**
     * Вывод кода валюты
     * @return string
     */
    function getCode() {
        return parent::getParam("code");
    }


    /**
     * Массив всех значений по ключу ISO
     * Пример: PHPShopValuta::getAll()
     * @return array
     */
    function getAll() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
        $PHPShopOrm->cache=true;
        $data=$PHPShopOrm->select(array('*'),false,false,array('limit'=>100));
        if(is_array($data))
            foreach($data as $row) {
                $id=$row['id'];
                $iso=$row['iso'];
                $array[$iso]=$iso;
            }

        return $data;
    }

    function getArray() {
        return $this->getAll();
    }
}

/**
 * Массив данных по валютам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopValutaArray extends PHPShopArray {

    function PHPShopValutaArray() {
        $this->objBase=$GLOBALS['SysValue']['base']['currency'];
        parent::PHPShopArray('id',"name",'code','iso','kurs');
    }
}
?>