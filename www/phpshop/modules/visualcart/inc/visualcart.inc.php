<?php

/**
 * Элемент корзины
 */
PHPShopObj::loadClass('order');
PHPShopObj::loadClass('cart');

class AddToTemplateVisualCart extends PHPShopElements {

    var $debug = false;

    /**
     * Конструктор
     */
    function AddToTemplateVisualCart() {

        $this->option();

        if ($this->option['memory'] == 1) {
            $this->check_user_memory();
            $this->get_memory();
        }

        $this->PHPShopCart = new PHPShopCart();

        parent::PHPShopElements();
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
     * Учет реферала
     */
    function referal() {
        $url = parse_url($_SERVER['HTTP_REFERER']);
        $referal = $url["host"];

        if (isset($_COOKIE['ps_referal']))
            $partner = base64_encode(base64_decode($_COOKIE['ps_referal']) . "," . $referal);
        else
            $partner = base64_encode($referal);

        if (strlen($_SERVER['HTTP_REFERER']) > 5 and !strpos($_SERVER['HTTP_REFERER'], $_SERVER['SERVER_NAME']))
            setcookie("ps_referal", $partner, time() + 60 * 60 * 24 * 90, "/", $_SERVER['SERVER_NAME'], 0);
    }

    /**
     *  JS библиотека
     */
    function addJS() {
        $this->set('visualcart_lib', '<SCRIPT language="JavaScript" type="text/javascript" src="' . $this->get('shopDir') . 'phpshop/modules/visualcart/js/visualcart.js"></SCRIPT>');
    }

    /**
     * Проверка ключа активации
     */
    function true_key($str) {
        return preg_match("/^[a-zA-Z0-9_]{4,35}$/", $str);
    }

    /**
     * Номер записи памяти в кукус
     */
    function add_cookie() {
        setcookie("visualcart_memory", $this->memory, time() + 60 * 60 * 24 * 90, "/", $_SERVER['SERVER_NAME'], 0);
    }

    /**
     * Проверка на авторизацию пользователя
     */
    function check_user_memory() {
        if (empty($_SESSION['cart']) and PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
            $PHPShopOrm->debug = $this->debug;
            $data = $PHPShopOrm->select(array('memory'), array('user' => "=" . $_SESSION['UsersId']), array('order' => 'date'), array('limit' => 1));
            if (is_array($data)) {
                $this->memory = $data['memory'];
                $this->add_cookie();
            }
        }
    }

    /**
     * Данные из БД по ключу памяти
     */
    function get_memory() {
        if (empty($_SESSION['cart_sig'])) {

            // Реферал
            $this->referal();

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['visualcart']['visualcart_memory']);
            $PHPShopOrm->debug = $this->debug;
            if ($this->true_key($_COOKIE['visualcart_memory'])) {
                $data = $PHPShopOrm->select(array('*'), array('memory' => "='" . $_COOKIE['visualcart_memory'] . "'"), false, array('limit' => 1));

                if (is_array($data)) {
                    $this->memory = $data['memory'];
                    $_SESSION['cart'] = unserialize($data['cart']);
                }
            }
        }
    }

    /**
     * Прорисовка элемента визуальной корзины
     */
    function visualcart() {

        // Учет модуля SEOURL
        if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system']))
            PHPShopObj::loadClass('string');

        $GLOBALS['PHPShopOrder'] = new PHPShopOrderFunction();

        // Валюта
        $this->currency = $GLOBALS['PHPShopOrder']->default_valuta_code;

        $this->addJS();

        $this->set('visualcart_pic_width', $this->option['pic_width']);

        // Если есть товары в корзине
        if ($this->PHPShopCart->getNum() > 0) {
            $list = $this->PHPShopCart->display('visualcartform', array('currency' => $this->currency));
            $this->set('visualcart_list', '<table>' . $list . '</table>', true);
            $this->set('visualcart_order', '');
        } else {
            $this->set('visualcart_list', $this->lang('visualcart_empty'), true);
            $this->set('visualcart_order', 'display:none');
        }

        $cart = parseTemplateReturn($GLOBALS['SysValue']['templates']['visualcart']['visualcart_cart'], true);

        $this->set('leftMenuContent', $cart);
        $this->set('leftMenuName', $this->option['title']);

        // Подключаем шаблон
        $dis = $this->parseTemplate($this->getValue('templates.left_menu'));

        // Назначаем переменную шаблона
        switch ($this->option['enabled']) {

            case 1:
                $this->set('leftMenu', $dis, true);
                break;

            case 2:
                $this->set('rightMenu', $dis, true);
                break;

            default: $this->set('visualcart', $cart);
        }
    }

}

/**
 * Шаблон вывода таблицы корзины
 */
PHPShopObj::loadClass('parser');

function visualcartform($val, $option) {
    global $SysValue;
    
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
    
    PHPShopParser::set('visualcart_product_xid',$val['id']);
    PHPShopParser::set('visualcart_product_name', $val['name']);
    PHPShopParser::set('visualcart_product_pic_small', $val['pic_small']);
    PHPShopParser::set('visualcart_product_price', $val['price'] * $val['num']);
    PHPShopParser::set('visualcart_product_currency', $option['currency']);
    PHPShopParser::set('visualcart_product_num', $val['num']);

    $dis = parseTemplateReturn($SysValue['templates']['visualcart']['visualcart_product'], true);
    return $dis;
}

// Добавляем в шаблон элемент
if ($PHPShopNav->notPath(array('order', 'done'))) {
    $AddToTemplateVisualCart = new AddToTemplateVisualCart();
    $AddToTemplateVisualCart->visualcart();
}
?>