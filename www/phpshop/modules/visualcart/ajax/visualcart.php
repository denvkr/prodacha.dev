<?php

session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass('order');
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("text");

// Подключаем библиотеку поддержки.
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

// Массив валют
$PHPShopValutaArray = new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

$PHPShopModules = new PHPShopModules('../../');

class AddToTemplateVisualCartAjax {

    var $debug = false;

    /**
     * Конструктор
     */
    function AddToTemplateVisualCartAjax() {

        $this->option();

        // Ключ памяти
        if ($this->option['memory'] == 1)
            $this->init();

        $this->PHPShopCart = new PHPShopCart();
    }

    /**
     * Инициализация ключа памяти
     */
    function init() {
        if (empty($_COOKIE['visualcart_memory']))
            $this->memory = md5(session_id());
        else
            $this->memory = $_COOKIE['visualcart_memory'];

        if (empty($_SESSION['cart_sig'])) {
            $this->get_memory();
        }
    }

    /**
     * Настройки
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_system']);
        $PHPShopOrm->debug = $this->debug;
        $this->option = $PHPShopOrm->select();
    }

    /**
     * Удаление товары
     * @param int $xid ИД товара
     */
    function del($xid) {
        if (is_numeric($xid)) {
            $this->PHPShopCart->del($xid);
            $this->clean_memory();
        }
    }

    /**
     * Затирание старой памяти
     */
    function clean_memory() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
        $PHPShopOrm->delete(array('memory' => "='" . $this->memory . "'"));
    }

    /**
     * Запись корзины в БД
     */
    function add_memory() {
        $insert = array();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
        $insert['memory_new'] = $this->memory;
        $insert['date_new'] = time();
        $insert['user_new'] = $_SESSION['UsersId'];
        $insert['cart_new'] = serialize($_SESSION['cart']);
        $insert['ip_new'] = $_SERVER["REMOTE_ADDR"];

        if (isset($_COOKIE['ps_referal']))
            $insert['referal_new'] = base64_decode($_COOKIE['ps_referal']);

        $PHPShopOrm->insert($insert);
    }

    /**
     * Номер записи памяти в кукус
     */
    function add_cookie() {
        setcookie("visualcart_memory", $this->memory, time() + 60 * 60 * 24 * 90, "/", $_SERVER['SERVER_NAME'], 0);
    }

    /**
     * Проверка ключа активации
     */
    function true_key($str) {
        return preg_match("/^[a-zA-Z0-9_]{4,35}$/", $str);
    }

    function get_memory() {
        if ($this->true_key($_COOKIE['visualcart_memory'])) {
            $this->memory = $_COOKIE['visualcart_memory'];
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
            $data = $PHPShopOrm->select(array('cart'), array('memory' => "='" . $this->memory . "'"), false, array('limit' => 1));
            if (is_array($data)) {
                $_SESSION['cart'] = unserialize($data['cart']);
            }
        }
    }

    /**
     * Форма корзины
     * @return string
     */
    function visualcart() {

        // Учет модуля SEOURL
        if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system']))
            PHPShopObj::loadClass('string');

        $GLOBALS['PHPShopOrder'] = new PHPShopOrderFunction();

        // Валюта
        $this->currency = $GLOBALS['PHPShopOrder']->default_valuta_code;

        PHPShopParser::set('visualcart_pic_width', $this->option['pic_width']);

        // Если есть товары в корзине
        if ($this->PHPShopCart->getNum() > 0) {
            $list = $this->PHPShopCart->display('visualcartform', array('currency' => $this->currency));

            // Обновленная корзина
            if ($_SESSION['cart_sig'] != md5($list)) {
                $_SESSION['cart_sig'] = md5($list);

                // Чистка
                $this->clean_memory();

                // Запись в БД
                $this->add_memory();

                // Ключ памяти в куку
                $this->add_cookie();
            }

            return '<table>' . $list . '</table>';
        }
    }

}

/**
 * Шаблон вывода таблицы корзины
 */
function visualcartform($val, $option) {

    // Проверка подтипа товара, выдача ссылки главного товара
    if (empty($val['parent'])) {
        PHPShopParser::set('visualcart_product_id', $val['id']);

        // Учет модуля SEOURL
        if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
            PHPShopParser::set('visualcart_product_seo', '_' . PHPShopString::toLatin($val['name']));
        }
    } else {
        PHPShopParser::set('visualcart_product_id', $val['parent']);
        PHPShopParser::set('visualcart_product_seo', null);
    }

    PHPShopParser::set('visualcart_product_xid', $val['id']);
    PHPShopParser::set('visualcart_product_name', $val['name']);
    PHPShopParser::set('visualcart_product_pic_small', $val['pic_small']);
    PHPShopParser::set('visualcart_product_price', $val['price'] * $val['num']);
    PHPShopParser::set('visualcart_product_currency', $option['currency']);

    $dis = PHPShopParser::file('../templates/product.tpl', true);
    return $dis;
}

$AddToTemplateVisualCartAjax = new AddToTemplateVisualCartAjax();

// Удаление
if (!empty($_REQUEST['xid'])) {
    $AddToTemplateVisualCartAjax->del($_REQUEST['xid']);
}

// Корзина
$visualcart = $AddToTemplateVisualCartAjax->visualcart();


// Формируем результат
if (!empty($_SESSION['cart']))
    $_RESULT = array(
        "visualcart" => $visualcart,
        "sum" => $AddToTemplateVisualCartAjax->PHPShopCart->getSum(),
        "num" => $AddToTemplateVisualCartAjax->PHPShopCart->getNum()
    );
elseif (!empty($_REQUEST['xid']) and empty($_SESSION['cart'])) {

    $_RESULT = array(
        "visualcart" => $SysValue['lang']['visualcart_empty'],
        "sum" => $AddToTemplateVisualCartAjax->PHPShopCart->getSum(),
        "num" => $AddToTemplateVisualCartAjax->PHPShopCart->getNum()
    );
}

// Обнуление даты обновления корзины
setcookie("cart_update_time", '', 0, "/", $_SERVER['SERVER_NAME'], 0);
?>