<?
include_once ($_SERVER['DOCUMENT_ROOT'] . '/custom_config/product_icons_config.php');
function catalog_product_icons($obj, $row) {
    global $SysValue;
	PHPShopObj::loadClass("orm");
	$PHPShopOrm = new PHPShopOrm();
	$PHPShopOrm->cache = $obj->cache;
	$PHPShopOrm->debug = $obj->debug;
	$PHPShopOrm->cache_format = $obj->cache_format;	
	//echo $row['id'].'<br/>';
	//$id=$row['id'];

	$id_tovara=$row['id'];
	
	//у товара в его массиве хар-к находим тот показатель,  что отвечает за нужные нам харки
	$vendor=$row['vendor'];						

	$price=$row['price'];

	//доп харки  - наличие,предпродажа и проч
	$array=array(186,185,181,184,302,42);														
					
	$retval='<ul class="product_icons">';
        //var_dump($row['vendor']);	
	foreach ($array as $j)
	{
		$reg="i".$j."-";//ищем по этому коду наш параметр					
		if(strpos($vendor,$reg)!==false)
		{
			//находим все значени€ из перемененой vendor  - с учетом того, что внтури нее может быть несколько одинаковых хар-к								
			$mas=explode($reg,$vendor);
			$usl="";			
			$h=1;	
			foreach ($mas as $value)//мисо	
			{
					if( (!empty($value)) AND (substr($value,0,1)!="i") )				
					{
						$s01=strpos($value,$reg2)+strlen($reg2);										
						$s02=strpos($value,"i",$s01); //конец искомого значени€
						$len0=$s02-$s01;				
						$id_img1=substr($value,$s01,$len0); //айди харки продукта из таблицы phpshop_sort													
						$usl.=" id='".$id_img1."' ";
						if (count($mas) > $h+1)
							$usl.=" OR ";
						$h++;
					}																					
			}							
			//echo $j.'<br/>';
			//идЄм вытаскиваем саму картинку

			if (!empty($usl)) {
				//$sql_1="SELECT name FROM ".$SysValue['base']['sort']." USE INDEX (name) WHERE ".$usl;
				//echo $sql_1.'<br/>';
				//$PHPShopOrm->sql=$sql_1;
				//$res1=$PHPShopOrm->select();
				$res1=$PHPShopOrm->query("SELECT name FROM ".$SysValue['base']['sort']." USE INDEX (name) WHERE ".$usl);
				//echo "SELECT name FROM ".$SysValue['base']['sort']." WHERE ".$usl.'<br/>';
				$row1=mysql_fetch_assoc($res1);
				$src=$row1['name'];
				//echo $src.'<br/>';
				//foreach($res1 as $prod_row1) {
				//$src=$res1[0][name];
				//}
				//идЄм вытаскиваем само описание
				//$sql_1="SELECT name,description FROM ".$SysValue['base']['sort_categories']." USE INDEX (name_2) WHERE id='".$j."'";
				//$PHPShopOrm->sql=$sql_1;
				//$res2=$PHPShopOrm->select();
				$res2=$PHPShopOrm->query("SELECT id,name,description FROM ".$SysValue['base']['sort_categories']." USE INDEX (name_2) WHERE id='".$j."'");
				$row2=mysql_fetch_assoc($res2);
				//$f=mysql_num_rows($res2);
                                
                                $icon_text=call_user_func_array('get_icon_bottom_text', array($row2['id'],$_COOKIE['sincity']));
                                
                                //¬ зависимости от региона и наличи€ на складе добавл€ем текст
                                if ( ($_COOKIE['sincity']=="m" && (int)$row['stockgroup']==11 && $id_img1=='1200') ) {
                                    $additiondscr='.<br><a href="/page/store.html" itemprop="url"><span itemprop="title">ћагазин м. ƒинамо</span></a><br><a href="/page/shop.html" itemprop="url"><span itemprop="title">ћагазин ћинское ш.</span></a>';
                                }elseif ( ($_COOKIE['sincity']=="m" && (int)$row['stockgroup']==10 && $id_img1=='1200') ) {
                                    $additiondscr='.<br><a href="/page/store.html" itemprop="url"><span itemprop="title">ћагазин м. ƒинамо</span></a>';
                                }elseif ( ($_COOKIE['sincity']=="m" && (int)$row['stockgroup']==1 && $id_img1=='1200') ) {
                                    $additiondscr='.<br><a href="/page/shop.html" itemprop="url"><span itemprop="title">ћагазин ћинское ш.</span></a>';
                                } else {
                                    $additiondscr='';                                    
                                }
                                    
				$tovar=$icon_text['bottom_text'];//$row2['name'];
				$dscr='<p>'.$icon_text['description'].$additiondscr.'</p>';//$row2['description']
				//foreach($res1 as $prod_row1) {
				//$tovar=$res2[0]['name'];
				//$dscr='<p>'.$res2[0]['description'].'</p>';
				//}
				//echo $src.'<br/>';
				if (!empty($src)){
					//$total_icons++;
                                        //иконка с дилером
                                        $icondealer=call_user_func_array('get_icon_dealer',array(intval($id_img1)));
					if (($_COOKIE['sincity']!="m" && $id_img1!='1200' && $id_img1!='3375' && $icondealer===false && $j!==42) || ($_COOKIE['sincity']=="m" && !empty($id_img1) && $id_img1!='3375' && $icondealer===false && $j!==42)) {
						if ($id_img1=='1200') {
							$href='<img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab7\';" src="'.$src.'" alt="">';//document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';
						} else if ($id_img1==1193) {
							$href='<img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab6\';" src="'.$src.'" alt="">'; //document.getElementsByClassName(\'tabNavigation\')[0].children[1].className=\'selected\';
						} else {
							$href='<img src="'.$src.'" alt="">';
						}
						
						$retval.='<li>
						<div class="product_icon">
						<div class="product_icon_img">'.$href.'</div>
						</div>
						<div class="product_icon_desc">
						'.$dscr.'
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
						$product_icon_desc='<table><tr><td><a href="//'.$GLOBALS['SysValue']['other']['serverName'].'/shop/UID_'.$row['gift'].'.html"><div style="width:60px;height:60px;background: url('.$row3375['pic_small'].'); background-repeat: no-repeat;background-position: center;-webkit-background-size: contain;-moz-background-size: contain;-o-background-size: contain;background-size: contain;"></div></a></td><td><a href="//'.$GLOBALS['SysValue']['other']['serverName'].'/shop/UID_'.$row['gift'].'.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">'.$row3375['name'].'</a><br><span style="color: #e7193f;font: 14px Arial,Helvetica,sans-serif;font-weight: bold;"><strike>'.$row3375['price'].' руб.</strike></span><br><span style="font: 14px Arial,Helvetica,sans-serif;font-weight: bold;color:#6C4B46;">¬ подарок!</span></td></tr></table>';
						$retval.='<li>
						<div class="product_icon_desc">
						'.$product_icon_desc.'
						</div>
						<div class="product_icon">
                                                <a href="//'.$GLOBALS['SysValue']['other']['serverName'].'/shop/UID_'.$row['gift'].'.html">
						<div class="product_icon_img">'.$href.'</div>
                                                </a>
						</div>
						</li>
						';
					}
                                        //var_dump($id_img1,$icondealer,$j);
					if ($icondealer!==false && $j===42) {
                                            //var_dump($id_img1,$icondealer);
						//идЄм вытаскиваем само описание
						$res3375=$PHPShopOrm->query("SELECT name,price,pic_small FROM ".$SysValue['base']['products']." WHERE id='".$row['gift']."'");
						//$res2=mysql_query($sql2);
						$row3375=mysql_fetch_assoc($res3375);
						
						$href='<img src="'.$src.'" alt="">';
						$retval.='<li>
						<div class="product_icon_desc" style="width: 400px!important;">
						'.$icondealer['description'].'
						</div>
						<div class="product_icon">
                                                <a id="showsertificatehref" href="#" onclick="showsertificate(\'/UserFiles/Image/'.$id_img1.'.jpg\')">
						<div class="product_icon_img">'.'<img src="'.$icondealer['icon'].'" alt="">'.'</div>
                                                </a>
						</div>
						</li>
						';
					}						
	
				}								
			}		
		}
	}
        $icon_text=call_user_func_array('get_icon_bottom_text', array('dostavka',$_COOKIE['sincity']));
	if ( ($_COOKIE['sincity']=="m") ) {
		if ($price>=10000) {
				$retval.='<li>
                                <div class="product_icon_desc"><p>'.$icon_text['description'].'</p>
                                </div>
                                <div class="product_icon">
                                <div class="product_icon_img"><img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab7\';" src="images/delivery_small.png" alt=""></div>
                                </div>
                                </li>';
		}
	
	}

	$retval.="</ul>";
	//echo $retval;
	$obj->set('Producticons',$retval);
}
?>