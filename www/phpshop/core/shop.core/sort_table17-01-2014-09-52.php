<?php

/**
 * Вывод сортировок для товаров таблицей
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function sort_table($obj, $row) {
    global $SysValue;

    $sort = $obj->PHPShopCategory->unserializeParam('sort');
    $vendor_array = unserialize($row['vendor_array']);
    $dis = $sortCat = $sortValue = null;
    $arrayVendorValue=array();
    
    if (is_array($sort))
        foreach ($sort as $v) {
            $sortCat.=' id=' . $v . ' OR';
        }
    $sortCat = substr($sortCat, 0, strlen($sortCat) - 2);

    if (!empty($sortCat)) {

        // Массив имен характеристик
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug = $obj->debug;
        $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name20'] . " where ($sortCat and goodoption!='1') order by num");
        while (@$row = mysql_fetch_assoc($result)) {


		//nah~ не выводим эти харки - 3 иконко		
		$array=array('181','184','185','186');
		
		if (!in_array($row['id'],$array))
            $arrayVendor[$row['id']]=$row;
        }

        if (is_array($vendor_array))
            foreach ($vendor_array as $v) {
                foreach ($v as $value)
                    if (is_numeric($value))
                        $sortValue.=' id=' . $value . ' OR';
            }
        $sortValue = substr($sortValue, 0, strlen($sortValue) - 2);

        if (!empty($sortValue)) {

            // Массив значений характеристик
            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $obj->debug;
            $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name21'] . " where $sortValue order by num");
            while (@$row = mysql_fetch_array($result)) {
                @$arrayVendorValue[$row['category']]['name'].= ", " . $row['name'];
            }


            // Создаем таблицу характеристик с учетом сортировки
            if(is_array($arrayVendor))
            foreach ($arrayVendor as $idCategory => $value)
                if (!empty($arrayVendorValue[$idCategory]['name'])) {
                    if (!empty($value['name'])) {
                        if (!empty($value['page']))
                            $dis.=PHPShopText::tr(PHPShopText::b($value['name']) . ': ', PHPShopText::a('../page/' . $value['page'] . '.html', substr($arrayVendorValue[$idCategory]['name'], 2)));
                        else
                            $dis.=PHPShopText::tr(PHPShopText::b($value['name']) . ': ', substr($arrayVendorValue[$idCategory]['name'], 2));
                    }
                }

            $disp = PHPShopText::table($dis);
            $obj->set('vendorDisp', $disp);
        }
    }
}

?>