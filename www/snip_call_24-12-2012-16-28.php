<?
if (isset($_POST['email']))
{

				Error_Reporting(E_ALL & ~E_NOTICE);

				$m=date("m");if($m=='01') $m1="������";if($m=='02') $m1="�������";if($m=='03') $m1="�����";if($m=='04') $m1="������";if($m=='05') $m1="���";if($m=='06') $m1="����";if($m=='07') $m1="����";if($m=='08') $m1="�������";if($m=='09') $m1="��������";if($m=='10') $m1="�������";if($m=='11') $m1="������";if($m=='12') $m1="�������";
				
				$date=date("d")." ".$m1." ".date("Y ")."���� � ".date(" H:i:s");
				
				 $taim=date(" H:i:s, d m Y");
				 $ip=getenv("REMOTE_ADDR");
				 
				// print_r($_POST);
				 $msg="				 																		 
							������ ������������ (ip ".$ip."), ����������� ������ ".$date." :	
							
							";						   						   						   
				
				//$_POST['input1']=urldecode($_POST['input1']);
				
				//$input1=iconv("utf-8","windows-1251",$_POST['input1']);//������������ 
										 
				$msg.="	
				
					�����: ".$_POST['email']."	
					����� ��������:	".$_POST['textArea']."			
					";
					
					 $subject="������ �  ����� ".$_SERVER['HTTP_HOST']." ";		
					 
				
							$mail_to="zakaz@prodacha.ru";
				//$mail_to="nah_nah@mail.ru, test@astramg.ru";
				
				$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
				$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];			

				if ($_POST['cv']=="nohspamcode") //��������+�������� ��
				{
					if  (mail($mail_to,$subject,$msg,$mailheaders)) 
					{					
							header("HTTP/1.1 301 Moved Permanently");
						header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."");	
						exit();
					}
				}	
				
}
