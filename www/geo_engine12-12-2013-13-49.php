<?
	
	session_start();
	
	if  (!isset($_COOKIE['citychoise'])) //если город еще не забит в куках	
	{
		//смотрим по айпи регион
		include "getrealip.php";
		$ip = rmn_getRealIp();	
	
		$url="http://ipgeobase.ru:7020/geo?ip=".$ip."";
		
		$buff=file_get_contents($url);	
		
		$pos1=strpos($buff,"<city>");	
		$pos2=strpos($buff,"</city>");
		
		$len=$pos2-$pos1;
			
		$sincity=substr($buff,$pos1+6,$len-6);	
		
		if( (empty($sincity)) OR (!isset($sincity)) OR ($sincity!="Санкт-Петербург") OR ($sincity!="Москва")) //если не Питер или не определно, то везде Москоу		
			$sincity="m";
		if($sincity == "Санкт-Петербург"){
			$sincity = "sp";
		}
		$_SESSION['sincity']=$sincity;		
		
		setcookie('sincity',$sincity,'0','/');
		setcookie('sincity',$sincity,'0','/shop');
		
		setcookie('citychoise','1','0','/');	
		setcookie('citychoise','1','0','/shop');			
		
	}else{
		if($_COOKIE['sincity'] == "Москва"){
			setcookie('sincity',"m",'0','/');
			setcookie('sincity',"m",'0','/shop');
		}
		if($_COOKIE['sincity'] == "Санкт-Петербург"){
			setcookie('sincity',"sp",'0','/');
			setcookie('sincity',"sp",'0','/shop');
		}
	}
	
	$m = 0;
	if  (!isset($_COOKIE['citychoise'])) {
		$url=$_SERVER['REQUEST_URI'];
		
		if($_COOKIE['sincity']=="sp"){
			$actclass2="active";
			$m = 1;
		}else{
			$actclass2="";
		}
	
		if($_COOKIE['sincity']=="m"){
			$actclass1="active";
			$m = 0;
		}else{
			$actclass1="";
		}
		
		$mas['url'] = $url;
		$mas['actclass2'] = $actclass2;
		$mas['actclass1'] = $actclass1;
		$mas['city'] = $m;
		$mas['ok'] = 1;
	}else{
		$mas['url'] = '';
		$mas['actclass2'] = '';
		$mas['actclass1'] = '';
		$mas['city'] = '';
		$mas['ok'] = 0;
	}
	
	echo json_encode($mas);
?>