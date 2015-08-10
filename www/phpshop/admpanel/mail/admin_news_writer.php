<?
class ReadCsvUnic {
   var $CsvContent;
   var $ReadCsvRow;
   var $TableName;
   
   function ReadCsvRow(){
   $this->ReadCsvRow = split("\n",$this->CsvContent);
   array_shift($this->ReadCsvRow);
   array_pop($this->ReadCsvRow);
   }

   function CsvToArray(){
       while (list($key, $val) = each($this->ReadCsvRow)) {
       $array1=split(";",$val);
            $OutArray[$array1[0]]=$array1;
       }
   return $OutArray;
   }
   
   function ReadCsvUnic($CsvContent,$table_name2){
   $this->CsvContent = $CsvContent;
   $this->TableName = $table_name2;
   $this->ReadCsvRow();
   }
   
   
   function DoUpdatebase(){
    $CsvToArray = $this->CsvToArray();
	foreach ($CsvToArray as $v)
	$this->UpdateBase($v);
   }
   
   function UpdateBase($CsvToArray){
$sql="INSERT INTO ".$this->TableName." 
VALUES ('','".date("d-m-y")."','".$CsvToArray[0]."')";
   $result=mysql_query($sql) or die ("".$sql);
   }
   
   function UpdateBaseClean(){
   $sql = "TRUNCATE TABLE ".$this->TableName; 
   $result=mysql_query($sql) or die ("".$sql);
   }
}


if(isset($_POST['loadBase'])){

@copy("$csv_file","csv/$csv_file_name");
@$fp = fopen("csv/$csv_file_name", "r");

  if ($fp) {
  //stream_set_write_buffer($fp, 0);
  $fstat = fstat($fp);
  $CsvContent=fread($fp,$fstat['size']);
  fclose($fp);
  
  if($_POST['status'] == 1){
  $ReadCsv = new ReadCsvUnic($CsvContent,$table_name9);
  $ReadCsv->UpdateBaseClean();
  $Done = $ReadCsv->DoUpdatebase();
  }
  if($_POST['status'] == 2){
  $ReadCsv = new ReadCsvUnic($CsvContent,$table_name9);
  $Done = $ReadCsv->DoUpdatebase();
  }
  }
}






function News_writer()
{
global $table_name9,$PHP_SELF,$systems,$page,$num_row;
$sql="select * from $table_name9 order by id desc";
$result=mysql_query($sql);
if(!isset($num_row)) $num_row=$systems['num_row_adm'];
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$data=$row['datas'];
	$mail=$row['mail'];
	@$display.=('
	<tr  onmouseover="show_on(\'r'.$id.'\')" id="r'.$id.'" onmouseout="show_out(\'r'.$id.'\')" class=row onclick="miniWin(\'mail/adm_news_writerID.php?id='.$id.'\',400,250)">
	<td class=forma align="center">
	'.dataV($data,"shot").'
	</td>
	<td class=forma>
	'.$mail.'
	</td>
    </tr>
	');
	@$i++;
	}
if($i>30)$razmer="height:600;";
	return ('
<table width="100%">
<tr>
	<td valign="top">
<table width="100%"  cellpadding="0" cellspacing="0" style="border: 1px;
	border-style: inset;">
<tr>
	<td valign="top" width="50%">
	<div align="left" style="width:100%;'.@$razmer.';overflow:auto"> 
<table cellpadding="0" cellspacing="1" width="100%" border="0" bgcolor="#808080" class="sortable" id="sort">
<tr>
	<td width="100" id=pane align=center><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Дата</span></td>
	<td width="300" id=pane align=center><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5>E-mail</td>
</tr>
	'.$display.'
</table>
</td>
	</tr>
</table>
</div>
<div align="right" style="padding:10"><BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin(\'mail/adm_news_writer_new.php\',400,250)">
<img src="icon/page_add.gif" width="16" height="16" border="0" align="absmiddle">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</td>
<td valign="top">

<FIELDSET id=fldLayout style="width: 50em; height: 8em;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>В</u>ыгрузка</span></LEGEND>
<table cellpadding="10">
<tr>
	<td >
	<input type="submit" value="OK" class=but onclick="javascript:miniWin(\'./export/adm_csv.php?DO=news_writer\',300,300);"><br><br>
	<span name=txtLang id=txtLang>*Выгрузка базы подписчиков осуществляется в формат</span> CSV (Excel)
	</td>
	<td>
	</td>
</tr>
</table>
</FIELDSET>
<!-- <br><br>
<FIELDSET id=fldLayout style="width: 50em; height: 8em;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>агрузка</span></LEGEND>
<table cellpadding="10">
<form enctype="multipart/form-data" method="post">
<tr>
	<td>
	<span name=txtLang id=txtLang>Выберете файл с разширением</span> *.csv   <br>
	<input type="file" style="width: 500;" name="csv_file"><br>
	<input type="radio" value="1" name="status"> <span name=txtLang id=txtLang>Заменить базу</span>
	<input type="radio" value="2" name="status" checked> <span name=txtLang id=txtLang>Добавить новые записи</span><br>
	<input type="submit" name="loadBase" value="OK" class=but>
	</td>
</tr>
</form>
</table>
</FIELDSET> -->
</td>
</tr>
</table>
	');
}
?>
