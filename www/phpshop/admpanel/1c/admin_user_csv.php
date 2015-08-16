<?
$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("readcsv");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("mail");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();



class ReadCsv1C extends PHPShopReadCsv{
   var $CsvContent;
   var $ReadCsvRow;
   var $TableName;
   var $CsvToArray;
   var $TotalUpdate=0;
   var $TotalCreate=0;
   var $UserStatus;
   var $n=1;
   var $AdminMail;
   var $ShopName;
   var $TotalSend=0;
   
   function ReadCsv1C($file){
   $this->CsvContent = parent::readFile($file);
   $this->TableName = $GLOBALS['SysValue']['base']['table_name27'];
   parent::PHPShopReadCsv();
   
   // Отправка приглашений
   $PS = new PHPShopSystem();
   $this->UserStatus=$PS->getSerilizeParam("admoption.user_status");
   $this->AdminMail=$PS->getParam("adminmail2");
   $this->ShopName=$PS->getParam("name");
   }
   
   function DoUpdatebase1(){
   $CsvToArray = $this->CsvToArray;
   foreach ($CsvToArray as $v)
   @$return.=$this->PrintResultDo($v);
   return $return;
   }
   
   function DoUpdatebase2(){
   $CsvToArray = $this->CsvToArray;
   foreach ($CsvToArray as $v)
   $this->UpdateBase($v);
   }


   function CheckBase($mail){// Проверяем есть ли товар
   $num=0;
   $sql="select id from ".$this->TableName." where mail='$mail'";
   $result=mysql_query($sql);
   $num=mysql_num_rows($result);
   return @$num;
   }
   
   
   // Генерация пароля
   function getPassword(){
   return substr(abs(crc32(rand(1000,9000))),0,6);
   }
   
   // Генерация логина
   function getLogin(){
   $r = substr($this->getPassword(),0,4);
   return "user".$r;
   }
   
   function sendMail($to,$login,$password){
   $from=$this->AdminMail;
   $zag="Добро пожаловать в ".$this->ShopName;
   $content="
Доброго времени!
--------------------------------------------------------

Вам пришло приглашение посетить Интернет-магазин http://".$_SERVER["SERVER_NAME"]."
Для получения скидок и участия в акциях воспользуйтесь авторизацией в личном кабинете:

Пользователь: $login
Пароль: ".base64_decode($password)."
";
   $PM = new PHPShopMail($to,$from,$zag,$content);
   $this->TotalSend++;
   }
   
   
   function UpdateBase($CsvToArray){
   
   if(!empty($CsvToArray[5]))
     $CheckBase=$this->CheckBase($CsvToArray[5]);
	 else $CheckBase=false;
   
   
   
if(!empty($CheckBase) and $CsvToArray[0]!=""){// Обновляем  
   
$sql="UPDATE ".$this->TableName." SET ";

// Отсеиваем поля
if($_REQUEST['tip'][22] == 1) $sql.="name = '".parent::CleanStr($CsvToArray[6])."',  ";// лицо
if($_REQUEST['tip'][16] == 1) $sql.="company = '".parent::CleanStr($CsvToArray[0])."', ";// наименование
if($_REQUEST['tip'][17] == 1) $sql.="inn = '".parent::CleanStr($CsvToArray[1])."', ";// инн
if($_REQUEST['tip'][20] == 1) $sql.="tel = '".parent::CleanStr($CsvToArray[4])."', ";// телефон
if($_REQUEST['tip'][19] == 1) $sql.="adres = '".parent::CleanStr($CsvToArray[3])."', ";// адрес
if($_REQUEST['tip'][18] == 1) $sql.="kpp = '".parent::CleanStr($CsvToArray[2])."'  ";// кпп
$sql.="datas = ".date("U");
$sql.=" where mail='".$CsvToArray[5]."'";
$result=mysql_query($sql);
$this->TotalUpdate++;

}else{// Создаем новый 

// Отсеиваем поля
if($_REQUEST['tip'][16] != 1) $CsvToArray[0]="";// наименование
if($_REQUEST['tip'][17] != 1) $CsvToArray[1]="";// инн
if($_REQUEST['tip'][18] != 1) $CsvToArray[2]="";// кпп
if($_REQUEST['tip'][19] != 1) $CsvToArray[3]="";// адрес
if($_REQUEST['tip'][20] != 1) $CsvToArray[4]="";// телефон
if($_REQUEST['tip'][21] != 1) $CsvToArray[5]="";// почта
if($_REQUEST['tip'][22] != 1) $CsvToArray[6]="";// лицо

$login=$this->getLogin();
$password=base64_encode($this->getPassword());

$sql="INSERT INTO ".$this->TableName." SET 
login = '".$login."',
password = '".$password."',
datas = ".date("U").",
mail = '".parent::CleanStr($CsvToArray[5])."',
name = '".parent::CleanStr($CsvToArray[6])."', 
company = '".parent::CleanStr($CsvToArray[0])."',
inn = '".parent::CleanStr($CsvToArray[1])."',
tel = '".parent::CleanStr($CsvToArray[4])."',
adres = '".parent::CleanStr($CsvToArray[3])."',
enabled = '1',
status = ".$this->UserStatus.",
kpp = '".parent::CleanStr($CsvToArray[2])."'
";
$result=mysql_query($sql);
$this->TotalCreate++;




// Рассылка приглашений
if(!empty($CsvToArray[5]))
  if($_REQUEST['tip'][23] == 1)
    $this->sendMail($CsvToArray[5],$login,$password);
 }
   }
   

function PrintResultDo($CsvToArray){
    if(!empty($CsvToArray[0])){
	
	if(empty($CsvToArray[5])) $style="style='color: red;'";
	  else $style="";
	
	$disp="
	<tr class='row' style=\"padding:3\" onmouseover=\"show_on('r".$CsvToArray[0]."')\" id=\"r".$CsvToArray[0]."\" onmouseout=\"show_out('r".$CsvToArray[0]."')\">
    <td align=center >".($this->n)."</td>
	 <td $style>".$CsvToArray[0]."</td>
	 <td >".$CsvToArray[1]."</td>
	 <td >".$CsvToArray[2]."</td>
	 <td >".$CsvToArray[3]."</td>
	 <td >".$CsvToArray[4]."</td>
	 <td >".$CsvToArray[5]."</td>
	 <td >".$CsvToArray[6]."</td>
	</tr>
	";
	$this->n++;
	}
	return $disp;
   }
}



require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest = new JsHttpRequest("windows-1251");

// Расширение
$_FILES['file']['ext']=PHPShopSecurity::getExt($_FILES['file']['name']);

if($_REQUEST['page']=="predload" and $_FILES['file']['ext']=="csv"){

// Загружаем
if(move_uploaded_file(@$_FILES['file']['tmp_name'], "../csv/".@$_FILES['file']['name'])){

  $ReadCsv = new ReadCsv1C("../csv/".@$_FILES['file']['name']);
  $interface.='
<div id=interfacesWin name=interfacesWin align="left" style="width:100%;height:580;overflow:auto"> 
<TABLE cellSpacing=0 cellPadding=0 width="100%"><TBODY>
<TR>
<TD vAlign=top>
<table width="100%" cellpadding="0" cellspacing="1" class="sortable" id="sort">
<tr>
    <td id="pane" width="50">№</td>
	<td id="pane" width="100">Наименование</td>
	<td id="pane">ИНН</td>
	<td id="pane">КПП</td>
	<td id="pane">Адрес</td>
	<td id="pane">Телефон</td>
	<td id="pane">E-mail</td>
	<td id="pane">Контактное лицо</td>
</tr>
'.$ReadCsv->DoUpdatebase1().'
</table>
</TD></TR><form method=post action="" encType=multipart/form-data name=forma2></TBODY></TABLE>
</div>
<div align="center" style="padding-top:20">
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoReload(\'csv1c\')">
<img src="img/icon-setup2.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
Выбрать другой файл</button>
&nbsp;&nbsp;
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoLoadBase1C(null,\'load\',\''.$_FILES['file']['name'].'\')">
<img src="img/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
Принять изменения</button>';

foreach($_REQUEST['tip'] as $key=>$val)
       $interface.='<input type="hidden" id="tip_'.$key.'" value="'.$_REQUEST['tip'][$key].'">';

$interface.='
<input type="hidden" id="1c_target_check" value="2">
<input type="hidden" id="1c_tree_check" value="0">
</form></div>
    ';
  }} elseif($_REQUEST['page']=="load"){
$ReadCsv = new ReadCsv1C("../csv/".@$_REQUEST['name']);
$ReadCsv->DoUpdatebase2();
$interface.='
<TABLE cellSpacing=0 cellPadding=0 width="50%" align="center">
<TR>

<TD vAlign=top style="padding-top:25">
<div align="center"><h4><span name=txtLang2 id=txtLang2>Загрузка контрагентов 1C:Предприятие выполнена!</span></h4></div>
<FIELDSET id=fldLayout style="width: 60em; height: 8em;">
<table style="border: 1px;border-style: inset;background-color: White;" cellpadding="10" width="100%">
<tr>
	<td width="50%" ><h4><span name=txtLang2 id=txtLang2>Отчет:</span></h4>
<ol>
	<li>Создано новых позиций: '. $ReadCsv->TotalCreate.'
	<li>Обновлено позиций: '. $ReadCsv->TotalUpdate.'
	<li>Приглашений отправлено: '.$ReadCsv->TotalSend.'
</ol></td>
</tr>
</table>
</FIELDSET>
</TD></TR></TABLE>
    ';

}else @$interface.=$disp='
	  <table width="100%" height="100%" style="Z-INDEX:2;">
<tr>
	<td valign="middle" align="center">
		<div style="width:400px;height:100px;BACKGROUND: #C0D2EC;padding:10px;border: solid;border-width: 1px; border-color:#4D88C8;FILTER: alpha(opacity=80);" align="left">
<table width="100%" height="100%">
<tr>
	<td width="35" vAlign=center ><IMG 
            hspace=0 src="img/i_support_med[1].gif" align="absmiddle" 
            border=0 ></td>
	<td ><b>Внимание, выберите файл с расширением *.csv.
	Повторите  <a href="javascript:DoReload(\'csv1c\')" style="color:red">загрузку файла</a>.</b></td>
</tr>
</table>

		</div>
</td>
</tr>
</table>
	  
	  ';

$_RESULT = array(
  "name"   => @$_FILES['file']['name'],
  "content"=> @$interface,
  "size"   => @filesize($_FILES['file']['tmp_name']),
); 
?>
