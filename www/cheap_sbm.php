<?
if (isset($_POST['product']))
{

				Error_Reporting(E_ALL & ~E_NOTICE);

				
				//ТТХ юзера
				
				$m=date("m");if($m=='01') $m1="января";if($m=='02') $m1="февраля";if($m=='03') $m1="марта";if($m=='04') $m1="апреля";if($m=='05') $m1="мая";if($m=='06') $m1="июня";if($m=='07') $m1="июля";if($m=='08') $m1="августа";if($m=='09') $m1="сентября";if($m=='10') $m1="октября";if($m=='11') $m1="ноября";if($m=='12') $m1="декабря";
				
				$date=date("d")." ".$m1." ".date("Y ")."года в ".date(" H:i:s");
				
				 $taim=date(" H:i:s, d m Y");
				 $ip=getenv("REMOTE_ADDR");
				 
				 //данные о товаре
				 
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
				
					$sql1="SELECT * FROM phpshop_products WHERE id='".$_POST['product']."' ";
					$res1=mysql_query($sql1);
					$row1=mysql_fetch_assoc($res1);
					
					$title=htmlspecialchars($row1['name']);
				 
				 
				//письмо
				
				 $msg="	
				 	
					Заявка о товаре: ".$title."	 			   						   
				
					Данные пользователя (ip ".$ip."), оставившего заявку ".$date." :
									
					Имя: ".$_POST['uname']."
					e-mail: ".$_POST['uemail']."
					Телефон: ".$_POST['uphone']."
					Название магазина, где этот товар дешевле: ".$_POST['sname']."
					Ссылка на товар: ".$_POST['surl']."
					Стоимость:	".$_POST['sprice']."						";
					
					 $subject="Сообщение о более дешевом товаре с сайта ".$_SERVER['HTTP_HOST']." ";		
					 
				
							$mail_to="zakaz@prodacha.ru";
			//	$mail_to="nah_nah@mail.ru, test@astramg.ru";
				
				$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
				$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];		
				
				

				if ($_POST['cv2']=="nohspamcode") //проверко+антиспам ок
				{
					if  (mail($mail_to,$subject,$msg,$mailheaders)) 
					{					
							header("HTTP/1.1 301 Moved Permanently");
						header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?cheap&url=".$_SERVER['HTTP_REFERER']."");	
						exit();
						//echo  $msg;
						
					}
				}	
				
}
