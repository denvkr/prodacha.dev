<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Сортировки                  |
+-------------------------------------+
*/

function DispCatSortTable($category,$vendor_array){ // Вывод сортировок таблицей
global $SysValue,$LoadItems;

// Выводить имя набора характеристики
$categorySort = "false";

$sort=unserialize($LoadItems['Podcatalog'][$category]['sort']);
$vendor_array=unserialize($vendor_array); 
$categoryControl="";
if(is_array($sort))
foreach($sort as $v){
$sql="select * from ".$SysValue['base']['table_name20']." where id=$v order by num";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
while (@$row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$category=$row['category'];
	$clean="false";
	
  if($categoryControl!=$category and $categorySort == "true")
  @$dis.= '
<tr>
   <td colspan="2"><b>'.$LoadItems['CatalogSort'][$category]['name'].'</b></td>
</tr>';

$count=count($vendor_array[$id]);
$srt_value="";
if( $count> 1)
  $zpt=", ";
  else $zpt="";
  
if(is_array($vendor_array[$id]))
foreach($vendor_array[$id] as $p){

     if(!@$LoadItems['Sort'][$p]['name']) {
	   @$srt_value="-   ";
	   $clean="true";
	   }
	  else @$srt_value.=$LoadItems['Sort'][$p]['name'].$zpt;
	 }
	 
if( $count> 1){
$leng=strlen($srt_value);
$srt_value = substr($srt_value,0,$leng-2);
}

if( $count == 0  ) $clean="true";


if( $clean!="true"){
@$dis.= '
<tr>
  <td class="sort_name_bg">'.$name.':</td>
  <td>'.$srt_value.'</td>
</tr>
';}
$categoryControl=$category;
}
}

$disp="<table class=sort_table cellpadding=3>$dis</table>";
return @$disp;
}


function dispValue($n,$title){ // вывод характеристик
global $SysValue;
$vendor=$SysValue['nav']['query']['v'];
$sql="select * from ".$SysValue['base']['table_name21']." where category='$n' order by num";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=substr($row['name'],0,35);
	$sel="";
	if(is_array($vendor))
	foreach($vendor as $k=>$v){
	if ($id == $v) $sel="selected";
	}
    @$dis.="<option value=".$id." ".$sel." >".$name."</option>\n";
	}
$SysValue['sort'][]=$n;
$SysValue['sql']['debug']=$SysValue['sort'];
@$disp="
<select name=v[$n] size=1 id=$n>
<option value='' selected>-- все $title --</option>
$dis
</select>
";
@$SysValue['sql']['num']++;

return @$disp;
}


function DispCatSort($category){ // Вывод сортировок
global $SysValue,$LoadItems;
$sort=unserialize($LoadItems['Podcatalog'][$category]['sort']);
	foreach($sort as $v){
$sql="select * from ".$SysValue['base']['table_name20']." where id=$v and filtr='1' order by num";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
$num=mysql_num_rows($result);
while (@$row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	
@$disp.= '<td>
'.dispValue($id,$name).'
</td>
';
}}
@$SysValue['sql']['num']++;
return @$disp;
}

?>
