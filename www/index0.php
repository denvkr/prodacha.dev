<?

		//��� ����������� ������	
		//include_once "geo_engine.php";
		
	
	//seo - redirect + / �� ����� +404
	
	$url=$_SERVER['REQUEST_URI'];
	$host=$_SERVER['HTTP_HOST'];
	
	
	if($host=="lander-mobilk.ru") //������ lander-mobilk.ru/ �� ������
		{
			$newhost="phpshop.dev";
			
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:http://".$newhost.$url);
			exit;
		}
	
	
		include_once "redirects.php";	//������ ���������	

		if(isset($massiv[$url])) //���������
		{
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:".$massiv[$url]."");
			exit;
		}
		
	
	 if(  (strpos($url,"_")!==false)  AND (!isset($massiv[$url])) AND (strpos($url,".html")!==false)  AND (strpos($url,"ID")!==false) )
	 {
	 	$pos=strpos($url,".html");
		$otrezok=substr($url,$pos-1,1);
		
		if( (!is_numeric($otrezok))  AND (strpos($url,"_ALL")===false)   )
		{	
	
	//	 	header("HTTP/1.0 404 Not Found");
//	       header("Status: 404 Not Found");
			header("HTTP/1.1 301 Moved Permanently");
		   header("Location:/404/");
		
		 }
		   
	 }


	
		if ( ($url!="/") AND (substr($url,-1)!="/" ) AND  (strpos($url,"?")===false) AND  (strpos($url,".")===false)  ) //���� �� �����, ���� ��� ���������� � �� ����
		{
				header("HTTP/1.1 301 Moved Permanently");
				header("Location:http://".$host.$url."/");
				exit();
		}
		else // �������� / ���� ���� ��� ���� ���������
		if (   ( (strpos($url,"?")!==false) AND (substr($url,-1)=="/" )  )   OR ( (strpos($url,".")!==false) AND (substr($url,-1)=="/" )  )        )
		{
			$pos=strrpos($url,"/");
			$newurl=substr($url,0,$pos);
			
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:http://".$host.$newurl);
			exit();
		}
		else 
		{
			include_once "index.php"; 
		}

?>