<?

function catalog_product_icons($obj, $row) {
    global $SysValue;
	PHPShopObj::loadClass("orm");
	$PHPShopOrm = new PHPShopOrm();
	$PHPShopOrm->cache = $obj->cache;
	$PHPShopOrm->debug = $obj->debug;
	$PHPShopOrm->cache_format = $obj->cache_format;	
	//echo $row['id'].'<br/>';
	$id=$row['id'];

	$id_tovara=$row['id'];
	
	//у товара в его массиве хар-к находим тот показатель,  что отвечает за нужные нам харки
	$vendor=$row['vendor'];						

	$price=$row['price'];

	//доп харки  - наличие,предпродажа и проч
	$array=array(186,185,181,184,302);														
					
	$retval='<ul class="product_icons">';
	
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
				$res2=$PHPShopOrm->query("SELECT name,description FROM ".$SysValue['base']['sort_categories']." USE INDEX (name_2) WHERE id='".$j."'");
				$row2=mysql_fetch_assoc($res2);
				//$f=mysql_num_rows($res2);
				$tovar=$row2['name'];
				$dscr='<p>'.$row2['description'].'</p>';
				//foreach($res1 as $prod_row1) {
				//$tovar=$res2[0]['name'];
				//$dscr='<p>'.$res2[0]['description'].'</p>';
				//}
				//echo $src.'<br/>';
				if (!empty($src)){
					//$total_icons++;
					if (($_COOKIE['sincity']!="m" && $id_img1!='1200' && $id_img1!='3375') || ($_COOKIE['sincity']=="m" && !empty($id_img1) && $id_img1!='3375')) {
						if ($id_img1==1200) {
							$href='<img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab7\';" src="'.$src.'" alt="">';//document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';
						} else if ($id_img1==1193) {
							$href='<img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab6\';" src="'.$src.'" alt="">'; //document.getElementsByClassName(\'tabNavigation\')[0].children[1].className=\'selected\';
						} else {
							$href='<img src="'.$src.'" alt="">';
						}
						
						$retval.='<li>
						<div class="product_icon">
						<div class="product_icon_img">'.$href.'</div>
						<!-- <div>'.$tovar.'</div> -->
						</div>
						<div class="product_icon_desc">
						'.$dscr.'
						</div>
						</li>
						';
					}
					if ($id_img1=='3375') {
						//идЄм вытаскиваем само описание
						$res3375=$PHPShopOrm->query("SELECT name,price,pic_small FROM ".$SysValue['base']['products']." WHERE id='".$row['gift']."'");
						//$res2=mysql_query($sql2);
						$row3375=mysql_fetch_assoc($res3375);
					
						$href='<img src="'.$src.'" alt="">';
						$product_icon_desc='<table><tr><td><img width="60" height="80" src="'.$row3375['pic_small'].'" alt="" style="max-width:100%;max-height:100%;"></td><td><a href="http://prodacha.ru/shop/UID_'.$row['gift'].'.html" style="color: #588910;font: 12px/1.4 Arial,Helvetica,sans-serif;">'.$row3375['name'].'</a><br><span style="color: #e7193f;font: 14px Arial,Helvetica,sans-serif;font-weight: bold;"><strike>'.$row3375['price'].' руб.</strike></span><br><span style="font: 14px Arial,Helvetica,sans-serif;font-weight: bold;color:#6C4B46;">¬ подарок!</span></td></tr></table>';
						$retval.='<li>
						<div class="product_icon_desc">
						'.$product_icon_desc.'
						</div>
						<div class="product_icon">
						<div class="product_icon_img">'.$href.'</div>
						<!--<div>'.$tovar.'</div> -->
						</div>
						</li>
						';
					}
						
				}								
			}		
		}
	}

	if ( ($_COOKIE['sincity']=="m") ) {
		if ($price>=10000) {
			$href='<img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab7\';" src="images/delivery_small.png" alt="">'; //window.location=\'/shop/UID_'.$id_img1.'.html\';document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();
				$retval.='<li>
				<div class="product_icon_desc"><p>&nbsp;&nbsp;«аказы от 10000 руб. доставл€ютс€ бесплатно в пределах ћ јƒ&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="¬се услови€ доставки">¬се услови€ доставки</a></p>
				</div>
				<div class="product_icon">
				<div class="product_icon_img">'.$href.'</div>
				</div>
				</li>';
		}
	
	}

	$retval.="</ul>";
	//echo $retval;
	$obj->set('Producticons',$retval);
}
?>