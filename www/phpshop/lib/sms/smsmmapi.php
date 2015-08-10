<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль SMS через smsmm.ru          |
+-------------------------------------+
*/



function SendSMS($msg,$phone){
global $SysValue;

$source=$SysValue['sms']['name'];
$login=$SysValue['sms']['login'];
$pass=$SysValue['sms']['pass'];

$post='<?xml version="1.0" encoding="windows-1251"?>
<request method="SendSMS">
	<login>'.$login.'</login>
	<pwd>'.$pass.'</pwd>
	<originator num_message="0">'.$source.'</originator>
	<phone_to num_message="0">'.$phone.'</phone_to>
	<message num_message="0">'.$msg.'</message>
</request>';

            if(function_exists('curl_init')) ## проверка, поддерживается ли модуль curl (наиболее быстрое соединение)
		{
            $ch=curl_init();
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=windows-1251'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CRLF, true);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
			curl_setopt($ch,CURLOPT_URL,"http://send.smsmm.ru/");

			
			$res=curl_exec($ch);


			if(curl_error($ch)!='' || $res==false)
			{
                                echo "Error: ".curl_error($ch);
                                curl_close($ch);
				die;
			}

			curl_close($ch);

			# данные посланы, результат в переменной $res
		}
		else # если модуля curl не существует, отправка по обычному fsockopen
		{    

			$fp = fsockopen("send.smsmm.ru", 80, $errno, $errstr, 30);
			if (!$fp) {
				echo "Произошла ошибка. Пожалуйста, попробуйте позже!";
			} else {
			
				$out = "POST /index.php    HTTP/1.1\r\n";
				$out .= "Host: send.smsmm.ru\r\n";
				$out .= "Content-Length: ".strlen($post)."\r\n";
				$out .= "Connection: Close\r\n\r\n";
				$out .= $post."\r\n";

				fwrite($fp, $out);
				$res=null;
				while (!feof($fp)) {
					$res.=fgets($fp, 128);
				}
				fclose($fp);
			}
			#$res=explode("\r\n\r\n",$res);
			#$res=$res[1];

			$response=split("\r\n\r\n",$res);
			$header=$response[0];
			$responsecontent=$response[1];
			if(!(strpos($header,"Transfer-Encoding: chunked")===false)){
				$aux=split("\r\n",$responsecontent);
				for($i=0;$i<count($aux);$i++)
				if($i==0 || ($i%2==0))
					$aux[$i]="";
				$responsecontent=implode("",$aux);
			}//if
			$res=chop($responsecontent);

			# запрос послан, результат в $res
		}
		
		/*
		$res=explode("\r\n\r\n",$res); # первая строка - статус, третья - сообщение
		if($res[0]=='error') # если первая строка ответа содержит error (ошибки в $res[1])
		{
                        $errors=explode("\r\n",$res[1]);
                        unset($errors[count($errors)-1]);
			echo "<ul>\n";
			foreach($errors as $err)
			echo "<li><font color=red>Ошибка: </font> ".$err."<br>\n";
			echo "</ul>\n";
		}
		elseif($res[0]=='ok') ## отправка успешная
		{
			echo "Ваше сообщение успешно отправлено";
		}
		else #отправка неуспешная, проблемы с соединением
		{
			echo "Произошла ошибка. Пожалуйста, повторите позже.";
		}
		*/
}
?>