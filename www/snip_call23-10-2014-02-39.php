<?
//echo 'yo_name '.$_POST['yo_name'].'yo_phone '.$_POST['yo_phone'].'cv3 '.$_POST['cv3'];
if (isset($_POST['email']))
{

				Error_Reporting(E_ALL & ~E_NOTICE);

				$m=date("m");if($m=='01') $m1="января";if($m=='02') $m1="февраля";if($m=='03') $m1="марта";if($m=='04') $m1="апреля";if($m=='05') $m1="мая";if($m=='06') $m1="июня";if($m=='07') $m1="июля";if($m=='08') $m1="августа";if($m=='09') $m1="сентября";if($m=='10') $m1="октября";if($m=='11') $m1="ноября";if($m=='12') $m1="декабря";
				
				$date=date("d")." ".$m1." ".date("Y ")."года в ".date(" H:i:s");
				
				 $taim=date(" H:i:s, d m Y");
				 $ip=getenv("REMOTE_ADDR");
				 
				// print_r($_POST);
				 $msg="				 																		 
							Данные пользователя (ip ".$ip."), оставившего заявку ".$date." :	
							
							";						   						   						   
				
				//$_POST['input1']=urldecode($_POST['input1']);
				
				//$input1=iconv("utf-8","windows-1251",$_POST['input1']);//конуертируем 
										 
				$msg.="	
				
					Почта: ".$_POST['email']."	
					Текст послания:	".$_POST['textArea']."			
					";
					
					 $subject="Вопрос с  сайта ".$_SERVER['HTTP_HOST']." ";		
					 
				
							$mail_to="zakaz@prodacha.ru";
				//$mail_to="nah_nah@mail.ru, test@astramg.ru";
				
				$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
				$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];			

				if ($_POST['cv']=="nohspamcode") //проверко+антиспам ок
				{
					if  (mail($mail_to,$subject,$msg,$mailheaders)) 
					{					
							header("HTTP/1.1 301 Moved Permanently");
						header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."");	
						exit();
					}
				}	
				
}

if (isset($_POST['yo_name']) && isset($_POST['yo_phone']))
{

	Error_Reporting(E_ALL & ~E_NOTICE);

	$m=date("m");if($m=='01') $m1="января";if($m=='02') $m1="февраля";if($m=='03') $m1="марта";if($m=='04') $m1="апреля";if($m=='05') $m1="мая";if($m=='06') $m1="июня";if($m=='07') $m1="июля";if($m=='08') $m1="августа";if($m=='09') $m1="сентября";if($m=='10') $m1="октября";if($m=='11') $m1="ноября";if($m=='12') $m1="декабря";

	$date=date("d")." ".$m1." ".date("Y ")."года в ".date(" H:i:s");

	$taim=date(" H:i:s, d m Y");
	$ip=getenv("REMOTE_ADDR");
		
	// print_r($_POST);
	$msg="
	Данные пользователя (ip ".$ip."), оставившего заявку ".$date." :
		
	";

	//$_POST['input1']=urldecode($_POST['input1']);

	//$input1=iconv("utf-8","windows-1251",$_POST['input1']);//конуертируем
		
	$msg.="
	Имя пользователя: ".$_POST['yo_name']."
	Заказ:            ".$_POST['tovar_info_input']."
	Пришел с          ".$_POST['referer_info']."
	Телефон:          ".$_POST['yo_phone']."
	Регион:			  ".$_POST['region'];
		
	$subject="Пользователь:".$_POST['yo_name']." Телефон:".$_POST['yo_phone']." Заказал: ".$_POST['tovar_info_input']." ";

	echo $msg,$subject;
	
	$mail_to="zakaz@prodacha.ru";
	//$mail_to="nah_nah@mail.ru, test@astramg.ru";

	$mailheaders="Content-Type: text/plain; charset=\"windows-1251\"\n ";
	$mailheaders.= "From:no-reply@".$_SERVER['HTTP_HOST'];

	if ($_POST['cv3']=="nohspamcode") //проверко+антиспам ок
	{

		if  (mail($mail_to,$subject,$msg,$mailheaders))
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:http://".$_SERVER['HTTP_HOST']."/redirect.php?url=".$_SERVER['HTTP_REFERER']."&fast_order=true");
			exit();
		}

	}

}
