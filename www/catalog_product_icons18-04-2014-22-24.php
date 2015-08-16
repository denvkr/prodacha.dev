<?

/*
				$host="localhost";          
				$user_db="u301639_test";           
				$pass_db="-o97iCiAlLop.";       
				$dbase="u301639_test"; 
*/
				$host=$GLOBALS['SysValue']['connect']['host'];          
				$user_db=$GLOBALS['SysValue']['connect']['user_db'];           
				$pass_db=$GLOBALS['SysValue']['connect']['pass_db'];       
				$dbase=$GLOBALS['SysValue']['connect']['dbase'];
				
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
				
							
				//из строки берем айди товара 
				$url=$_SERVER['REQUEST_URI'];				
				
				$s1=strpos($url,"СID_")+10;	//вариант каталога											
				
				$s2=strpos($url,".html");				
				$len=$s2-$s1;			
								
				$id=substr($url,$s1,$len);				
				
				$usl0=" category='".$id."' ";
				
				
				//+ смотрим для варианта, когда у него есть КАК БЭ дети - на самом деле это братья. Обычно это раздел ВСЕ товары из разных категорий 

				$all=array('5','13','37','17',$id);
				
				if (in_array($id,$all))
				{
					$sql02="SELECT * FROM phpshop_categories WHERE id='".$id."' ";
					$res02=mysql_query($sql02);
				
					if (mysql_num_rows($res02))
					{
						$row02=mysql_fetch_assoc($res02);
					
						$sql2="SELECT * FROM phpshop_categories WHERE parent_to='".$row02['parent_to']."' ";
						$res2=mysql_query($sql2);				
						while($row2=mysql_fetch_assoc($res2))
							{
								$usl0.=" OR category='".$row2['id']."' ";
							}
					}				
				}
				

				$sql00="SELECT * FROM phpshop_products WHERE ".$usl0." ";				
				$res00=mysql_query($sql00);
				while($row00=mysql_fetch_assoc($res00))
				{
					$uid=$row00['id'];
					
					$nameuid="icon_".$row00['id'];
					
					$_SESSION[$nameuid]='<ul class="product_icons">';			
					
					//инфо из бд
					$sql0="SELECT * FROM phpshop_products WHERE id='".$uid."' ";
					$res0=mysql_query($sql0);
					
					$row0=mysql_fetch_assoc($res0);
					
					$id_tovara=$row0['id'];
					
					//у товара в его массиве хар-к находим тот показатель,  что отвечает за нужные нам харки
					$vendor=$row0['vendor'];						
					
					//echo "<!-- $vendor -->";
	
					//названия магазов для ссылок  - ибо со вбитием ссылок через админку траблы
					$shop_urls['м. Динамо']="http://prodacha.ru/page/store.html";
					$shop_urls['Минское шоссе']="http://prodacha.ru/page/shop.html";
					$shop_urls['Лахтинский пр-т.']="http://prodacha.ru/page/shop_spb.html";
				
					//доп харки  - наличие,предпродажа и проч
					$array=array(186,185,181,184);														
					
				
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
						
							
							
							//идём вытаскиваем саму картинку			
							$sql1="SELECT * FROM phpshop_sort WHERE ".$usl." ";
							$res1=mysql_query($sql1);				
	
							if ($j==186)//случай неск магазов
							{
								while($row1=mysql_fetch_assoc($res1))
								{		
									$id=$row1['id'];														
									$characters[$uid][$id]=$row1['name'];																						
								}														
							}
							else
							{							
								$row1=mysql_fetch_assoc($res1);									
								$src=$row1['name'];
							}						
	
						
						
							//идём вытаскиваем само описание			
							$sql2="SELECT * FROM  phpshop_sort_categories WHERE id='".$j."'  ";
							$res2=mysql_query($sql2);				
							$row2=mysql_fetch_assoc($res2);													
								
							$f=mysql_num_rows($res2);
				
							if ($j==186) //в случае с наличием 
							{
								foreach($characters as $character)							
								{	
									foreach($character as $key=>$val)	
									{												
										if ($key!=1200)
										{
											$substite=$shop_urls[$val];
											$shops.='<p><a href="'.$substite.'" target="_blank" >'.$val.'</a></p>';	
										}	
									}	
								}
								
								$src=$characters[$uid][$id];								
							
								
								$tovar=$row2['description']; //-  меняем местами описание и название
								// $dscr='<div class="header">'.$row2['name'].'</div>'.$shops;		
								$dscr = '<p>Самовывоз в день заказа</p>';
								
								
							}
							else //остальные картиношные харки
							{
								$tovar=$row2['name'];
								$dscr='<p>'.$row2['description'].'</p>';												
							}	
							
							if (!empty($src))
								$_SESSION[$nameuid].='<li>
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
	
			
					$_SESSION[$nameuid].="</ul>";	
		
			}
				



?>