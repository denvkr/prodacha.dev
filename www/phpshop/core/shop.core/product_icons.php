<?
include_once ($_SERVER['DOCUMENT_ROOT'] . '/custom_config/product_icons_config.php');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
function product_icons($obj, $row) {
	global $SysValue;
	PHPShopObj::loadClass("orm");
	$PHPShopOrm = new PHPShopOrm();
	$PHPShopOrm->cache = $obj->cache;
	$PHPShopOrm->debug = $obj->debug;
	$PHPShopOrm->cache_format = $obj->cache_format;
	//из строки берем айди товара
	$url=$_SERVER['REQUEST_URI'];
	
	$s1=strpos($url,"UID_")+4;	//вариант товара
	
	$s2=strpos($url,".html");
	$len=$s2-$s1;
	
	$id=substr($url,$s1,$len);
	//echo $id;
	$vuvod='<ul class="product_icons">';
	
	//инфо из бд
	//$sql0="SELECT id,vendor,price FROM ".$GLOBALS['SysValue']['base']['products']." WHERE id='".$id."' ";
	//$res0=mysql_query($sql0);
	
	//$row0=mysql_fetch_assoc($res0);
	
	$id_tovara=$row['id'];
	//echo $row['id'];
	//у товара в его массиве хар-к находим тот показатель,  что отвечает за нужные нам харки
	$vendor=$row['vendor'];
	//echo $vendor;
	//echo "<!-- $vendor -->";
	
	$price=$row['price'];
	
	//названи€ магазов дл€ ссылок  - ибо со вбитием ссылок через админку траблы
	$shop_urls['м. ƒинамо']="http://prodacha.ru/page/store.html";
	$shop_urls['ћинское шоссе']="http://prodacha.ru/page/shop.html";
	$shop_urls['Ћахтинский пр-т.']="http://prodacha.ru/page/shop_spb.html";
		
	//доп харки  - наличие,предпродажа и проч
	$array=array(186,185,181,184,302,42);
	
	$total_icons=0;
	//var_dump(unserialize ($row['vendor_array']));
	//var_dump($row['vendor']);
	foreach ($array as $j) {
		$reg="i".$j."-";//ищем по этому коду наш параметр
			//echo $reg;
		if(strpos($vendor,$reg)!==false)
		{
				
			//находим все значени€ из перемененой vendor  - с учетом того, что внутри нее может быть несколько одинаковых хар-к
			$mas=explode($reg,$vendor);
			$usl="";
			//print_r($mas);
			$h=1;
				
			foreach ($mas as $value)//мисо
			{
				//echo $value;
				if( (!empty($value)) AND (substr($value,0,1)!="i") )
				{
					$s01=strpos($value,$reg2)+strlen($reg2);
					$s02=strpos($value,"i",$s01); //конец искомого значени€
	
					$len0=$s02-$s01;
					$id_img1=substr($value,$s01,$len0); //айди харки продукта из таблицы phpshop_sort
					//echo $value;	
					$usl.=" id='".$id_img1."' ";
	
					if (count($mas) > $h+1)
						$usl.=" OR ";
						
					$h++;
				}
			}
				
	
			#echo '1'.$usl;
			if (!empty($usl)) {
				
				//идЄм вытаскиваем саму картинку
				$res1=$PHPShopOrm->query("SELECT name FROM ".$SysValue['base']['sort']." USE INDEX (name) WHERE ".$usl);
				//$res1=mysql_query($sql1);
				
				/*
				 if ($j==186)//случай неск магазов
				{
				while($row1=mysql_fetch_assoc($res1))
				{
				$id=$row1['id'];
				$characters[$id]=$row1['name'];
				}
				}
				else
				{
				$row1=mysql_fetch_assoc($res1);
				$src=$row1['name'];
				}
				*/
				$row1=mysql_fetch_assoc($res1);
				$src=$row1['name'];
				//echo '2';
				//echo empty($src).'||'.$src;
				//идЄм вытаскиваем само описание
				$res2=$PHPShopOrm->query("SELECT id,name,description FROM ".$SysValue['base']['sort_categories']." WHERE id='".$j."'");
                                //echo "SELECT id,name,description FROM ".$SysValue['base']['sort_categories']." WHERE id='".$j."'";
				//$res2=mysql_query($sql2);
				$row2=mysql_fetch_assoc($res2);
				
				/*
				 if ($j==186) //в случае с наличием
				{
				
				foreach($characters as $key=>$val)
				{
				if ($key!=1200)
				{
				$substite=$shop_urls[$val];
				$shops.='<p><a href="'.$substite.'" target="_blank" >'.$val.'</a></p>';
				}
				}
				
				$src=$characters['1200'];
				
				$tovar=$row2['description']; //-  мен€ем местами описание и название
				$dscr='<div class="header">'.$row2['name'].'</div>'.$shops;
				
				
				}
				else //остальные картиношные харки
				{
				$tovar=$row2['name'];
				$dscr='<p>'.$row2['description'].'</p>';
				}
				*/
                                $icon_text=call_user_func_array('get_icon_bottom_text', array($row2['id'],$_COOKIE['sincity']));
                                
                                $additiondscr='';
                                //¬ зависимости от региона и наличи€ на складе добавл€ем текст
                                if ( ($_COOKIE['sincity']=="m" && (int)$row['stockgroup']==11) ) {
                                    $additiondscr='. ƒинамо + ћинское ш.';
                                }
                                if ( ($_COOKIE['sincity']=="m" && (int)$row['stockgroup']==10) ) {
                                    $additiondscr='. ƒинамо';
                                }
                                if ( ($_COOKIE['sincity']=="m" && (int)$row['stockgroup']==1) ) {
                                    $additiondscr='. ћинское ш.';
                                }
				$tovar=$icon_text['bottom_text'];//$row2['name'];
				$dscr='<p>'.$icon_text['description'].$additiondscr.'</p>';//$row2['description']
				//echo '3';
				//echo $id_img1.' ';
                                //иконка с дилером
                                $icondealer=call_user_func_array('get_icon_dealer',array(intval($id_img1)));
				if (!empty($src)) {
					if (($_COOKIE['sincity']!="m" && $id_img1!='1200' && $id_img1!='3375' && $icondealer===false && $j!==42) || ($_COOKIE['sincity']=="m" && !empty($id_img1) && $id_img1!='3375' && $icondealer===false && $j!==42)) {
						$total_icons++;
						//echo $id_img1;
						if ($id_img1==1200) {
							$href='<img onclick="document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].focus();" src="'.$src.'" alt="">';//document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';
						} else if ($id_img1==1193) {
							$href='<img onclick="document.getElementsByClassName(\'tabNavigation\')[0].children[1].children[0].click();document.getElementsByClassName(\'tabNavigation\')[0].children[1].children[0].focus();" src="'.$src.'" alt="">'; //document.getElementsByClassName(\'tabNavigation\')[0].children[1].className=\'selected\';		
						} else {
							$href='<img src="'.$src.'" alt="">';
						}
						$vuvod.='<li>
						<div class="product_icon_desc">
						'.$dscr.'
						</div>
						<div class="product_icon">
						<div class="product_icon_img">'.$href.'</div>
						<div>'.$tovar.'</div>
						</div>
						</li>
						';
					}
                                        //иконка с подарком
					if ($id_img1=='3375') {
						//идЄм вытаскиваем само описание
						$res3375=$PHPShopOrm->query("SELECT name,price,pic_small FROM ".$SysValue['base']['products']." WHERE id='".$row['gift']."'");
						//$res2=mysql_query($sql2);
						$row3375=mysql_fetch_assoc($res3375);
						
						$href='<img src="'.$src.'" alt="">';
                                                //логика дл€ нескольких подарков
                                                $product_icon_desc=call_user_func_array('get_more_gift', array($GLOBALS['SysValue']['other']['serverName'],intval($id_tovara)));
                                                if ($product_icon_desc===false)
                                                    $product_icon_desc='<table><tr><td><a href="http://'.$GLOBALS['SysValue']['other']['serverName'].'/shop/UID_'.$row['gift'].'.html"><div style="width:80px;height:80px;background: url('.$row3375['pic_small'].'); background-repeat: no-repeat;background-position: center;-webkit-background-size: contain;-moz-background-size: contain;-o-background-size: contain;background-size: contain;"></div></a></td><td><a href="http://'.$GLOBALS['SysValue']['other']['serverName'].'/shop/UID_'.$row['gift'].'.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">'.$row3375['name'].'</a><br><span style="color: #e7193f;font: 14px Arial,Helvetica,sans-serif;font-weight: bold;"><strike>'.$row3375['price'].' руб.</strike></span><br><span style="font: 14px Arial,Helvetica,sans-serif;font-weight: bold;color:#6C4B46;">¬ подарок!</span></td></tr></table>';
						$vuvod.='<li>
						<div class="product_icon_desc">
						'.$product_icon_desc.'
						</div>
						<div class="product_icon">
                                                <a href="http://'.$GLOBALS['SysValue']['other']['serverName'].'/shop/UID_'.$row['gift'].'.html">
						<div class="product_icon_img">'.$href.'</div>
                                                </a>
						<div style="color:#e7193f">'.$icon_text['bottom_text'].'</div>
						</div>
						</li>
						';
					}

                                        //var_dump($id_img1,$icondealer,$j);
					if ($icondealer<>false && $j===42) {
                                            //var_dump($id_img1,$icondealer);
						$vuvod.='<li>
						<div class="product_icon_desc" style="width: 400px!important;">
						'.$icondealer['description'].'
						</div>
						<div class="product_icon">
                                                <a id="showsertificatehref" href="#" onclick="showsertificate(\'/UserFiles/Image/'.$id_img1.'.jpg\')">
						<div class="product_icon_img">'.'<img src="'.$icondealer['icon'].'" alt="">'.'</div>
						<div style="color:#383838">'.'ќфициальный дилер'.'</div>
                                                </a>
						</div>
						</li>
						';
					}						
                                        //$vuvod='';
				}
				}
				
				}	
	}
        $icon_text=call_user_func_array('get_icon_bottom_text', array('dostavka',$_COOKIE['sincity']));
        if ( ($_COOKIE['sincity']=="m") ) {
                if ($price>=10000) {
                        if ( $total_icons<4 ) {
                                $vuvod.='<li>
                                <div class="product_icon_desc"><p>'.$icon_text['description'].'</p>
                                </div>
                                <div class="product_icon">
                                <div class="product_icon_img"><img onclick="document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].focus();" src="images/delivery.png" alt=""></div>
                                <div>'.$icon_text['bottom_text'].'</div>
                                </div>
                                </li>';
                        }
                }

        }
        //echo '4';
        $vuvod.="</ul>";

        if ( ($_COOKIE['sincity']=="m") ) {
                if ($price>=10000) {
                        if ( $total_icons>=4 ) {
                                $vuvod.='<br /><ul class="product_icons_1"><li>
                                <div class="product_icon_desc_1"><br />'.$icon_text['description'].'<br />&nbsp;
                                </div>
                                <div class="product_icon">
                                <div class="product_icon_img"><img onclick="document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].focus();" src="images/delivery.png" alt=""></div>
                                <div>ƒоставка бесплатно</div>
                                </div>
                                </li></ul>';
                        }
                }

        }

        //echo $vuvod;
        $obj->set('Product_full_icons',$vuvod);
        //echo '5';
}
?>