<?
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
	//�� ������ ����� ���� ������
	$url=$_SERVER['REQUEST_URI'];
	
	$s1=strpos($url,"UID_")+4;	//������� ������
	
	$s2=strpos($url,".html");
	$len=$s2-$s1;
	
	$id=substr($url,$s1,$len);
	//echo $id;
	$vuvod='<ul class="product_icons">';
	
	//���� �� ��
	//$sql0="SELECT id,vendor,price FROM ".$GLOBALS['SysValue']['base']['products']." WHERE id='".$id."' ";
	//$res0=mysql_query($sql0);
	
	//$row0=mysql_fetch_assoc($res0);
	
	$id_tovara=$row['id'];
	//echo $row['id'];
	//� ������ � ��� ������� ���-� ������� ��� ����������,  ��� �������� �� ������ ��� �����
	$vendor=$row['vendor'];
	//echo $vendor;
	//echo "<!-- $vendor -->";
	
	$price=$row['price'];
	
	//�������� ������� ��� ������  - ��� �� ������� ������ ����� ������� ������
	$shop_urls['�. ������']="http://prodacha.ru/page/store.html";
	$shop_urls['������� �����']="http://prodacha.ru/page/shop.html";
	$shop_urls['���������� ��-�.']="http://prodacha.ru/page/shop_spb.html";
		
	//��� �����  - �������,����������� � ����
	$array=array(186,185,181,184);
	
	$total_icons=0;
	
	foreach ($array as $j)
	{
		$reg="i".$j."-";//���� �� ����� ���� ��� ��������
			//echo $reg;
		if(strpos($vendor,$reg)!==false)
		{
				
			//������� ��� �������� �� ����������� vendor  - � ������ ����, ��� ������ ��� ����� ���� ��������� ���������� ���-�
			$mas=explode($reg,$vendor);
			$usl="";
			//print_r($mas);
			$h=1;
				
			foreach ($mas as $value)//����
			{
				//echo $value;
				if( (!empty($value)) AND (substr($value,0,1)!="i") )
				{
					$s01=strpos($value,$reg2)+strlen($reg2);
					$s02=strpos($value,"i",$s01); //����� �������� ��������
	
					$len0=$s02-$s01;
					$id_img1=substr($value,$s01,$len0); //���� ����� �������� �� ������� phpshop_sort
					//echo $value;	
					$usl.=" id='".$id_img1."' ";
	
					if (count($mas) > $h+1)
						$usl.=" OR ";
						
					$h++;
				}
			}
				
	
			#echo '1'.$usl;
			if (!empty($usl)) {
				
				//��� ����������� ���� ��������
				$res1=$PHPShopOrm->query("SELECT name FROM ".$SysValue['base']['sort']." USE INDEX (name) WHERE ".$usl);
				//$res1=mysql_query($sql1);
				
				/*
				 if ($j==186)//������ ���� �������
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
				//��� ����������� ���� ��������
				$res2=$PHPShopOrm->query("SELECT name,description FROM ".$SysValue['base']['sort_categories']." WHERE id='".$j."'");
				//$res2=mysql_query($sql2);
				$row2=mysql_fetch_assoc($res2);
				
				/*
				 if ($j==186) //� ������ � ��������
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
				
				$tovar=$row2['description']; //-  ������ ������� �������� � ��������
				$dscr='<div class="header">'.$row2['name'].'</div>'.$shops;
				
				
				}
				else //��������� ����������� �����
				{
				$tovar=$row2['name'];
				$dscr='<p>'.$row2['description'].'</p>';
				}
				*/
				$tovar=$row2['name'];
				$dscr='<p>'.$row2['description'].'</p>';
				//echo '3';
				//echo $id_img1.' ';
				if (!empty($src)) {
					if (($_COOKIE['sincity']!="m" && $id_img1!='1200') || ($_COOKIE['sincity']=="m" && !empty($id_img1))) {
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
				
				}
				}
				
				}	
			}
			if ( ($_COOKIE['sincity']=="m") ) {
				if ($price>=10000) {
					if ( $total_icons<4 ) {
						$href='<img onclick="document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].focus();" src="images/delivery.png" alt="">'; //document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';
						$vuvod.='<li>
						<div class="product_icon_desc"><p>&nbsp;&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a></p>
						</div>
						<div class="product_icon">
						<div class="product_icon_img">'.$href.'</div>
						<div>�������� ���������</div>
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
						$href='<img onclick="document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].click();document.getElementsByClassName(\'tabNavigation\')[0].children[2].children[0].focus();" src="images/delivery.png" alt="">'; //document.getElementsByClassName(\'tabNavigation\')[0].children[2].className=\'selected\';
						$vuvod.='<br /><ul class="product_icons_1"><li>
						<div class="product_icon_desc_1"><br />&nbsp;������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp;<br />&nbsp;<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a><br />&nbsp;
						</div>
						<div class="product_icon">
						<div class="product_icon_img">'.$href.'</div>
						<div>�������� ���������</div>
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