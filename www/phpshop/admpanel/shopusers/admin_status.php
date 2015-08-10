<?
function ShopUsersStatus()// Вывод 
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name28']." order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$discount=$row['discount'];
	if(($row['enabled'])=="1"){$checked="<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"В наличии\">";}else{$checked="<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Отсутствует\">";};
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('shopusers/adm_statusID.php?id=$id',450,270,event)\">
    <td align=center class=forma>$checked</td>
    <td class=forma>
	".$name."
	</td>
	<td class=forma>
	".$discount."%
	</td>
    </tr>
	";
	@$i++;
	}
if($i>20)$razmer="height:600;";
	return "
	
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\"class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"50\" id=pane align=center><span name=txtLang id=txtLang>Статус</span></td>
	<td id=pane align=center><span name=txtLang id=txtLang>Название</span></td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Скидка</span></td>
</tr>

	".$display."

    </table>
	</td>
</tr>
    </table>

<div align=\"right\" style=\"padding:10;width:50%\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('shopusers/adm_status_new.php',450, 270)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>
	";
}
?>
