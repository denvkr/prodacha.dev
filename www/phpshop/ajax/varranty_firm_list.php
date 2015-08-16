<?php
header('Content-type: text/html; charset=windows-1251');
$_classPath="../";
require_once $_classPath."lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");
$SysValue = parse_ini_file($_classPath.'inc/config.ini', 1);

$host=$SysValue['connect']['host'];
$user_db=$SysValue['connect']['user_db'];
$pass_db=$SysValue['connect']['pass_db'];
$dbase=$SysValue['connect']['dbase'];

$dbcnx=mysql_connect($host,$user_db,$pass_db);


if (!$dbcnx)
{
	echo("<p> error MySql </p>");
	exit;
}

if (!@mysql_select_db($dbase,$dbcnx))
{
	echo("<p> error DB MySql </p>");
	exit;
}

//if ( !empty($_POST['field_vendor_brand']) && !empty($_POST['field_vendor_city']) ) {
	//$sql='set names utf8';
	mysql_query($sql);
	$sql="SELECT service_org_name,phone,address FROM ".$SysValue['base']['service_and_varranty']." WHERE brand='".$_GET['select_vendor_brand']."' and city='".$_GET['select_vendor_city']."'";
	//$sql="SELECT service_org_name,phone,address FROM ".$user_db.".".$SysValue['base']['service_and_varranty'];
	echo $sql;
	$res=mysql_query($sql) or trigger_error(mysql_error()." in ".$sql);

	$cnt=0;
	$mas='<table id="select_varranty_firm" name="select_varranty_firm" cellpadding="5" cellspacing="10" col="3">';//[$cnt]
	$mas.='<thead><tr><th style="text-align:left">Наименование</th><th style="text-align:left">Телефон</th><th style="text-align:left">Адрес</th></tr></thead><tbody>';
	while ( $row=mysql_fetch_assoc($res) ) {
			$mas.='<tr>';//[($cnt+1)]
			$mas.='<td>'.$row['service_org_name'].'</td><td>'.$row['phone'].'</td><td>'.$row['address'].'</td>';//[($cnt+2)]
			$mas.='</tr>';//[($cnt+3)]
			//$cnt+=3;
	}

		$mas.='</tbody></table>';//[($cnt+1)]

//}
//echo json_encode($mas);
		$_RESULT = array(
				'result' => $mas
		);
		
function WinToUtf8($data)
{
	if (is_array($data))
	{
		$d = array();
		foreach ($data as $k => &$v) $d[WinToUtf8($k)] = WinToUtf8($v);
		return $d;
	}
	if (is_string($data))
	{
		if (function_exists('iconv')) return iconv('cp1251', 'utf-8//IGNORE//TRANSLIT', $data);
		if (! function_exists('cp1259_to_utf8')) include_once 'cp1259_to_utf8.php';
		return WinToUtf8($data);
	}
	if (is_scalar($data) or is_null($data)) return $data;
	#throw warning, if the $data is resource or object:
	trigger_error('An array, scalar or null type expected, ' . gettype($data) . ' given!', E_USER_WARNING);
	return $data;
}
function Utf8ToWin($s)
        {
            $byte2=false;
            for ($c=0;$c<strlen($s);$c++)
            {
               $i=ord($s[$c]);
               if ($i<=127) $out.=$s[$c];
                   if ($byte2){
                       $new_c2=($c1&3)*64+($i&63);
                       $new_c1=($c1>>2)&5;
                       $new_i=$new_c1*256+$new_c2;
                   if ($new_i==1025){
                       $out_i=168;
                   } else {
                       if ($new_i==1105){
                           $out_i=184;
                       } else {
                           $out_i=$new_i-848;
                       }
                   }
                   $out.=chr($out_i);
                   $byte2=false;
                   }
               if (($i>>5)==6) {
                   $c1=$i;
                   $byte2=true;
               }
            }
            return $out;
        }
?>