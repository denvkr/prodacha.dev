<?php

// Ipboard
class PHPShopFacebookpageElement extends PHPShopElements {

    function PHPShopFacebookpageElement() {
        $this->debug = false;
        $this->objBase = $GLOBALS['SysValue']['base']['facebookpage']['facebookpage_system'];
        parent::PHPShopElements();
    }

    function setFacebookSkin() {
        $this->data = $this->PHPShopOrm->select();
        $_SESSION['skin'] = $this->data['skin'];
        header("Location: /");
    }
    
    function setNormalSkin() {
        $_SESSION['skin'] = $this->PHPShopSystem->getParam('skin');
        if($GLOBALS['SysValue']['nav']['path'] == 'order')
            $location = "/order/";
        else 
            $location = "/";
        header("Location: $location");
    }

}

// Вывод 
$PHPShopFacebookpageElement = &new PHPShopFacebookpageElement();
if (@$_GET['facebookpage'] == true){
    $PHPShopFacebookpageElement->setFacebookSkin();
}
if (@$_GET['facebookpage'] == 'out'){
    $PHPShopFacebookpageElement->setNormalSkin();
}
?>