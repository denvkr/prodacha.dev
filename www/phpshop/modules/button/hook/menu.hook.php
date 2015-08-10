<?php


function button_menu_hook() {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['button']['button_system']);
    $option = $PHPShopOrm->select();

    $dis=null;
    if($option['enabled']>1) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['button']['button_forms']);
        $data = $PHPShopOrm->select(array('content'),array('enabled'=>"='1'"),array('order'=>'num'),array('limit'=>100));

        if(is_array($data))
            foreach($data as $row) {
                $dis.='<div>'.$row['content'].'</div>';
            }

        $GLOBALS['SysValue']['other']['button_forms']=$dis;
        $buttons=ParseTemplateReturn($GLOBALS['SysValue']['templates']['button']['button'],true);

        if($option['enabled'] == 2) {
            $GLOBALS['SysValue']['other']['leftMenu'].=$buttons;
        }
        else $GLOBALS['SysValue']['other']['rightMenu'].=$buttons;
    }
}


$addHandler=array
        (
        'miniCart'=>'button_menu_hook'
);
?>