<?
	
	session_start();
	
	if  (!isset($_COOKIE['citychoise'])) //���� ����� ��� �� ����� � �����	
	{
		//������� �� ���� ������
		include "getrealip.php";
		$ip = rmn_getRealIp();	
	
		$url="http://ipgeobase.ru:7020/geo?ip=".$ip."";
		
		//$buff=file_get_contents($url);	
		$buff="<city>������</city>";
		$pos1=strpos($buff,"<city>");	
		$pos2=strpos($buff,"</city>");
		
		$len=$pos2-$pos1;
			
		$sincity=substr($buff,$pos1+6,$len-6);	
		
		if( (empty($sincity)) OR (!isset($sincity)) OR ($sincity!="�����-���������") OR ($sincity!="������") OR ($sincity!="���������") OR ($sincity!="�����")) //���� �� ����� ��� �� ���������, �� ����� ������		
			$sincity="other";
		if($sincity == "�����-���������"){
			$sincity = "sp";
		}
		if($sincity == "���������"){
			$sincity = "chb";
		}
		if($sincity == "�����"){
			$sincity = "kur";
		}		
		$_SESSION['sincity']=$sincity;		
		
		setcookie('sincity',$sincity,'0','/');
		setcookie('sincity',$sincity,'0','/shop');
		
		setcookie('citychoise','1','0','/');	
		setcookie('citychoise','1','0','/shop');			
		
	}else{
		if($_COOKIE['sincity'] == "������"){
			setcookie('sincity',"m",'0','/');
			setcookie('sincity',"m",'0','/shop');
		}
		if($_COOKIE['sincity'] == "�����-���������"){
			setcookie('sincity',"sp",'0','/');
			setcookie('sincity',"sp",'0','/shop');
		}
		if($_COOKIE['sincity'] == "���������"){
			setcookie('sincity',"chb",'0','/');
			setcookie('sincity',"chb",'0','/shop');
		}
		if($_COOKIE['sincity'] == "�����"){
			setcookie('sincity',"kur",'0','/');
			setcookie('sincity',"kur",'0','/shop');
		}		
		if($_COOKIE['sincity'] == "������ ������"){
			setcookie('sincity',"other",'0','/');
			setcookie('sincity',"other",'0','/shop');
		}
	}
	
	$m = 0;
	if  (!isset($_COOKIE['citychoise'])) {
		$url=$_SERVER['REQUEST_URI'];
		
		if($_COOKIE['sincity']=="m"){
			$actclass1="active";
			$m = 0;
		}else{
			$actclass1="";
		}

		if($_COOKIE['sincity']=="sp"){
			$actclass2="active";
			$m = 1;
		}else{
			$actclass2="";
		}

		if($_COOKIE['sincity']=="chb"){
			$actclass3="active";
			$m = 2;
		}else{
			$actclass3="";
		}		

		if($_COOKIE['sincity']=="other"){
			$actclass4="active";
			$m = 3;
		}else{
			$actclass4="";
		}		
		if($_COOKIE['sincity']=="kur"){
			$actclass5="active";
			$m = 4;
		}else{
			$actclass5="";
		}		

		$mas['url'] = $url;
		$mas['actclass5'] = $actclass5;
		$mas['actclass4'] = $actclass4;		
		$mas['actclass3'] = $actclass3;
		$mas['actclass2'] = $actclass2;
		$mas['actclass1'] = $actclass1;
		$mas['city'] = $m;
		$mas['ok'] = 1;
	}else{
		$mas['url'] = '';
		$mas['actclass5'] = '';
		$mas['actclass4'] = '';				
		$mas['actclass3'] = '';		
		$mas['actclass2'] = '';
		$mas['actclass1'] = '';
		$mas['city'] = '';
		$mas['ok'] = 0;
	}
	
	echo json_encode($mas);
?>