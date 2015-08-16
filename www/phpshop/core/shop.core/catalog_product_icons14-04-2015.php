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
	$array=array(186,185,181,184);														
					
	$retval='<ul class="product_icons">';
	
	foreach ($array as $j)
	{
		$reg="i".$j."-";//ищем по этому коду наш параметр					
		if(strpos($vendor,$reg)!==false)
		{
			//находим все значения из перемененой vendor  - с учетом того, что внтури нее может быть несколько одинаковых хар-к								
			$mas=explode($reg,$vendor);
			$usl="";			
			$h=1;	
			foreach ($mas as $value)//мисо	
			{
					if( (!empty($value)) AND (substr($value,0,1)!="i") )				
					{
						$s01=strpos($value,$reg2)+strlen($reg2);										
						$s02=strpos($value,"i",$s01); //конец искомого значения
						$len0=$s02-$s01;				
						$id_img1=substr($value,$s01,$len0); //айди харки продукта из таблицы phpshop_sort													
						$usl.=" id='".$id_img1."' ";
						if (count($mas) > $h+1)
							$usl.=" OR ";
						$h++;
					}																					
			}							
			//echo $j.'<br/>';
			//идём вытаскиваем саму картинку

			if (!empty($usl)) {
				//$sql_1="SELECT name FROM ".$SysValue['base']['sort']." USE INDEX (name) WHERE ".$usl;
				//echo $sql_1.'<br/>';
				//$PHPShopOrm->sql=$sql_1;
				//$res1=$PHPShopOrm->select();
				$res1=$PHPShopOrm->query("SELECT name FROM ".$SysValue['base']['sort']." USE INDEX (name) WHERE ".$usl);
				//echo "SELECT name FROM ".$SysValue['base']['sort']." WHERE ".$usl.'<br/>';
				$row1=mysql_fetch_assoc($res1);
				$src=$row1['name'];
				//foreach($res1 as $prod_row1) {
				//$src=$res1[0][name];
				//}
				//идём вытаскиваем само описание
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
					if (($_COOKIE['sincity']!="m" && $id_img1!='1200') || ($_COOKIE['sincity']=="m" && !empty($id_img1))) {
						$retval.='<li>
						<div class="product_icon">
						<div class="product_icon_img"><img src="'.$src.'" alt="" ></div>
						<!-- <div>'.$tovar.'</div> -->
						</div>
						<div class="product_icon_desc">
						'.$dscr.'
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
				$retval.='<li>
				<div class="product_icon_desc"><p>&nbsp&nbspЗаказы от 10000 руб. доставляются бесплатно в пределах МКАД&nbsp<br />&nbsp&nbsp<a href="/page/delivery.html" title="Все условия доставки">Все условия доставки</a></p>
				</div>
				<div class="product_icon">
				<div class="product_icon_img"><img src="images/delivery_small.png" alt=""></div>
				</div>
				</li>';
		}
	
	}

	$retval.="</ul>";

	$obj->set('Producticons',$retval);
}
?>