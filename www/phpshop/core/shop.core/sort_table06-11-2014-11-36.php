<?php

/**
 * Вывод сортировок для товаров таблицей
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopCoreFunction
 * @param obj объект класса
 * @return mixed
 */
function sort_table($obj, $row) {
    global $SysValue;

    $sort = $obj->PHPShopCategory->unserializeParam('sort');
    $vendor_array = unserialize($row['vendor_array']);
    $dis = $sortCat = $sortValue = null;
    $arrayVendorValue=array();
    //echo '1'.time().'<br/>';
    if (is_array($sort))
		$sortCat='id in (';
        foreach ($sort as $v) {
			$sortCat.=$v.',';
            //$sortCat.=' id=' . $v . ' OR';
        }
    //$sortCat = substr($sortCat, 0, strlen($sortCat) - 2);
	//echo $sortCat;
    if (!empty($sortCat)) {
		$sortCat = substr($sortCat, 0, strlen($sortCat) - 1).')';

        // Массив имен характеристик
        $PHPShopOrm = new PHPShopOrm();
		$PHPShopOrm->cache = true;		
        $PHPShopOrm->debug = $obj->debug;
        $result = $PHPShopOrm->query("select id,name,category from " . $SysValue['base']['sort_categories'] . " where ($sortCat and goodoption!='1') order by num");
        while (@$row = mysql_fetch_assoc($result)) {

		//nah~ не выводим эти харки - 3 иконко		
		$array=array('181','184','185','186');
		
		if (!in_array($row['id'],$array))
            $arrayVendor[$row['id']]=$row;
        }

        if (is_array($vendor_array))
			$sortValue='id in (';
            foreach ($vendor_array as $v) {
                foreach ($v as $value) {
                    if (is_numeric($value)) {
						$sortValue.=$value.',';
                        //$sortValue.=' id=' . $value . ' OR';
					}
				}
            }
        //$sortValue = substr($sortValue, 0, strlen($sortValue) - 2);

		//echo $sortValue;
		//echo '2'.time().'<br/>';
        if (!empty($sortValue)) {
			$sortValue = substr($sortValue, 0, strlen($sortValue) - 1).')';
            // Массив значений характеристик
            $PHPShopOrm = new PHPShopOrm();
			$PHPShopOrm->cache = true;			
            $PHPShopOrm->debug = $obj->debug;
            $result = $PHPShopOrm->query("select id,name,category from " . $SysValue['base']['sort'] . " where $sortValue order by num");
            while (@$row = mysql_fetch_array($result)) {
                @$arrayVendorValue[$row['category']]['name'].= ", " . $row['name'];
            }
			//echo '3'.time().'<br/>';
            $vendor_dis="";
            // Создаем таблицу характеристик с учетом сортировки
            if(is_array($arrayVendor))
			//$cnt=0;
			//print_r($arrayVendor).'<br/>';
            foreach ($arrayVendor as $idCategory => $value) {
                if (!empty($arrayVendorValue[$idCategory]['name'])) {
                    if (!empty($value['name'])) {
                    	//if ($value['name']=='Производитель') {
                    	//	$vendor_dis.=substr($arrayVendorValue[$idCategory]['name'], 2);//$value['name'].
                    	//}
                        //if (!empty($value['page']))
                        //    $dis.=PHPShopText::tr(PHPShopText::b($value['name']) . ': ', PHPShopText::a('../page/' . $value['page'] . '.html', substr($arrayVendorValue[$idCategory]['name'], 2)));
                        //else
                            $dis.=PHPShopText::tr(PHPShopText::b($value['name']) . ': ', substr($arrayVendorValue[$idCategory]['name'], 2));
                    }
                }
				//if ($cnt==5) break;
				//$cnt++;
			}
				//echo '4'.time().'<br/>';
				$disp = PHPShopText::table($dis);
				$obj->set('vendorDisp', $disp);
				//$obj->set('vendorName', $vendor_dis);
		}
    }
}

?>