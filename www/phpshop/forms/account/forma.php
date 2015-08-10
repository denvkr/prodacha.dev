<?php
/*
 * Счет в банк заказ
*/

session_start();

$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("inwords");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("text");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopSystem = new PHPShopSystem();

/**
 * Шаблон вывода таблицы корзины
 * Основной шаблон печатной формы расположен в phpshop/lib/templates/print/acount.tpl
 */
function printforma($val) {
    global $n;
    if(empty($val['ed_izm'])) $val['ed_izm']='шт.';
    $dis=PHPShopText::tr($n,$val['name'],$val['ed_izm'],$val['num'],$val['price'],$val['total']);
    @$n++;
    return $dis;
}

/**
 * Шаблон вывода таблицы доставки
 * Основной шаблон печатной формы расположен в phpshop/lib/templates/print/acount.tpl
 */
function printdelivery($val) {
    global $n;
    return PHPShopText::tr($n,'Доставка - '.$val['name'],'шт.','1',$val['price'],$val['price']);
}

if(PHPShopSecurity::true_param($_GET['tip'],$_GET['orderId'],$_GET['datas'])) {

    $orderId=PHPShopSecurity::TotalClean($_GET['orderId'],5);
    $datas=PHPShopSecurity::TotalClean($_GET['datas'],5);

    $PHPShopOrm = new PHPShopOrm();
    $result=$PHPShopOrm->query("select id from ".$SysValue['base']['table_name1']." where id='$orderId' and datas=".$datas);
    $n=mysql_num_rows($result);

    if(empty($n)) exit("Неавторизованный пользователь!");
    else $PHPShopOrder = new PHPShopOrderFunction($orderId);

    // Перевод цифр в слова
    $iw = new inwords;

    PHPShopParser::set('totaltext',$iw->get($PHPShopOrder->getTotal()));
    PHPShopParser::set('item',$PHPShopOrder->getNum());
    PHPShopParser::set('currency',$PHPShopOrder->default_valuta_code);
    PHPShopParser::set('total',$PHPShopOrder->getTotal());

    if($PHPShopSystem->getValue('nds_enabled') == 0) {
        PHPShopParser::set('nds_block_start','<!--');
        PHPShopParser::set('nds_block_end','-->');
    }

    PHPShopParser::set('totalnds',$PHPShopOrder->getTotal($nds=true));
    PHPShopParser::set('nds',$PHPShopOrder->PHPShopSystem->getParam('nds'));
    PHPShopParser::set('discount',$PHPShopOrder->getDiscount());
    PHPShopParser::set('ouid',$PHPShopOrder->getValue('uid'));
    PHPShopParser::set('person_org',$PHPShopOrder->getSerilizeParam('orders.Person.org_name').' ИНН '.
            $PHPShopOrder->getSerilizeParam('orders.Person.org_inn').' КПП '.$PHPShopOrder->getSerilizeParam('orders.Person.org_kpp'));
    PHPShopParser::set('person_user',$PHPShopOrder->getSerilizeParam('orders.Person.name_person'));
    PHPShopParser::set('org_bank_acount',$PHPShopSystem->getSerilizeParam('bank.org_bank_schet'));
    PHPShopParser::set('org_bank_acount',$PHPShopSystem->getSerilizeParam('bank.org_bank_schet'));
    PHPShopParser::set('org_bic',$PHPShopSystem->getSerilizeParam('bank.org_bic'));
    PHPShopParser::set('org_bank',$PHPShopSystem->getSerilizeParam('bank.org_bank'));
    PHPShopParser::set('org_name',$PHPShopSystem->getSerilizeParam('bank.org_name'));
    PHPShopParser::set('org_schet',$PHPShopSystem->getSerilizeParam('bank.org_schet'));
    PHPShopParser::set('org_kpp',$PHPShopSystem->getSerilizeParam('bank.org_kpp'));
    PHPShopParser::set('org_inn',$PHPShopSystem->getSerilizeParam('bank.org_inn'));
    PHPShopParser::set('org_adres',$PHPShopSystem->getSerilizeParam('bank.org_adres'));
    PHPShopParser::set('org_ur_adres',$PHPShopSystem->getSerilizeParam('bank.org_ur_adres'));
    PHPShopParser::set('org_name',$PHPShopSystem->getSerilizeParam('bank.org_name'));
    PHPShopParser::set('date',date("d-m-y"));
    PHPShopParser::set('name',$PHPShopSystem->getName());
    PHPShopParser::set('cart',$PHPShopOrder->cart('printforma').$PHPShopOrder->delivery('printdelivery'));
    PHPShopParser::file('../../lib/templates/print/account.tpl');
}
else header('Location: /');
?>