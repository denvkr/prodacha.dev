<?php

/**
 * Cортировка товаров
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter($obj) {

    $sort=null;
    $n=$obj->category;

    $v=@$_REQUEST['v'];
    $s=PHPShopSecurity::TotalClean(@$_REQUEST['s'],1);
    $f=PHPShopSecurity::TotalClean(@$_REQUEST['f'],1);

    if($obj->PHPShopNav->isPageAll())
        $p=PHPShopSecurity::TotalClean($p,1);

    if($obj->PHPShopCategory->getParam('num_row')>0)
        $num_row=$obj->PHPShopCategory->getParam('num_row');
    else $num_row=$obj->PHPShopSystem->getValue('num_row');

    // Сортировка по характеристикам
    if(is_array($v)) {
        foreach($v as $key=>$value) {
            if(PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                $hash=$key."-".$value;
                $sort.=" and vendor REGEXP 'i".$hash."i' ";
            }
        }
    }

    // Направление сортировки из настроек каталога. Вторая часть логики в sort.class.php
    if(empty($s))
        switch($obj->PHPShopCategory->getParam('order_to')) {
            case(1): $order_direction="";
                $obj->set('productSortImg',1);
                break;
            case(2): $order_direction=" desc";
            $obj->set('productSortImg',2);
                break;
            default: $order_direction="";
               $obj->set('productSortImg',1);
                break;
        }


    // Сортировки из настроек каталога. Вторая часть логики в sort.class.php
    if(empty($f))
        switch($obj->PHPShopCategory->getParam('order_by')) {
            case(1): $order=array('order'=>'name'.$order_direction);
                $obj->set('productSortA','sortActiv');
                break;
            case(2): $order=array('order'=>'price'.$order_direction);
                $obj->set('productSortB','sortActiv');
                break;
            case(3): $order=array('order'=>'num'.$order_direction);
                $obj->set('productSortC','sortActiv');
                break;
            default: $order=array('order'=>'num'.$order_direction);
                $obj->set('productSortC','sortActiv');
                break;
        }

    // Сортировка принудительная пользователем
    if($s or $f) {
        switch($f) {
            case(1): $order_direction="";

                break;
            case(2): $order_direction=" desc";
                break;
            default: $order_direction="";
                break;
        }
        switch($s) {
            case(1): $order=array('order'=>'name'.$order_direction);
                break;
            case(2): $order=array('order'=>'price'.$order_direction);
                break;
            case(3): $order=array('order'=>'num'.$order_direction);
                break;
            default: $order=array('order'=>'num'.$order_direction);
        }
    }
	//if ($obj->category==288 || $obj->category==290 || $obj->category==292) {
		//$n0='48,20,19,15,17,21,23,45,46,244,38,39,40,41,42,203,49,50,51,52,205,53,169,243,22,24,25,30,26,27,28,29,56,57,229,61,62,63,64,100,65,104,111,230,67,102,112,69,70,168,71,117,113,103,74,116,114,106,105,83,245,84,86,87,88,198,200,89,90,91,92,101,220,216,225,219,108,110,138,226,223,222,221,217,218,197,115,207,94,120,109,156,144,148,149,147,150,194,151,152,204,170,171,173,175,174,181,178,179,185,193,195,199,210,209,253,231,235,238,240,241,254,255,256,265,266,267,271,272';
		//48,20,19,15,17,21,23,45,46,244,38,39,40,41,42,203,49,50,51,52,205,53,169,243,22,24,25,30,26,27,28,29,56,57,229,61,62,63,64,100,65,104,111,230,67,102,112,69,70,168,71,117,113,103,74,116,114,106,105,83,245,84,86,87,88,198,200,89,90,91,92,101,220,216,225,219,108,110,138,226,223,222,221,217,218,197,115,207,94,120,109,156,144,148,149,147,150,194,151,152,204,170,171,173,175,174,181,178,179,185,193,195,199,210,209,253,231,235,238,240,241,254,255,256,265,266,267,271,272
		//$n0='-1';
	//} else {
		$n0=$n;
	//}
    // Учет добавочных категорий
    $catt='(category in ('.$n0.') OR dop_cat LIKE \'%#'.$n.'#%\')';

    // Преобзазуем массив условия сортировки в строку
    foreach($order as $key=>$val)
        $string=$key.' by '.$val;

		//nah~  ставим вывод по наличию в 1 очередь
		/*
		if ( (!isset($_GET['s'])) AND (!isset($_GET['f'])) AND (!isset($_GET['v'])) )
		{
			// $string = str_replace("order by", "order by sklad, price DESC, ",$string);
			$string = str_replace("order by", "order by COALESCE(sklad,0) = 1 ASC, ",$string);
			
			// echo $string;
		}
		*/
		
    // Все страницы
    if($obj->PHPShopNav->isPageAll()) {
        $sql=" ($catt and enabled='1' and parent_enabled='0') ".$sort." ".$string.' limit '.$obj->max_item;
		//echo $sql; 
    }

    // Поиск по цене
    elseif(isset($_POST['priceSearch'])) {

        $priceOT=PHPShopSecurity::TotalClean($_POST['priceOT'],1);
        $priceDO=PHPShopSecurity::TotalClean($_POST['priceDO'],1);

        $percent=$obj->PHPShopSystem->getValue('percent');

        // Бесконечность
        if($priceDO==0) $priceDO=1000000000;

        if(empty($priceOT)) $priceOT=0;

        // Цена с учетом выбранной валюты
        $priceOT/=$obj->currency('kurs');
        $priceDO/=$obj->currency('kurs');

        $sql="$catt and enabled='1' and parent_enabled='0' and price >= ".
                ($priceOT/(100+$percent)*100)." AND price <= ".($priceDO/(100+$percent)*100)." ".$sort.$string.' limit 0,'.$obj->max_item;
    }
    elseif(!empty($sort)) {
        return array('sql'=>$catt." and enabled='1' and parent_enabled='0' ".$sort.$string);
    }
    else {
        return array('sql'=>$catt." and enabled='1' and parent_enabled='0' ".$sort.$string);
    }
	//echo $sql;
    // Возвращаем SQL 
    return $sql;
}
?>