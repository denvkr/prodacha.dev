<?php
/**
 * Flash баннер витрины товаров
 * @package PHPShopElementsDepricated
 * @author PHPShop Software
 * @version 1.3
 */

// Библиотеки
include("../phpshop/class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("valuta");

// Кол-во товаров в витрине
$limit=10;

// Подложка фитрины
$background='/stockgallery/src.jpg';

// Подключение к БД
$PHPShopBase = new PHPShopBase("../phpshop/inc/config.ini");

// Сстемные настройки
$PHPShopSystem = new PHPShopSystem();
$defvaluta=$PHPShopSystem->getParam('dengi');
$percent=$PHPShopSystem->getParam('percent');
$format=$PHPShopSystem->getSerilizeParam('admoption.price_znak');
$user_price_activate=$PHPShopSystem->getSerilizeParam('admoption.user_price_activate');

// Кэш валют
$PHPShopValuta = new PHPShopValutaArray();
$LoadItems['Valuta']=$PHPShopValuta->getArray();

// Отрезаем длинный символы
function SubstrName($str) {
    $num=50; // Кол-во максимальное в имени символов
    $len=strlen($str);
    if($len > $num)
        return substr($str, 0, $num)."...";
    else return $str;
}

/**
 * Расчет случайного вывода товаров
 * @param int $limit кол-во товаров для вывода
 * @return array
 */
function setramdom($limit) {

    // Если не сохранено в кэше
    if(empty($_SESSION['max_item'])) {
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug=false;
        $PHPShopOrm->cache=false;
        $PHPShopOrm->sql='SELECT MAX(id) as max_item FROM '.$GLOBALS['SysValue']['base']['products'];
        $data = $PHPShopOrm->select();

        if(is_array($data[0]))
            $max_item=$data[0]['max_item'];
        else $max_item=0;

        // Добавляем в кэш общее кол-во товаров
        $_SESSION['max_item']=$max_item;
    }
    else $max_item = $_SESSION['max_item'];

    $limit_start=rand(1,$max_item/rand(1,7));
    return ' BETWEEN '.$limit_start.' and '.round($limit_start+$limit+$max_item/3);
}


$XML = ('<?xml version="1.0" encoding="UTF-8"?>
<dat>
	<itemsSum>'.$limit.'</itemsSum>
	<moveto>left</moveto>
	<defaultSpeed>2</defaultSpeed>
	<acceleration>40</acceleration>
	<itemsInterval>10</itemsInterval> 
	<scalePrirost>1.5</scalePrirost>
	<backgroundImage>'.$background.'</backgroundImage>
	<titleBlockAlpha>40</titleBlockAlpha>
	<blur>4</blur>
	<currency>'.PHPShopString::win_utf8($LoadItems['Valuta'][$defvaluta]['code']).'</currency>
	<items>');

// Случайные товары
//$where['id']=setramdom($limit);

$where['spec']="='1'";
$where['enabled']="='1'";
$where['parent_enabled']="='0'";
$where['sklad']="!='1'";

// Выборка
$PHPShopOrm = new PHPShopOrm($SysValue['base']['products']);
$data=$PHPShopOrm->select(array('*'),$where,array('order'=>'RAND()'),array('limit'=>$limit));
if(count($data)<10) {
    $PHPShopOrm->clean();
    unset($where['spec']);
    $data=$PHPShopOrm->select(array('*'),$where,array('order'=>'RAND()'),array('limit'=>$limit));
}

if(is_array($data))
    foreach($data as $row) {
        $id=$row['id'];
        $name=PHPShopString::win_utf8(SubstrName($row['name']));
        $baseinputvaluta=$row['baseinputvaluta'];
        $price=$row['price'];

        if($baseinputvaluta>0)
            if ($baseinputvaluta!==$defvaluta) {//Если присланная валюта отличается от базовой
                $vkurs=$LoadItems['Valuta'][$baseinputvaluta]['kurs'];
                $price=$price/$vkurs; //Приводим цену в базовую валюту
            }

        $price=($price+(($price*$percent)/100));
        $price=round($price,$format);

        // Если цены показывать только после аторизации
        if(!empty($user_price_activate) and !$_SESSION['UsersId'])
            $price="***";

        $pic_small=$row['pic_small'];
        if(empty($pic_small))
            $pic_small="images/shop/no_photo.gif";

        $XML.= '<item price="'.$price.'" image="'.$pic_small.'" url="/shop/UID_'.$id.'.html">'.$name.'</item>';
    }

else $XML.= '<item price="" image="images/shop/no_photo.gif" url="/">'.PHPShopString::win_utf8("Добавьте товары в базу").'</item>';

$XML.= ('
    </items>
</dat>');

// Вывод XML
header('Content-Type: text/xml; charset=utf-8');
echo $XML
?>