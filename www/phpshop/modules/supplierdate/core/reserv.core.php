<?php

class PHPShopReserv extends PHPShopCore {

    /**
     * �����������
     */
    function PHPShopReserv() {

        // ������ �������
        $this->action = array(
            'post' => 'reserv_id',
            'get' => 'reserv_id'
        );

        parent::PHPShopCore();
    }

    /**
     * �������� 
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
     * ����� �� ���������, ����� ��������� � �������
     */
    function reserv_id() {

        $order_num = $this->decode();

        if (!empty($order_num)) {

            // ���������
            $message = $GLOBALS['SysValue']['lang']['reserv_done'];
            $this->set('pageTitle', $message);

            // �������� ��������� ��������
            $this->mail($order_num);

            // ���������� ������
            $this->set('pageContent', $GLOBALS['SysValue']['lang']['reserv_done_message']);
            $this->parseTemplate($this->getValue('templates.page_page_list'));
        }
        else return $this->setError404();
    }

    /**
     * ��������� �������������� � �������
     */
    function mail($order_num) {

        $zag = $this->PHPShopSystem->getValue('name') . " - ������ - " . PHPShopDate::dataV($date);
        $message = "
������� �������!
---------------

� ����� " . $this->PHPShopSystem->getValue('name') . " ������ ������ ������� �� ������ " . $order_num . "
IP:                 " . $_SERVER['REMOTE_ADDR'] . "

---------------

� ���������,
http://" . $_SERVER['SERVER_NAME'];

        PHPShopObj::loadClass('mail');
        new PHPShopMail($this->PHPShopSystem->getValue('adminmail2'), $this->PHPShopSystem->getValue('adminmail2'), $zag, $message);
    }

}

?>