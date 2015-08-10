<?php

if (!defined("OBJENABLED")) {
    require_once(dirname(__FILE__) . "/obj.class.php");
    require_once(dirname(__FILE__) . "/array.class.php");
}

/**
 * Библиотека данных по товарам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopProduct extends PHPShopObj {

    /**
     * Конструктор
     * @param Int $objID ИД товара
     */
    function PHPShopProduct($objID) {
        $this->objID = $objID;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name2'];
        $this->cache = true;
        $this->debug = false;
        $this->cache_format = array('content');

        // Учет подтипов для выборки по артикулу
        if (PHPShopProductFunction::true_parent($objID))
            $var = 'uid';
        else {
            $objID = PHPShopSecurity::TotalClean($objID, 1);
            $var = 'id';
        }

        parent::PHPShopObj($var);
    }

    /**
     * Имя товара
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * ИД валюты товара
     * @return int
     */
    function getValutaID() {
        return parent::getParam("baseinputvaluta");
    }

    /**
     * Цена товара
     * @return float 
     */
    function getPrice() {
        $price_array = array($this->objRow['price'], $this->objRow['price2'], $this->objRow['price3'], $this->objRow['price4'], $this->objRow['price5']);
        return PHPShopProductFunction::GetPriceValuta($this->objID, $price_array, $this->objRow['baseinputvaluta']);
    }

    /**
     * Изображенеи товара
     * @return string 
     */
    function getImage() {
        return parent::getParam("pic_small");
    }

}

/**
 * Массив данных по товарам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopProductArray extends PHPShopArray {

    /**
     * Конструктор
     * @param string $sql SQL условие выборки
     */
    function PHPShopProductArray($sql = false) {
        $this->objSQL = $sql;
        $this->objBase = $GLOBALS['SysValue']['base']['table_name2'];
        parent::PHPShopArray('id', 'uid', 'name', 'category', 'price', 'price_n', 'sklad', 'odnotip', 'vendor', 'title_enabled', 'datas', 'page', 'user', 'descrip_enabled', 'keywords_enabled', 'pic_small', 'pic_big', 'parent', 'baseinputvaluta');
    }

}

/**
 * Библиотека функций по товарам
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopClass
 */
class PHPShopProductFunction {

    function getLink() {
        $Arg = func_get_args();
        $link = '/shop/UID_' . $Arg[0] . '.html';
        return $link;
    }

    /**
     * Проверка на подтип товара из 1С
     * @param string $str арткул товара
     * @return bool
     */
    function true_parent($str) {
        if (strstr($str, '-'))
            return preg_match("/^[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}$/", $str);
        else
            return preg_match("/^[a-zA-Z0-9]{36}$/", $str);
    }

    /**
     * Цена с учетом валюты
     * @param int $id ИД товара
     * @param float $price стоимость товара
     * @param int $baseinputvaluta ИД валюты товара
     * @param bool $order параметр расчета заказе [true/false]
     * @param bool $check_user_price учитыватьперсональную колонку цен пользователя [true/false]
     * @return format
     */
    function GetPriceValuta($id, $price_array, $baseinputvaluta = false, $order = false, $check_user_price = true) {
        global $PHPShopValutaArray, $PHPShopSystem;

        if (!is_array($price_array))
            $price = $price_array;
        else
            $price = $price_array[0];

        $LoadItems['Valuta'] = $PHPShopValutaArray->getArray();
        $LoadItems['System'] = $PHPShopSystem->getArray();


        // Форматирование цены
        $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
        if(empty( $format))  $format=0;

        if (!empty($_SESSION['UsersStatus']) and !empty($check_user_price)) {

            if (empty($_SESSION['UsersStatusPice'])) {

                // Выборка из базы нужной колонки цены для автор. пользователя
                $PHPShopUser = new PHPShopUserStatus($_SESSION['UsersStatus']);
                $GetUsersStatusPrice = $PHPShopUser->getPrice();
                $_SESSION['UsersStatusPice'] = $GetUsersStatusPrice;
            }
            else
                $GetUsersStatusPrice = $_SESSION['UsersStatusPice'];

            if ($GetUsersStatusPrice > 1) {
                $pole = "price" . $GetUsersStatusPrice;

                // Если не известны другие колонки цен
                if (!is_array($price_array)) {
                    $PHPShopProduct = new PHPShopProduct($id);
                    $user_price = $PHPShopProduct->getParam($pole);
                } else {
                    // Берем цену из массива
                    $user_price = $price_array[$GetUsersStatusPrice - 1];
                }
                if (!empty($user_price))
                    $price = $user_price;
            }
        }

        // Учет валюты товара
        if ($baseinputvaluta) { //Если прислали баз. валюту
            if ($baseinputvaluta !== $LoadItems['System']['dengi']) {//Если присланная валюта отличается от базовой
                if (!empty($LoadItems['Valuta'][$baseinputvaluta]['kurs']))
                    $price = $price / $LoadItems['Valuta'][$baseinputvaluta]['kurs']; //Приводим цену в базовую валюту
            }
        }

        // Если выбрана другая валюта, order - флаг для расчета корзины толкьо в деф. валюте
        if ($order)
            $valuta = $LoadItems['System']['dengi'];
        elseif (isset($_SESSION['valuta']))
            $valuta = $_SESSION['valuta'];
        else
            $valuta = $LoadItems['System']['dengi'];

        // Правка на курс
        $price = $price * $LoadItems['Valuta'][$valuta]['kurs'];

        // Наценка
        $price = ($price + (($price * $LoadItems['System']['percent']) / 100));
        return number_format($price, $format, '.', '');
    }

}

?>