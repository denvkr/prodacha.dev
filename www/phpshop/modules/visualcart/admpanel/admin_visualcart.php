<?php
$TitlePage="Незавершенные заказы";

PHPShopObj::loadClass('text');


function getCartInfo($cart) {
    $dis=null;
    $cart=unserialize($cart);
    if(is_array($cart))
        foreach($cart as $val) {
            $dis.=$val['uid']."  ".$val['name']." (".$val['num']." ".$val['ed_izm']." * ".$val['price'].") -- ".($val['price']*$val['num']).'<br>';
        }
    return $dis;
}


function getUserName($id,$ip) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $data = $PHPShopOrm->select(array('name'),array('id'=>'='.$id),false,array('limit'=>1));
    if(is_array($data))
        return $data['name'];
    else return $ip;
}

function getReferal($str) {
    $referal = explode(',', $str);
    $dis=null;
    if(is_array($referal)) {
        foreach($referal as $val)
            $un_array[$val]=$val;

        foreach($un_array as $val)
            $dis.=PHPShopText::a('http://'.$val,$val,false,false,false,'_blank').'<br>';
    }
    return $dis;

}

function actionStart() {
    global $PHPShopInterface,$_classPath;

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");

    $PHPShopInterface->size="630,530";
    $PHPShopInterface->setCaption(array("Дата","10%"),array("Товары","45%"),array("Пользователь","25%"),array("Реферал","25%"));

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.visualcart.visualcart_memory"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array("limit"=>"1000"));
    if(is_array($data))
        foreach($data as $row) {
            extract($row);
            $PHPShopInterface->setRow($id,PHPShopDate::dataV($date),getCartInfo($cart),getUserName($user,$ip),getReferal($referal));
        }


    $link = "../modules/visualcart/admpanel/export.php?sortdate_start=".$_GET['sortdate_start']."&sortdate_end=".$_GET['sortdate_end'];
    if(count($data)>2)$PHPShopInterface->_CODE_ADD_BUTTON=$PHPShopInterface->setDiv('left',$notice,'padding:10px;float:left').
                $PHPShopInterface->setInput("button","","Выгрузить в CSV","right",150,"return  window.open('".$link."','_blank');","but");
    $PHPShopInterface->Compile();
}
?>