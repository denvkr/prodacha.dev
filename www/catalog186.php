<?
	$host="localhost";          
	$user_db="u301639_second";           
	$pass_db="peREneSti-AB6E";       
	$dbase="u301639";
				
				$dbcnx=mysql_connect($host,$user_db,$pass_db);
				
				if (!$dbcnx) 
				{
				  echo("<p> error MySql </p>");
				   exit;
				}
																
				if (!@mysql_select_db($dbase,$dbcnx)) 
				{
				   echo("<p> error DB MySql </p>");	
				   exit;
				}
				
							
				
				//���� �� ��
				$sql0="SELECT id,sort FROM phpshop_categories";
				$res0=mysql_query($sql0);

				while ($row0=mysql_fetch_assoc($res0)){
					$categoriesarray=unserialize($row0['sort']);
					//var_dump($categoriesarray);
                                        if (!is_null($categoriesarray))
					if (array_key_exists ( '186' ,$categoriesarray)) 
                                                $resultarr[]=$row0['id'];
				}
				var_dump($resultarr);
				//var_dump(unserialize($row0['sort']));
				
                                die();
				//��� �����  - �������,����������� � ����
				$array=array(186,185,181,184);														
				
				$total_icons=0;
				
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
					
						
						//echo $usl;
						//��� ����������� ���� ��������			
						$sql1="SELECT name FROM ".$GLOBALS['SysValue']['base']['sort']." WHERE ".$usl." ";
						$res1=mysql_query($sql1);				

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
						//echo empty($src).'||'.$src;
						//��� ����������� ���� ��������			
						$sql2="SELECT name,description FROM ".$GLOBALS['SysValue']['base']['sort_categories']." WHERE id='".$j."'";
						$res2=mysql_query($sql2);				
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
						
						//echo $id_img1.' ';
						if (!empty($src)) {
							if (($_COOKIE['sincity']!="m" && $id_img1!='1200') || ($_COOKIE['sincity']=="m" && !empty($id_img1))) {
								//echo $src;
								$total_icons++;
								$vuvod.='<li>
								<div class="product_icon_desc">
								'.$dscr.'
								</div>
								<div class="product_icon">
								<div class="product_icon_img"><img src="'.$src.'" alt=""></div>
								<div>'.$tovar.'</div>
								</div>
								</li>
								';
							}
								
						}
					}
				
				}

				if ( ($_COOKIE['sincity']=="m") ) {
					if ($price>=10000) {
						if ( $total_icons<4 ) {
							$vuvod.='<li>
							<div class="product_icon_desc"><p>&nbsp&nbsp������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp<br />&nbsp&nbsp<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a></p>
							</div>
							<div class="product_icon">
							<div class="product_icon_img"><img src="images/delivery.png" alt=""></div>
							<div>�������� ���������</div>
							</div>
							</li>';
						}
					}
				
				}
				
				$vuvod.="</ul>";
					
				if ( ($_COOKIE['sincity']=="m") ) {
					if ($price>=10000) {
						if ( $total_icons>=4 ) {
							$vuvod.='<br /><ul class="product_icons_1"><li>
							<div class="product_icon_desc_1"><br />&nbsp������ �� 10000 ���. ������������ ��������� � �������� ����&nbsp<br />&nbsp<a href="/page/delivery.html" title="��� ������� ��������">��� ������� ��������</a><br />&nbsp
							</div>
							<div class="product_icon">
							<div class="product_icon_img"><img src="images/delivery.png" alt=""></div>
							<div>�������� ���������</div>
							</div>
							</li></ul>';
						}
					}
				
				}				
				echo $vuvod;

?>