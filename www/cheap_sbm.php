<?
if (isset($_POST['product']))
{

				Error_Reporting(E_ALL & ~E_NOTICE);

				
				//��� �����
				
				$m=date("m");if($m=='01') $m1="������";if($m=='02') $m1="�������";if($m=='03') $m1="�����";if($m=='04') $m1="������";if($m=='05') $m1="���";if($m=='06') $m1="����";if($m=='07') $m1="����";if($m=='08') $m1="�������";if($m=='09') $m1="��������";if($m=='10') $m1="�������";if($m=='11') $m1="������";if($m=='12') $m1="�������";
				
				$date=date("d")." ".$m1." ".date("Y ")."���� � ".date(" H:i:s");
				
				 $taim=date(" H:i:s, d m Y");
				 $ip=getenv("REMOTE_ADDR");
				 
				 //������ � ������
				 
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
				 
				 
				//������
				
				 $msg="	
				 	
					������ � ������: ".$title."	 			   						   
				
					������ ������������ (ip ".$ip."), ����������� ������ ".$date." :
									
					���: ".$_POST['uname']."
					e-mail: ".$_POST['uemail']."
					�������: ".$_POST['uphone']."
					�������� ��������, ��� ���� ����� �������: ".$_POST['sname']."
					������ �� �����: ".$_POST['surl']."
					���������:	".$_POST['sprice']."						";
					
					 $subject="��������� � ����� ������� ������ � ����� ".$_SERVER['HTTP_HOST']." ";		
					 
				
							$mail_to="zakaz@prodacha.ru";
			//	$mail_to="nah_nah@mail.ru, test@astramg.ru";
				
				$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
				$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];		
				
				

				if ($_POST['cv2']=="nohspamcode") //��������+�������� ��
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
