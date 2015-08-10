<?

//unset ($_SESSION['usr_info']);
//echo $_SERVER['HTTP_REFERER'];

$pageURL = 'http';
if ($_SERVER["HTTPS"] == "on") {
	$pageURL .= "s";
}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
	$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
	$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}


if (preg_match('/^.*utm\_source\=Yandex\-Direct.*$/i',$pageURL)==0) {
	$pageURL='';
}

if ( !in_array($_SERVER['HTTP_REFERER'],$_SESSION['usr_info']) ) {
	$_SESSION['usr_info'][]=$_SERVER['HTTP_REFERER'];
}

if ( !empty($pageURL) && empty($_SESSION['usr_ydir_info']) ) {
	$_SESSION['usr_ydir_info']=$pageURL;	
}

$sourses['01']="yandex.ru/clck/jsredir?from=yandex.ru";
$sourses['02']="google.ru/";

$sourses['03']="go.mail.ru";
$sourses['03_1']="e.mail.ru";

$sourses['04']="bs.yandex.ru/count/";//bs.yandex.ru/count/
$sourses['05']="google.ru/aclk?sa";
$sourses['06']="market.yandex.ru";
$sourses['07']="mobilk.ru";

$sourses['08']="mastercity.ru";
$sourses['08_1']="master-forum.ru";

$sourses['09']="forumhouse.ru";

$sourses['10']="rambler.ru";
$sourses['10_1']="nova.rambler.ru";

$sourses['11']="ask.com";

$sourses['12']="prodacha.pul.ru";
$sourses['12_1']="msk.pulscen.ru";
$sourses['12_2']="prodacha.pulscen.ru";
$sourses['12_3']="pulscen.ru";
$sourses['12_4']="spb.pulscen.ru";

$sourses['13']="bing.com";
$sourses['14']="maps.yandex.ru";
$sourses['15']="news.yandex.ru";
$sourses['16']="images.yandex.ru";

$sourses['17']="tiu.ru";
$sourses['17_1']="prodacha.tiu.ru";
$sourses['17_2']="moskva.tiu.ru";

$sourses['18']="avito.ru";
$sourses['19']="vk.com";
$sourses['20']="irr.ru";
$sourses['21']="nigma.ru";
$sourses['22']="price.ru";
$sourses['23']="youtube.com";
$sourses['24']="pogoda.yandex.ru";
$sourses['25']="findsmarter.ru";
$sourses['26']="motobloki46.ru";
$sourses['27']="moto-market.ru";
$sourses['28']="news.mail.ru";
$sourses['29']="webalta.ru";
$sourses['30']="link.2gis.ru";
$sourses['31']="nearyoucom.ru";
$sourses['32']="m.market.yandex.ru";
$sourses['33']="top-page.ru";
$sourses['34']="pchelovod.info";
$sourses['35']="forum.uazbuka.ru";
$sourses['36']="odnoklassniki.ru";
$sourses['37']="m.news.yandex.ru";
$sourses['38']="gismeteo.ru";
$sourses['39']="images.rambler.ru";
$sourses['40']="news.rambler.ru";
$sourses['41']="rg.ru";
$sourses['42']="voprosotvet.net";
$sourses['43']="sait-pro-dachu.ru";
$sourses['44']="dacha.wcb.ru";

$first=99;

if (empty($_SESSION['usr_info'][0]))
	$first="00";
else
	foreach ($sourses as $key=>$value)
	{
		if (strpos($_SESSION['usr_info'][0],$value)!==false)	
		{
			if (strpos($key,"_")!==false)
			{
				$n=strpos($key,"_");
				
				$first=substr($key,0,$n);
			}
			else
				$first=$key;
		}
		
	}


$second=count($_SESSION['usr_info'])-1;

if ($second<10) $second="0".$second;
//print_r($_SESSION['usr_info']);


//if (preg_match('/^.*utm\_source\=Yandex\-Direct.*$/i',$pageURL)==1) {
if (!empty($_SESSION['usr_ydir_info'])) {
	$first='04';	
}

//}

echo $first."-".$second;

?>