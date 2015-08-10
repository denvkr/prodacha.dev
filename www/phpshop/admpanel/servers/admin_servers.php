<?
function Servers()// Вывод доставки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name31']." order by name";
$result=mysql_query($sql);

while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$host=$row['host'];
	$enabled=$row['enabled'];
	if(($enabled)=="1"){$checked="<img src=img/icon-activate.gif  >";}else{$checked="<img src=img/icon-deactivate.gif>";};
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('servers/adm_serversID.php?id=$id',400,350,event)\">
<td align=\"center\">$checked</td>
<td class=forma>$id</td>
<td>
	$name
	</td>
	<td class=forma>
	$host
	</td>
    </tr>
	";
	@$i++;
	}
if($i>30)$razmer="height:600;";
	return "
<div align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"70%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"50\" id=pane align=>+/-</td>
	<td width=\"50\" id=pane align=>ID</td>
	<td width=\"50%\" id=pane align=><span name=txtLang id=txtLang>Название</span></td>
	<td width=\"40%\" id=pane align=><span name=txtLang id=txtLang>Хост</span></td>
</tr>
	".$display."
    </table>
</table>

<div align=\"right\" style=\"width:70%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('servers/adm_servers_new.php',400,350)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div></div>
	";
}
?>
