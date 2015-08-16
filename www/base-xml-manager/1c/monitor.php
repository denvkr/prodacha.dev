<?php

$_classPath = '../../';
include($_classPath . 'phpshop/class/obj.class.php');
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("basexml");
PHPShopObj::loadClass("modules");


// Подключаем БД
$PHPShopBase = new PHPShopBase($_classPath . 'phpshop/inc/config.ini');

// Настройки модулей
$PHPShopModules = new PHPShopModules($_classPath . "phpshop/modules/");

class PHPShopMonitor extends PHPShopBaseXml {

    function PHPShopMonitor() {
        $this->debug = false;
        $this->true_method = array('visualcart', 'stat');
        $this->true_from = array('');

        parent::PHPShopBaseXml();
    }

    function visualcart() {
        global $PHPShopModules;
        $base = $PHPShopModules->getParam("base.visualcart.visualcart_memory");
        if (!empty($base)) {
            $PHPShopOrm = new PHPShopOrm($base);
            $PHPShopOrm->debug = $this->debug;
            $this->data = $PHPShopOrm->select($this->xml['vars'], $this->xml['where'], $this->xml['order'], $this->xml['limit']);
        }
    }

    function stat() {
        global $PHPShopModules;
        $base = $PHPShopModules->getParam("base.stat.stat_visitors");
        if (!empty($base)) {
            $PHPShopOrm = new PHPShopOrm($base);
            $PHPShopOrm->debug = $this->debug;
            $this->data = $PHPShopOrm->select($this->xml['vars'], $this->xml['where'], $this->xml['order'], $this->xml['limit']);
        }
    }

    function decode($code) {
        $decode = substr($code, 0, strlen($code) - 4);
        $decode = str_replace("I", 11, $decode);
        $decode = explode("O", $decode);
        $disp_pass = "";
        for ($i = 0; $i < (count($decode) - 1); $i++)
            $disp_pass.=chr($decode[$i]);
        return base64_encode($disp_pass);
    }

    function admin() {

        $PHPShopOrm = new PHPShopOrm($this->PHPShopBase->getParam('base.table_name19'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('login,password,status'), array('enabled' => "='1'"), false, array('limit' => 10));
        if (is_array($data)) {
            foreach ($data as $v)
                if ($_POST['log'] == $v['login'] and $this->decode($_POST['pas']) == $v['password']) {
                    $this->user_status = unserialize($v['status']);
                    return true;
                }
        }
        return false;
    }

}

new PHPShopMonitor();
?>