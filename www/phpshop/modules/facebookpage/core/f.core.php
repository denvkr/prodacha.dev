<?php

class PHPShopF extends PHPShopCore {

    // Конструктор
    function PHPShopF() {
        $this->objBase=$GLOBALS['SysValue']['base']['facebookpage']['facebookpage_system'];
        $this->debug=false;
        $this->cache=false;
        parent::PHPShopCore();
    }
    

    function index() {
        $this->data = $this->PHPShopOrm->select();
        $_SESSION['skin'] = $this->data['skin'];
        header("Location: /");
    }
}

?>