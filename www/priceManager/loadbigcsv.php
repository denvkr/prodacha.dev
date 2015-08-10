<?

// Парсируем установочный файл
$SysValue=parse_ini_file("../phpshop/inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
@mysql_query("SET NAMES cp1251");

$row = mysql_fetch_array(mysql_query("SELECT * FROM ".$SysValue['base']['table_name40']." WHERE id='1'"));
$file = $row['file'];
$seek = $row['seek'];
$num_new = $row['num_new'];
$num_upd = $row['num_upd'];
$aoption = unserialize($row['aoption']);
$status = $row['status'];
$num = $row['num'];




if ($status != 1) exit();


$erflag=1;
$aoption['error'] = "";
if(!($f= @fopen($file,"r"))){
	//echo "error";
	$erflag = 0;
	$aoption['error'] = "Не могу прочитать файл $file";
    //echo $file;
}

if(@fseek($f,$seek, SEEK_SET) == -1){
	//echo "error1";
	$erflag = 0;
	$aoption['error'] = "error1";}

if ($seek == 0)
	$str = @fgets($f,1000);


echo $aoption['error'];
$c =1;
while (($c<=$num) && ($erflag == 1)) {	

	if (!($str = @fgets($f,1000))){ 
		$sql = "UPDATE ".$SysValue['base']['table_name40']." SET 
		status = '2', 
		seek = '$seek', 
		num_new = '$num_new', 
		num_upd = '$num_upd' 
		WHERE id = '1'
		";
		mysql_query($sql);			
		exit();
	}
	$data = explode(";",$str);
	$c++;
	
	if (intval($data[8]) < 1) {
		if ($aoption['sklad'] == 1) $sklad = " , enabled='0', sklad='0' ";
		if ($aoption['sklad'] == 2) $sklad = " , enabled='1', sklad='1' ";
	}
	elseif (intval($data[8]) > 0){
		if ($aoption['sklad'] == 1) $sklad = " , enabled='1', sklad='0' ";
		if ($aoption['sklad'] == 2) $sklad = " , enabled='1', sklad='0' ";
	}
	if (intval($data[9]) < 1) {
		$data[9] = 1000002;
	}
	
	$ff = 0;
	if ($aoption['ident'] == 0)			{
		$ff = mysql_num_rows(mysql_query("SELECT id from ".$SysValue['base']['table_name2']." WHERE id = '".$data[0]."'"));
		if ($ff > 0) {
			$sql="
			UPDATE phpshop_products SET 
			uid = '".$data[1]."',
			name = '".$data[2]."',
			price = '".$data[3]."',
			price2 = '".$data[4]."',
			price3 = '".$data[5]."',
			price4 = '".$data[6]."',
			price5 = '".$data[7]."',
			items = '".$data[8]."', 
			baseinputvaluta = '".$aoption['valuta']."', 
			category = '".$data[9]."' 
			$sklad
			where id = ".$data[0];

			mysql_query($sql);
			$num_upd++;
		}
		else{
			$sql="
			INSERT INTO ".$SysValue['base']['table_name2']." SET 
			uid = '".$data[1]."',
			name = '".$data[2]."',
			price = '".$data[3]."',
			price2 = '".$data[4]."',
			price3 = '".$data[5]."',
			price4 = '".$data[6]."',
			price5 = '".$data[7]."',
			items = '".$data[8]."',
			baseinputvaluta = '".$aoption['valuta']."',   
			category = '".$data[9]."' 			
			$sklad
			";
			mysql_query($sql);
			$num_new++;
		}
		
	}
	else {
		$ff = mysql_num_rows(mysql_query("SELECT id from ".$SysValue['base']['table_name2']." WHERE uid LIKE '".$data[1]."'"));
		if ($ff > 0) {
			$sql="
			UPDATE phpshop_products SET 
			name = '".$data[2]."',
			price = '".$data[3]."',
			price2 = '".$data[4]."',
			price3 = '".$data[5]."',
			price4 = '".$data[6]."',
			price5 = '".$data[7]."',
			items = '".$data[8]."',
			baseinputvaluta = '".$aoption['valuta']."', 
			category = '".$data[9]."'  
			$sklad
			where uid = '".$data[1]."'
			";
			mysql_query($sql);
			$num_upd++;
		}
		else{
			$sql="
			INSERT INTO ".$SysValue['base']['table_name2']." SET 
			uid = '".$data[1]."',
			name = '".$data[2]."',
			price = '".$data[3]."',
			price2 = '".$data[4]."',
			price3 = '".$data[5]."',
			price4 = '".$data[6]."',
			price5 = '".$data[7]."',
			items = '".$data[8]."',
			baseinputvaluta = '".$aoption['valuta']."', 
			category = '".$data[9]."'  
			$sklad
			";
			mysql_query($sql);
			$num_new++;
		}
	}
	
	

}
$seek = @ftell($f);


	$sql = "UPDATE ".$SysValue['base']['table_name40']." SET 	 
	seek = '$seek', 
	num_new = '$num_new', 
	num_upd = '$num_upd',
	aoption = '".serialize($aoption)."'  
	WHERE id = '1'
	";
	mysql_query($sql);


@fclose($f);

?>
