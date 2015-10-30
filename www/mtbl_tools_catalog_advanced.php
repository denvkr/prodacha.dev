<?
if ($_SERVER['REQUEST_URI']=="/shop/CID_16.html")
{
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
				   exit();
				}
																
				if (!@mysql_select_db($dbase,$dbcnx)) 
				{
				   echo("<p> error DB MySql </p>");	
				   exit();
				}
				
								
			/*	mysql_query ("set character_set_client='utf8'");
				mysql_query ("set character_set_results='utf8'");
				mysql_query ("set collation_connection='utf8_general_ci'");
				*/
				$vuvod="<style>.add_catalog {display:none;}</style>	
									
					<p>Мотоблок, оснащенный навесным оборудованием, незаменим на даче, деревенском подворье или в фермерском хозяйстве.</p>
					
					<p>В нашем каталоге представлено различное навесное оборудование для мотоблоков: окучники, плуги, фрезы, косилки, картофелекопалки, прицепы, лопаты-отвалы, снегоуборщики и т. д.</p>
					<a name='adv_cat_begin'></a>
					";
				
				
				$sql0="SELECT * FROM phpshop_categories WHERE parent_to='16' AND id!='93' ORDER BY num ";
				$res0=mysql_query($sql0);
				while($row0=mysql_fetch_assoc($res0))
				{

					$vuvod.='<div class="adv_section"> 
								<a class="h2" href="/shop/CID_'.$row0['id'].'.html" >'.$row0['name'].'</a>
								<div class="adv_items">'
								;					
				
					$sql1="SELECT * FROM phpshop_products WHERE category='".$row0['id']."' AND enabled='1' AND  pic_small!=''  ORDER BY id DESC LIMIT 0,3 ";
					$res1=mysql_query($sql1);
					while($row1=mysql_fetch_assoc($res1))
					{
						$title=htmlspecialchars($row1['name']);
						$vuvod.='<a href="/shop/UID_'.$row1['id'].'.html" alt="'.$title.'" title="'.$title.'" >';
						
						//if (!empty($row1['pic_small']))
							$vuvod.='<img src="http://'.$_SERVER['HTTP_HOST'].$row1['pic_small'].'" alt="'.$title.'" /> ';								
						//else		
							//$vuvod.='<img src="http://'.$_SERVER['HTTP_HOST'].'/UserFiles/Image/item_shop_logo.png" alt="'.$title.'" /> ';

						$vuvod.='</a>';					
					}				
					
					$vuvod.="</div></div>";
				}
				
			
			echo $vuvod;
}

?>