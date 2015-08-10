<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("date");

$PHPShopBase=&new PHPShopBase($_classPath."inc/config.ini");
$PHPShopSystem=&new PHPShopSystem();

$PHPShopModules = new PHPShopModules('../../');

PHPShopObj::loadClass('text');


function getCartInfo($cart) {
    $dis=null;
    $cart=unserialize($cart);
    if(is_array($cart))
        foreach($cart as $val) {
            $dis.=$val['uid']."  ".$val['name']." (".$val['num']." ".$val['ed_izm']." * ".$val['price'].") -- ".($val['price']*$val['num']).', ';
        }
    return substr($dis, 0, strlen($dis)-2);
}


function getUserName($id,$ip) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $data = $PHPShopOrm->select(array('name'),array('id'=>'='.$id),false,array('limit'=>1));
    if(is_array($data))
        return $data['name'];
    else return $ip;
}

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.visualcart.visualcart_memory"));
if(!empty($_GET['sortdate_start'])) $where=array('date'=>' < '.(PHPShopDate::GetUnixTime($_GET['sortdate_end'])+86400).' AND date > '.(PHPShopDate::GetUnixTime($_GET['sortdate_start'])-86400));
else $where=false;

$data = $PHPShopOrm->select(array('*'),$where,array('order'=>'id DESC'),array('limit'=>1000));
$content='Дата;Товары;Пользователь;Реферал;
 ';

if(is_array($data))
foreach($data as $value) $content.=PHPShopDate::dataV($value['date']).';'.getCartInfo($value['cart']).';'.getUserName($value['user'],$value['ip']).';'.$value['referal'].';
';

$file = $_classPath.'admpanel/csv/cart.csv';
PHPShopFile::write($file,$content);
header("Location: ".$file);
?>
