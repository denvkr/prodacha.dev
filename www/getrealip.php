<?

/***********************
*
*	Определить IP
*
***********************/

function rmn_getRealIp($mode = 'all') 
{
	// если сервер сам определился
	if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
	
	
	//-----------------------------------------
	// IP 
	//-----------------------------------------
	if ($mode == 'all')
	{
		foreach( array_reverse( explode( ',', getenv('HTTP_X_FORWARDED_FOR') ) ) as $x_f )
		{
			$x_f = trim($x_f);
			
			if ( preg_match( '/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/', $x_f ) )
			{
				$addrs[] = $x_f;
			}
		}
		
		$addrs[] = getenv('HTTP_CLIENT_IP');
		$addrs[] = getenv('HTTP_X_CLUSTER_CLIENT_IP');
		$addrs[] = getenv('HTTP_PROXY_USER');
	}
	
	$addrs[] = getenv('REMOTE_ADDR');
	
	
	//-----------------------------------------
	// Есть чего?
	//-----------------------------------------

	foreach ( $addrs as $ip )
	{
		if ( $ip )
		{
			preg_match( "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$/", $ip, $match );
			$ip_address = $match[1].'.'.$match[2].'.'.$match[3].'.'.$match[4];
			if ( $ip_address AND $ip_address != '...' )
			{
				break;
			}
		}
	}
	
	return $ip_address;
}


//echo rmn_getRealIp();
?>