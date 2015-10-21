<?

		//гео определение города	
		//include_once "geo_engine.php";
		
	
	//seo - redirect + / на конце +404
	
	$url=$_SERVER['REQUEST_URI'];
	$host=$_SERVER['HTTP_HOST'];

	if($host=="lander-mobilk.ru") //меняем lander-mobilk.ru/ на продач
		{
			$newhost="prodacha.ru";
			
			header("HTTP/1.1 301 Moved Permanently");
			header("Location:http://".$newhost.$url);
			exit;
		}

	if (stripos($url,'/shop/cid_')!==false && strpos('/', substr($url,-1))!==false){
            $cid_id_posstart=stripos($url,'/shop/cid_')+10;
            $cid_id_posend=(strlen($url)-1);
            $cid_id=intval(substr($url,$cid_id_posstart,($cid_id_posend-$cid_id_posstart)));
            if ($cid_id==0) {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location:/shop/404.html");                
            } else {
            //echo $cid_id_posstart.' '.$cid_id_posend.' '.$cid_id;
            header("HTTP/1.1 301 Moved Permanently");
            header("Location:http://".$host.'/shop/CID_'.$cid_id.'.html');                
            }
            exit();
        }	
	
		include_once "redirects.php";	//массив редректов	

		if(isset($massiv[$url])) //редиректы
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


	
		if ( ($url!="/") AND (substr($url,-1)!="/" ) AND  (strpos($url,"?")===false) AND  (strpos($url,".")===false)  ) //слэш на конце, если нет параметров и не файл
		{
				header("HTTP/1.1 301 Moved Permanently");
				header("Location:http://".$host.$url."/");
				exit();
		}
		else // вырезаем / если файл или есть параметры
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