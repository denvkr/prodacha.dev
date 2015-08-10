<?php

function query_multibase($obj) {
    
    $multi_cat = null;

    // Мультибаза
    if ($obj->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
        
        $where['servers'] = " REGEXP 'i" . $obj->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->debug = $obj->debug;
        $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 100));
        if (is_array($data)){
            foreach ($data as $row) {
                $multi_cat.='category=' . $row['id'] . ' or ';
            }
        $multi_cat = substr($multi_cat, 0, strlen($multi_cat) - 4);
        $multi_cat = ' and ('.$multi_cat.')';
        }
    }

    return $multi_cat;
}

/**
 * Составление SQL запроса для поиска товара
 * @author PHPShop Software
 * @version 1.1
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter($obj) {
    global $SysValue;

    if (!empty($_REQUEST['v']))
        $v = $_REQUEST['v'];
    else
        $v = null;

    if (!empty($_REQUEST['set']))
        $set = $_REQUEST['set'];
    else
        $set = 2;

    if (!empty($_REQUEST['pole']))
        $pole = $_REQUEST['pole'];
    else
        $pole = 1;

    if (!empty($_REQUEST['p']))
        $p = $_REQUEST['p'];
    else
        $p = 1;

    $cat = PHPShopSecurity::TotalClean(@$_REQUEST['cat'], 1);
    $words = trim(PHPShopSecurity::true_search(@$_REQUEST['words']));
    $num_row = $obj->PHPShopSystem->getValue('num_row');
    $num_ot = 0;
    $q = 0;
    $sortV = null;
    $sort = null;

    // Сортировка по характеристикам
    if (empty($_POST['v']))
        @$v = $SysValue['nav']['query']['v'];
    if (is_array($v)) {
    	//$file="base_".date("d_m_y_His")."query_filtr.txt";
    	//@$fp = fopen($file, "w");
    	//if ($fp) {
    	
    	foreach ($v as $key => $value) {
    		if (!empty($value)) {
    			$hash = $key . "-" . $value;
    			$sortV.=" and vendor like '%" . $hash . "%' ";
    			//fwrite($fp,'2'.$key."-".$value.$sortV);
    			//" and vendor REGEXP 'i" . $hash . "i' ";
    		}
    	}
    	 
    	//fclose($fp);
    }

    // Чистка запроса Secure Fix
    $words = PHPShopSecurity::true_search(PHPShopSecurity::TotalClean($words, 2));
    debug($words,'denvkr debugging');

    if ($set == 1) {
        switch ($pole) {
            case(1):
                $sort.="(name like '%".$words."%' or keywords like '%".$words."%') and "; 
				//'(name like \'%'.$words.'%\' or keywords like \'%'.$words.'%\') and '
                //"(name REGEXP '$words' or keywords REGEXP '$words') and "
                break;

            case(2):
                $sort.="(name like '%".$words."%' or content like '%".$words."%' or description like '%".$words."%' or keywords like '%".$words."%' or uid like '%".$words."%' or id = '".$words."') and ";
				//'(name like \'%'.$words.'%\' or content like \'%'.$words.'%\' or description like \'%'.$words.'%\' or keywords like \'%'.$words.'%\' or uid like \'%'.$words.'%\' or id = \''.$words.\'') and '
				//"(name REGEXP '$words' or content REGEXP '$words' or description REGEXP '$words' or keywords REGEXP '$words' or uid REGEXP '$words' or id = '$words') and "
                break;
                
            case(3):
              	$sort.="(name like '".$words."%') or ";
                //'(name like \'%'.$words.'%\' or content like \'%'.$words.'%\' or description like \'%'.$words.'%\' or keywords like \'%'.$words.'%\' or uid like \'%'.$words.'%\' or id = \''.$words.\'') and '
                //"(name REGEXP '$words' or content REGEXP '$words' or description REGEXP '$words' or keywords REGEXP '$words' or uid REGEXP '$words' or id = '$words') and "
                break;
        }
    } else {

        // Разделяем слова
        $_WORDS = explode(" ", $words);
        switch ($pole) {
            case(1):
                foreach ($_WORDS as $w)
                    $sort.="(name like '%".$w."%' or uid like '%".$w."%' or id = '".$w."' or keywords like '%".$w."%') and ";
		    		//'(name like \'%'.$w.'%\' or uid like \'%'.$w.'%\' or id = \''.$w.\'' or keywords like \'%'.$w.'%\') and '
		    		//"(name REGEXP '$w' or uid REGEXP '$w' or id = '$w' or keywords REGEXP '$w') and "
                break;

            case(2):
                foreach ($_WORDS as $w)
                    $sort.="(name like '%".$w."%' or content like '%".$w."%' or description like '%".$w."%' or keywords like '%".$w."%' or uid like '%".$w."%' or id = '".$w."') and ";
		    		//'(name like \'%'.$w.'%\' or content like \'%'.$w.'%\' or description like \'%'.$w.'%\' or keywords like \'%'.$w.'%\' or uid like \'%'.$w.'%\' or id = \''.$w.\'') and '
		    		//"(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') and "
                break;
                
            case(3):{
            	if (is_array($_WORDS)) {
            		$_WORDS1 = trim($words);
            		$_WORDS1 = str_replace(' ', '%',$_WORDS1);
            		$sort.="(name like '%".$_WORDS1."%')    ";
            	} else           		
	            	foreach ($_WORDS as $w) {
	            	   	$sort.="(name like '%".$w."%') or ";
	            	}
                	//'(name like \'%'.$words.'%\' or content like \'%'.$words.'%\' or description like \'%'.$words.'%\' or keywords like \'%'.$words.'%\' or uid like \'%'.$words.'%\' or id = \''.$words.\'') and '
                	//"(name REGEXP '$words' or content REGEXP '$words' or description REGEXP '$words' or keywords REGEXP '$words' or uid REGEXP '$words' or id = '$words') and "
            		//echo $_WORDS; 
            		//echo $sort;
            		$orderby="order by sortorder asc";
            		break;
            	}
                
        }
    }

    $sort = substr($sort, 0, strlen($sort) - 4);

    // По категориям
    if ($cat != 0)
        $string = " category=".$cat." and";
    else
        $string = null;

    // Перенаправление поиска
    $prewords = search_base($obj, $words);

    // Мультибаза
    $multibase = query_multibase($obj);
    
    // Все страницы
    if ($p == "all") {
        $sql = "select *,case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from " . $SysValue['base']['table_name2'] . " where ".$sort.$prewords.$multibase." and enabled='1' and parent_enabled='0' ".$orderby;
    }
    else
        while ($q < $p) {

            $sql = "select *,case when sklad='0' and outdated='0' then 1 when sklad='1' and outdated='0' then 2 when sklad='1' and outdated='1' then 3 end as sortorder from " . $SysValue['base']['table_name2'] . " where enabled='1' and parent_enabled='0' and ".$string.$sort.$prewords.$sortV.$multibase." ".$orderby." LIMIT ".$num_ot.",".$num_row;
            $q++;
            $num_ot = $num_ot + $num_row;
        }

    $obj->search_order = array(
        'words' => $words,
        'pole' => $pole,
        'set' => $set,
        'cat' => $cat,
        'string' => $string,
        'sort' => $sort,
        'prewords' => $prewords,
        'sortV' => $sortV
    );

    $obj->set('searchString', $words);

    if ($set == 1)
        $obj->set('searchSetA', 'checked');
    elseif ($set == 2)
        $obj->set('searchSetB', 'checked');
    else
        $obj->set('searchSetA', 'checked');

    if ($pole == 1)
        $obj->set('searchSetC', 'checked');
    elseif ($pole == 2)
        $obj->set('searchSetD', 'checked');
    else
        $obj->set('searchSetC', 'checked');

    // Возвращаем SQL запрос
    return $sql;
}

/**
 * Выдача переадресации поиска из БД
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param string $words строка поиска
 * @return string 
 */
function search_base($obj, $words) {
    $string = null;

    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->debug = $obj->debug;
    $result = $PHPShopOrm->query('select uid from ' . $GLOBALS['SysValue']['base']['table_name26'] . ' where name like \'%'.$words.'%\''); 
				//'select uid from ' . $GLOBALS['SysValue']['base']['table_name26'] . ' where name like \'%'.$words.'%\''
				//"select uid from " . $GLOBALS['SysValue']['base']['table_name26'] . " where name REGEXP 'i" . $words . "i'"
    while (@$row = mysql_fetch_array(@$result)) {
        $uid = $row['uid'];
        $uids = explode(",", $uid);
        foreach ($uids as $v)
            $string.="id=$v or ";
    }

    if (!empty($string)) {
        $string = substr($string, 0, strlen($string) - 3);
        $string = " OR (($string) AND enabled='1')";
    }

    return $string;
}

?>
