<?


				$host="u301639.mysql.masterhost.ru";          
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
				
							
				//�� ������ ����� ���� ������ 
				$url=$_SERVER['REQUEST_URI'];				
				
				$s1=strpos($url,"UID_")+4;	//������� ������											
				
				$s2=strpos($url,".html");				
				$len=$s2-$s1;			
								
				$id=substr($url,$s1,$len);				
				
				$vuvod='<ul class="product_icons">';
				
				//���� �� ��
				$sql0="SELECT * FROM phpshop_products WHERE id='".$id."' ";
				$res0=mysql_query($sql0);
				
				$row0=mysql_fetch_assoc($res0);
				
				$id_tovara=$row0['id'];
				
				//� ������ � ��� ������� ���-� ������� ��� ����������,  ��� �������� �� ������ ��� �����
				$vendor=$row0['vendor'];						
				
				//echo "<!-- $vendor -->";

				//�������� ������� ��� ������  - ��� �� ������� ������ ����� ������� ������
				$shop_urls['�. ������']="http://prodacha.ru/page/store.html";
				$shop_urls['������� �����']="http://prodacha.ru/page/shop.html";
				$shop_urls['���������� ��-�.']="http://prodacha.ru/page/shop_spb.html";
			
				//��� �����  - �������,����������� � ����
				$array=array(186,185,181,184);														
				
			
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
					
						
						
						//��� ����������� ����� ��������			
						$sql1="SELECT * FROM phpshop_sort WHERE ".$usl." ";
						$res1=mysql_query($sql1);				

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

					
					
						//��� ����������� ����� ��������			
						$sql2="SELECT * FROM  phpshop_sort_categories WHERE id='".$j."'  ";
						$res2=mysql_query($sql2);				
						$row2=mysql_fetch_assoc($res2);													
			
			
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
						
						if (!empty($src))	
							$vuvod.='<li>
										<div class="product_icon">
											<div class="product_icon_img"><img src="'.$src.'" alt=""></div>
											<div>'.$tovar.'</div>
										</div>
										<div class="product_icon_desc">
											'.$dscr.'
										</div>
									</li>
							';
						
						
							
					}
				
				}

		
				$vuvod.="</ul>";	
				
				echo $vuvod;

?>