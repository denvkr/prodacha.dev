<?php
	
	if($_GET['ID']){
		$Ids   = explode("-",$_GET['ID']);
		$id    = (int)trim($Ids['0']);
		$datas = $Ids['1'];
		
		function OplataMetod($tip){
			if($tip==1) return "Счет в банк";
			if($tip==2) return "Квитанция";
			if($tip==3) return "Наличная";
		}

		
		
		function dataV($nowtime){
			$Months = array("01"=>"января","02"=>"февраля","03"=>"марта", 
			 "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
			 "08"=>"августа","09"=>"сентября",  "10"=>"октября",
			 "11"=>"ноября","12"=>"декабря");
			$curDateM = date("m",$nowtime); 
			$t=date("d",$nowtime)."-".$curDateM."-".date("Y",$nowtime).""; 
			return $t;
		}

		function ReturnSumma($sum,$disc){
			$sum=$sum-($sum*$disc/100);
			return $sum;
		}

		
		
		$SysValue=parse_ini_file("./../../phpshop/inc/config.ini",1);
  		while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
		$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

			
		$res = @mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db']);
		
		mysql_select_db($SysValue['connect']['dbase']); 
	
		$NewArray = mysql_fetch_array(mysql_query("SELECT * FROM ".$SysValue['base']['table_name1']." WHERE uid=".$id." AND datas='$datas'"));
		if(is_array($NewArray)){
			$OrderInfoArr = unserialize($NewArray['orders']);
				
			$CartInfoArr  = $OrderInfoArr['Cart'];
			$UserInfo	  = $OrderInfoArr['Person'];
				
			$dom = "<?xml version=\"1.0\" encoding=\"windows-1251\" standalone=\"no\" ?>\n";
	    	$dom .= "<main>\n";
	    	
	      	if(is_array($UserInfo) && sizeof($UserInfo)>0){
	    	   	while(list($key,$value) = each($UserInfo)){
		    			if($key=='order_metod'){
		    				$dom .= "<$key>".OplataMetod($value)."</$key>\n";
		    			}else{	
		    				$dom .= "<$key>".$value."</$key>\n";
		    			}
		    	}
		    	$dom .= "<sum>".ReturnSumma($CartInfoArr['sum'],$UserInfo['discount'])."</sum>\n";
	    	}else{
	    		$dom .= "<ouid>".$id."</ouid>\n
	    				<data/>\n
	    				<time/>\n
	    				<mail>".$NewArray['user_email']."</mail>\n
	    				<name_person>".$NewArray['user_name']."</name_person>\n
	    				<org_name>".$NewArray['org_name']."</org_name>\n
	    				<org_inn>".$NewArray['org_inn']."</org_inn>\n
	    				<tel_name>".$NewArray['tell_name']."</tel_name>\n    				
	    				<adr_name>".$NewArray['addr_name']."</adr_name>\n
	    				<dostavka_metod>".$NewArray['delivery_method']."</dostavka_metod>\n
	    				<order_pac/>
	    				<discount>".$NewArray['discount']."</discount>\n
	    				<user_id>".$NewArray['user']."</user_id>\n
	    				<dos_ot>".$NewArray['delivery_from']."</dos_ot>\n
	    				<dos_do>".$NewArray['delivery_to']."</dos_do>\n
	    				<order_metod>".OplataMetod($NewArray['order_method'])."</order_metod>\n	    					    				    				
	    				";
	    		$dom .= "<sum>".ReturnSumma($NewArray['sum'],$UserInfo['discount'])."</sum>\n";
	    	}
	    	
			
	    	
	    	$dom .= "<datas>".dataV($NewArray['datas'])."</datas>\n";
	    	$dom .= "<carts>\n";
	    	if(is_array($CartInfoArr) && sizeof($CartInfoArr)>0){
		    	foreach ($CartInfoArr['cart'] as $CartArra){
		    		$dom .= "<prod id=\"".$CartArra['id']."\">\n";	
		    		while(list($key,$value) = each($CartArra))
						$dom .= "<$key>".$value."</$key>\n";
		    		$dom .= "</prod>\n	";
		    	}
	    	}else{
	    		foreach ($OrderInfoArr['cart'] as $CartArra){
		    		$dom .= "<prod id=\"".$CartArra['id']."\">\n";	
		    		while(list($key,$value) = each($CartArra))
						$dom .= "<$key>".$value."</$key>\n";
		    		$dom .= "</prod>\n	";
		    	}
	    	}
	    	$dom .= "</carts>\n";   	
			//$dom = iconv("windows-1251","UTF-8", $dom);
	    	$dom .= "</main>\n";
	    	
			header("Content-type: text/xml");
			header("Content-Length: ".strlen($dom));
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Expires: " . gmdate("D, d M Y H:i:s",mktime(0,0,0,1,1,1980) )."GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
	
			echo $dom;
		}		
	}elseif($_GET['datefrom'] && $_GET['dateto']){
		
		
		$datefrom = explode(".",$_GET['datefrom']);
		$dateto   = explode(".",$_GET['dateto']);
		
		
		$datefrom = mktime(12,0,0,(int)$datefrom['1'],(int)$datefrom['0'],"20".$datefrom['2'])-86400;
		$dateto   = mktime(12,0,0,(int)$dateto['1'],(int)$dateto['0'],"20".$dateto['2'])+86400;
		
		
		$SysValue=parse_ini_file("./../phpshop/inc/config.ini",1);
  		while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
		$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

			
		$res = @mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db']);
		
		mysql_select_db($SysValue['connect']['dbase']); 
		$NewQuery = mysql_query("SELECT * FROM ".$SysValue['base']['table_name1']." WHERE datas<'$dateto' AND datas>'$datefrom' ORDER BY datas DESC") or die(mysql_error());
		
		if(mysql_num_rows($NewQuery)){
			$dom = "<?xml version=\"1.0\" encoding=\"windows-1251\" standalone=\"no\" ?>\n";
	    	$dom .= "<main>\n";
	
			while($Rows = mysql_fetch_assoc($NewQuery)){
				$OrderInfo = unserialize($Rows['orders']);
				if(is_array($OrderInfo['Person']) && sizeof($OrderInfo['Person'])>0){
					$dom .= "<order id=\"".$Rows['uid']."\" date=\"".$Rows['datas']."\">".$Rows['uid']." - ".$OrderInfo['Person']['name_person']."</order>\n";	
				}else{
					$dom .= "<order id=\"".$Rows['uid']."\" date=\"".$Rows['datas']."\">".$Rows['uid']." - ".$Rows['user_name']."</order>\n";	
				}
			}
			$dom .= "</main>\n";
		    	
			header("Content-type: text/xml");
			header("Content-Length: ".strlen($dom));
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
			header("Expires: " . gmdate("D, d M Y H:i:s",mktime(0,0,0,1,1,1980) )."GMT");
			header("Cache-Control: no-cache, must-revalidate");
			header("Pragma: no-cache");
			echo $dom;
		}
	}		
	
	

?>