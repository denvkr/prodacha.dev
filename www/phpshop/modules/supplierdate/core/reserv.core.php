<?php

class PHPShopReserv extends PHPShopCore {

    /**
     * Конструктор
     */
    function PHPShopReserv() {

        // Список экшенов
        $this->action = array(
            'post' => 'reserv_id',
            'get' => 'reserv_id'
        );

        parent::PHPShopCore();
    }

    /**
     * Заглушка 
     */
    function index() {
        $this->setError404();
    }

    function decode() {
        $code = $_REQUEST['reserv_id'];
        $code = substr($code, 2, strlen($code));
        $decode = base64_decode($code);
        $num = explode('-', $decode);
        if (is_numeric($num[0]))
            return $decode;
    }

    /**
     * Экшен по умолчанию, вывод сообщения о резерве
     */
    function reserv_id() {

        $order_num = $this->decode();

        if (!empty($order_num)) {

            // Сообщение
            $message = $GLOBALS['SysValue']['lang']['reserv_done'];
            $this->set('pageTitle', $message);

            // Отправка почтового собщения
            $this->mail($order_num);

            // Подключаем шаблон
            $this->set('pageContent', $GLOBALS['SysValue']['lang']['reserv_done_message']);
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
        else return $this->setError404();
    }

    /**
     * Сообщение администратору о резерве
     */
    function mail($order_num) {

        $zag = $this->PHPShopSystem->getValue('name') . " - Резерв - " . PHPShopDate::dataV($date);
        $message = "
Доброго времени!
---------------

С сайта " . $this->PHPShopSystem->getValue('name') . " пришла заявка резерва по заказу " . $order_num . "
IP:                 " . $_SERVER['REMOTE_ADDR'] . "

---------------

С уважением,
http://" . $_SERVER['SERVER_NAME'];

        PHPShopObj::loadClass('mail');
        new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, $message);
    }

}

?>