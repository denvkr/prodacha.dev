<?
include("login.php");

// Подключаем настройки из базы
$PS = new PHPShopSystem();

if(empty($_GET['limit'])) $limit=100;
  else $limit=$_GET['limit'];

$sql="select * from ".$GLOBALS['SysValue']['base']['table_name12']." order by id desc limit $limit ";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result)){
$id=$row['id'];
$datas=$row['datas'];
$p_name=$row['p_name'];
$f_name=$row['f_name'];
$time=$row['time'];

$dis.='
<tr bgcolor="#ffffff">
	<td>'.$id.'</td>
	<td>'.date("d-m-y H:s",$datas).'</td> 
	<td><a href="'.$p_name.'/'.$f_name.'" target="_blank">'.$p_name.'/'.$f_name.'</a></td>
	<td>'.$time.'</td>
	<td><a href="result.php?date='.$p_name.'&log='.$_REQUEST['log'].'&pas='.$_REQUEST['log'].'&files='.trim($f_name).'&create=true&create_category=true">Выполнить</a></td>
</tr>';

$disp='
<h1>PHPShop 1C Log</h1>
<table border="0" cellpadding="3" cellspacing="1" bgcolor="#808080">
<tr bgcolor="#6699cc">
	<td>№</td>
	<td>Дата</td>
	<td>Файл</td>
	<td>Время</td>
   	<td>Операции</td>
</tr>
'.$dis.'
</table>
';
}
echo $disp;
?>
