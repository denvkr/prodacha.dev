<?

function OrderStatus()// вывод статусов
{
global $table_name32,$UserStatus,$SysValue;

$sql="select * from $table_name32 order by name";
@$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
    $name=$row['name'];
	$color=$row['color'];
	$order=unserialize($row['orders']);

	@$disp.='
	<tr valign="top" bgcolor="ffffff" onmouseover="show_on(\'r'.$id.'\')" id="r'.$id.'" onmouseout="show_out(\'r'.$id.'\')" class=row onclick="miniWin(\'order/adm_statusID.php?id='.$id.'\',460,260,event)">
    <td class=forma valign="middle" align="center">'.$name.'</td>
	<td class=forma valign="middle" align="center" bgcolor="'.$color.'">
	'.$color.' </td>
</tr>
	';
    @$i++;
    }
if($i>30)$razmer="height:600;";
$_Return=('
<div align="left" style="width:100%;'.@$razmer.';overflow:auto"> 
<table width="50%"  cellpadding="0" cellspacing="0">
<tr>
	<td valign="top">

<table cellpadding="0" cellspacing="1" width="100%" border="0" class="sortable" id="sort">
<tr>
	<td width="200" id="pane" align="center">
	<img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Наименование</span></td>
	<td id="pane" align="center"><img src=img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Цвет</span></td>
</tr>
'.@$disp.'
</form>
</table>
</table>
<div align="right" style="width:50%;padding:10"><BUTTON style="width: 15em; height: 2.2em; margin-left:5"  onclick="miniWin(\'order/adm_status_new.php\',460,260)">
<img src="icon/page_add.gif" width="16" height="16" border="0" align="absmiddle" hspace="5">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>
 ');
return $_Return;
}
?>