<?

	
	if (isset($_POST['reg']))//обрабатываем выбор региона юзером
	{
		//session_start();
		
	
			setcookie('citychoise','1','0','/');	
			setcookie('citychoise','1','0','/shop');			
			//$_SESSION['citychoise']=1;
	
			if ($_POST['reg']=="spb")
			{
				

				setcookie('sincity',"sp",'0','/');
				setcookie('sincity',"sp",'0','/shop');
				
			}	

			if ($_POST['reg']=="chb")
			{
				

				setcookie('sincity',"chb",'0','/');
				setcookie('sincity',"chb",'0','/shop');
				
			}	
			
			if ($_POST['reg']=="msc")
			{

				setcookie('sincity',"m",'0','/');			
				setcookie('sincity',"m",'0','/shop');	
				
			}	
			
			if ($_POST['reg']=="other")
			{

				setcookie('sincity',"other",'0','/');			
				setcookie('sincity',"other",'0','/shop');	
				
			}		
			//редиректим туда, откуда он пришел
			///header("HTTP/1.1 301 Moved Permanently");
			///header("Location:http://".$_SERVER['HTTP_HOST'].$_GET['url']);
			///exit;	
			
			/*
			echo " <pre>";
								print_r($_SESSION);
								echo "<pre>";
			*/
	}
	
	
?>