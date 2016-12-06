<?php
	$host="localhost";          
	$user_db="u301639_second";           
	$pass_db="peREneSti-AB6E";       
	$dbase="u301639";
				
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
				
							
				
				//инфо из бд
				$sql0="SELECT id FROM phpshop_products WHERE enabled='1' order by id LIMIT 3001,3500";
				$res0=mysql_query($sql0);

				while ($row0=mysql_fetch_assoc($res0)){
									//echo 'http://'.$_SERVER["SERVER_NAME"].'/shop/UID_'.$row0['id'].'.html';
                                    //заглядываем в url
                                    $site_info = file_get_contents('http://'.$_SERVER["SERVER_NAME"].'/shop/UID_'.$row0['id'].'.html');
                                    if (stristr(WinToUtf8($site_info),'<div class="sametovarbox"><table cellspacing="0" cellpadding="0" border="0"><tbody><tr><td><div id="same_tovar')!=false)
                                            $resultarr[]=$row0['id'];
				}
				echo implode(',',$resultarr);
				
function Utf8ToWin($s)
        {
			$out='';
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

