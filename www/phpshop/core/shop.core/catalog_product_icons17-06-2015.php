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
	
	//� ������ � ��� ������� ���-� ������� ��� ����������,  ��� �������� �� ������ ��� �����
	$vendor=$row['vendor'];						

	$price=$row['price'];

	//��� �����  - �������,����������� � ����
	$array=array(186,185,181,184);														
					
	$retval='<ul class="product_icons">';
	
	foreach ($array as $j)
	{
		$reg="i".$j."-";//���� �� ����� ���� ��� ��������					
		if(strpos($vendor,$reg)!==false)
		{
			//������� ��� �������� �� ����������� vendor  - � ������ ����, ��� ������ ��� ����� ���� ��������� ���������� ���-�								
			$mas=explode($reg,$vendor);
			$usl="";			
			$h=1;	
			foreach ($mas as $value)//����	
			{
					if( (!empty($value)) AND (substr($value,0,1)!="i") )				
					{
						$s01=strpos($value,$reg2)+strlen($reg2);										
						$s02=strpos($value,"i",$s01); //����� �������� ��������
						$len0=$s02-$s01;				
						$id_img1=substr($value,$s01,$len0); //���� ����� �������� �� ������� phpshop_sort													
						$usl.=" id='".$id_img1."' ";
						if (count($mas) > $h+1)
							$usl.=" OR ";
						$h++;
					}																					
			}							
			//echo $j.'<br/>';
			//��� ����������� ���� ��������

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
				//��� ����������� ���� ��������
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
				}								
			}		
		}
	}

	if ( ($_COOKIE['sincity']=="m") ) {
		if ($price>=10000) {
			$href='<img onclick="window.location=\'/shop/UID_'.$id_tovara.'.html#tab7\';" src="images/delivery_small.png" alt="">'; //window.location=\'/shop/UID_'.$id_img1.'.html\';document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();
				$retval.='<li>
				<div class="product_icon_desc"><p>&nbsp;&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a></p>
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