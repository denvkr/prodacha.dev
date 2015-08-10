<?php

class skin_viewer {

    var $debug = false;

    function skin_viewer() {
        global $PHPShopNav;
        $this->PHPShopNav = $PHPShopNav;

        if ($this->PHPShopNav->getPath() == 'shop') {
            $link = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 2);
            if ($this->PHPShopNav->getNav() == 'CID') {

                $sql = array('name' => 'skincat', 'base' => $GLOBALS['SysValue']['base']['categories'], 'where' => array('id' => "=$link"));
            } else {
                $sql = array('name' => 'skin', 'base' => $GLOBALS['SysValue']['base']['products'], 'where' => array('id' => "='$link'"));
            }

            $this->select($sql);
        }
        else
            $this->set();
    }

    function select($option = array()) {
        $PHPShopOrm = new PHPShopOrm($option['base']);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array($option['name']), $option['where'], false, array('limit' => 1));
        $this->set($data[$option['name']]);
    }

    function set($skin = false) {
        global $PHPShopSystem;
        if (!empty($skin) and file_exists("phpshop/templates/" . $skin . "/index.html"))
            $_SESSION['skin'] = $skin;
        else
            $_SESSION['skin'] = $PHPShopSystem->getParam('skin');

        $GLOBALS['SysValue']['other']['pageCss'] = $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . chr(47) . $GLOBALS['SysValue']['css']['default'];
    }

}

new skin_viewer;
?>