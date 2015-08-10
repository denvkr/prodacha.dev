<?php

// Ipboard
class PHPShopIpboardElement extends PHPShopElements {
    var $scrolling="no";
    var $frameborder=0;

    function PHPShopIpboardElement() {
        $this->debug=false;
        $this->objBase=$GLOBALS['SysValue']['base']['forumlastpost']['ipboard_system'];
        parent::PHPShopElements();
        $this->option();
    }
    
    function option(){
        $this->data = $this->PHPShopOrm->select();

        // Сохраняем настройки
        $this->LoadItems['modules']['forumlastpost']['enabled']=$this->data['enabled'];
        $this->LoadItems['modules']['forumlastpost']['flag']=$this->data['flag'];
    }

    // Вывод корзины
    function ipboard() {

        $dis = '<IFRAME height="'.$this->data['height'].'" src="'.$this->data['path'].'/lastpost.php?n='.$this->data['num'].'"
            frameBorder="'.$this->frameborder.'" width="'.$this->data['width'].'" scrolling="'.$this->scrolling.'"></IFRAME>';

        $this->set('leftMenuName',$this->data['title']);
        $this->set('leftMenuContent',$dis);


        if(empty($this->data['flag'])) $templates=$this->getValue('templates.right_menu');
        else $templates=$this->getValue('templates.left_menu');

        return $this->parseTemplate($templates);
    }

}

// Вывод 
$PHPShopIpboardElement = &new PHPShopIpboardElement();
if($GLOBALS['LoadItems']['modules']['forumlastpost']['enabled']==1) {
    if($GLOBALS['LoadItems']['modules']['forumlastpost']['flag']==1) {
        $PHPShopTextElement = &new PHPShopTextElement();
        $GLOBALS['SysValue']['other']['rightMenu']=$PHPShopTextElement->rightMenu();
        $GLOBALS['SysValue']['other']['rightMenu'].=$PHPShopIpboardElement->ipboard();
    }
    else {
        $PHPShopTextElement = &new PHPShopTextElement();
        $GLOBALS['SysValue']['other']['leftMenu']=$PHPShopTextElement->leftMenu();
        $GLOBALS['SysValue']['other']['leftMenu'].=$PHPShopIpboardElement->ipboard();
       
    }
}else $PHPShopIpboardElement->init('forumlastpost');
?>